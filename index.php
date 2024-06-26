<?php

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Caiofior\Core\ProfileValidation;

require __DIR__ . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Add DI container definitions
$containerBuilder->addDefinitions(__DIR__ . '/config/container.php');
// Create Slim App instance
AppFactory::setContainer($containerBuilder->build());
$app = AppFactory::create();

$app->setBasePath(($app->getContainer()->get('settings')['basePath'] ?? ''));
$app->redirect('/index.php', ($app->getContainer()->get('settings')['basePath'] ?? '') . '/', 301);
session_start();
if(rand(1,100)==1) {
    $command = 'php '.__DIR__.'/cli/cli-config.php seo:sitemap-generator >/dev/null 2>&1 &';
    exec($command);
}
$app->get('/', function (Request $request, Response $response, $args) {
    $userAgent = ($request->getHeader('User-Agent')[0]??'');
    if (stripos($userAgent,'curl') === 0) {
    	require __DIR__ . '/theme/cli/index.php';
	exit();
    }
    $theme = ($this->get('settings')['theme'] ?? '');	
    if (!empty($theme)) {
        require __DIR__ . '/theme/' . $theme . '/index.php';
    }
    return $response;
});
$app->any('/index.php/login', function (Request $request, Response $response, $args) {
    $theme = ($this->get('settings')['theme'] ?? '');
    /** @var \Doctrine\ORM\EntityManager $entityManager */
    $entityManager = $this->get('entity_manager');
    $message = '';
    $page = 'login';
    if(!empty($request->getQueryParam('validation'))) {
        $profileValidation = new ProfileValidation($this);
        $profileValidation->validateToken($request->getQueryParam('validation'));
        $message = 'Email confermata, accedi al sito';
    } elseif (isset($request->getParsedBody()['register'])) {
        $entityManager->beginTransaction();
        $profile = new \Caiofior\Core\model\Profile();
        $profile->setRoleId(3);
        $entityManager->persist($profile);
        $entityManager->flush();
        $login = new \Caiofior\Core\model\Login();
        try {
            $login->setUsername($request->getParsedBody()['username']);
            if ($request->getParsedBody()['password'] != $request->getParsedBody()['ripeti_password']) {
                throw new \Exception('Different password', 4);
            }
            $login->setPassword($request->getParsedBody()['password']);
            $login->setProfileId($profile->getId());
            $login->setCreationDatetime(new DateTimeImmutable());
            $entityManager->persist($login);
            $entityManager->flush();
        } catch (\Exception $e) {
            if ($e instanceof \Exception) {
                switch ($e->getCode()) {
                    case 1:
                        $message .= 'Utente obbligatorio';
                        break;
                    case 2:
                        $message .= 'Password obbligatoria';
                        break;
                    case 4:
                        $message .= 'Password diverse';
                        break;
                }
            } else if ($e instanceof \Doctrine\DBAL\Exception\UniqueConstraintViolationException) {
                $message .= 'Utente già presente';
            }
            $entityManager->rollback();
        }
        $entityManager->commit();
        $profileValidation = new ProfileValidation($this);
        $profileValidation->sentValidationMail($profile);
        $message = 'Email confermata, accedi al sito';
    } elseif (isset($request->getParsedBody()['login'])) {
        /** @var \Caiofior\Core\model\Login $login */
        $login = $entityManager->find('\Caiofior\Core\model\Login', $request->getParsedBody()['username']);
        if (is_object($login)) {
            try {
                $login->checkPassword($request->getParsedBody()['password']);
                $_SESSION['username'] = $login->getUsername();
            } catch (\Exception $e) {
                switch ($e->getCode()) {
                    case 3:
                        $message .= 'Credenziali non valide';
                        break;
                }
            }
            $profile = $entityManager->find('\Caiofior\Core\model\Profile', $login->getProfileId());
            if ($profile->getData()['active']!= 1) {
                $message .= 'Credenziali non valide';    
            }
        } else {
            $message .= 'Credenziali non valide';
        }
        $login->setLastLogin(new DateTimeImmutable());
        $entityManager->persist($login);
        $entityManager->flush();
        $page = 'dashboard';
    } elseif (isset($request->getParsedBody()['recover'])) {
        $login = $entityManager->find('\Caiofior\Core\model\Login', $request->getParsedBody()['username']);
        if (is_object($login)) {
            $profileValidation = new ProfileValidation($this);
            $profileValidation->sentRecoverMail($login);
        } else {
            $message .= 'Utente non trovato';
        }
    }

    if (!empty($theme)) {
        require __DIR__ . '/theme/' . $theme . '/index.php';
    }
    return $response;
});
$app->any('/index.php/logout', function (Request $request, Response $response, $args) {
    session_destroy();
    unset($_SESSION);
    $theme = ($this->get('settings')['theme'] ?? '');
    if (!empty($theme)) {
        require __DIR__ . '/theme/' . $theme . '/index.php';
    }
    return $response;
});
$app->any('/index.php/profilo', function (Request $request, Response $response, $args) {
    $theme = ($this->get('settings')['theme'] ?? '');
    /** @var \Doctrine\ORM\EntityManager $entityManager */
    $entityManager = $this->get('entity_manager');
    $message = '';
    $page = 'profile';
    $login = $entityManager->find('\Caiofior\Core\model\Login', $_SESSION['username']);
    $profile = $entityManager->find('\Caiofior\Core\model\Profile', $login->getProfileId());
    if (isset($request->getParsedBody()['salva'])) {
        $profile->setData($request->getParsedBody());
        $entityManager->persist($profile);
        $entityManager->flush();
        $profile = $entityManager->find('\Caiofior\Core\model\Profile', $login->getProfileId());
        $page = '';
    }
    if (!empty($theme)) {
        require __DIR__ . '/theme/' . $theme . '/index.php';
    }
    return $response;
});
$app->any('/index.php/password', function (Request $request, Response $response, $args) {
    $theme = ($this->get('settings')['theme'] ?? '');
    /** @var \Doctrine\ORM\EntityManager $entityManager */
    $entityManager = $this->get('entity_manager');
    $message = '';
    $page = 'password';
    $login = $entityManager->find('\Caiofior\Core\model\Login', $_SESSION['username']);
    if (isset($request->getParsedBody()['salva'])) {
        try {
            if ($request->getParsedBody()['password'] != $request->getParsedBody()['ripeti_password']) {
                throw new \Exception('Different password', 4);
            }
            $login->setPassword($request->getParsedBody()['password']);
            $entityManager->persist($login);
            $entityManager->flush();
            $page = '';
        } catch (\Exception $e) {
            if ($e instanceof \Exception) {
                switch ($e->getCode()) {
                    case 2:
                        $message .= 'Password obbligatoria';
                        break;
                    case 4:
                        $message .= 'Password diverse';
                        break;
                }
            }
        }
    }
    if (!empty($theme)) {
        require __DIR__ . '/theme/' . $theme . '/index.php';
    }
    return $response;
});
$app->group('/index.php/contenuti', function (RouteCollectorProxy $group) {
    return \Caiofior\Control\Contents::parse($group);
});
$app->group('/index.php/calendari', function (RouteCollectorProxy $group) {
    return \Caiofior\Control\CalendarProperties::parse($group);
});
$app->group('/index.php/preghiere', function (RouteCollectorProxy $group) {
    return \Caiofior\Control\Prey::parse($group);
});
try {
    $app->run();
} catch(\Slim\Exception\HttpNotFoundException $e )  {
    $error = 'Errata configurazione dei percorsi';
    require __DIR__.'/theme/technext/small-apps/error.php';
    exit;
} catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
    $error = 'Errata configurazione del database';
    require __DIR__.'/theme/technext/small-apps/error.php';
    exit;
}
<?php

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

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

$app->get('/', function (Request $request, Response $response, $args) {
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
    if (isset($request->getParsedBody()['register'])) {
        $entityManager->beginTransaction();
        $profile = new \Caiofior\Core\Profile();
        $profile->setRoleId(3);
        $entityManager->persist($profile);
        $entityManager->flush();
        $login = new \Caiofior\Core\Login();
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
    }
    if (isset($request->getParsedBody()['login'])) {
        /** @var \Caiofior\Core\Login $login */
        $login = $entityManager->find('\Caiofior\Core\Login', $request->getParsedBody()['username']);
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
        } else {
            $message .= 'Credenziali non valide';
        }
        $login->setLastLogin(new DateTimeImmutable());
        $entityManager->persist($login);
        $entityManager->flush();
        $page = 'dashboard';
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
    $login = $entityManager->find('\Caiofior\Core\Login', $_SESSION['username']);
    $profile = $entityManager->find('\Caiofior\Core\Profile', $login->getProfileId());
    if (isset($request->getParsedBody()['salva'])) {
        $profile->setData($request->getParsedBody());
        $entityManager->persist($profile);
        $entityManager->flush();
        $profile = $entityManager->find('\Caiofior\Core\Profile', $login->getProfileId());
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
    $login = $entityManager->find('\Caiofior\Core\Login', $_SESSION['username']);
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
$app->any('/index.php/calendari', function (Request $request, Response $response, $args) {
    $theme = ($this->get('settings')['theme'] ?? '');
    $page = 'calendar';
    if (!empty($theme)) {
        require __DIR__ . '/theme/' . $theme . '/index.php';
    }
    return $response;
});
$app->any('/index.php/calendari/list', function (Request $request, Response $response, $args) {
    /** @var \Doctrine\ORM\EntityManager $entityManager */
    $entityManager = $this->get('entity_manager');
    $results = $entityManager->getRepository('\Caiofior\CatholicLiturgical\CalendarProperties')->findBy([]);
    $data = array();
    array_walk($results, function ($value, $key) use (&$data) {
        $data[$key] = json_decode(json_encode($value));
        $data[$key]->actions = <<<EOT
<div class="icon-container">
    <a href="{$this->get('settings')['baseUrl']}/index.php/calendari/modifica/{$data[$key]->id}">
        <span class="ti-pencil"></span>
    </a>
    <a class="trash" href="{$this->get('settings')['baseUrl']}/index.php/calendari/cancella/{$data[$key]->id}">
        <span class="ti-trash"></span>  
    </a>
</div>  
EOT;
    });
    return $response->withJson($data, 201);
});
$app->any('/index.php/calendari/modifica[/{id}]', function (Request $request, Response $response, $args) {
    $theme = ($this->get('settings')['theme'] ?? '');
    /** @var \Doctrine\ORM\EntityManager $entityManager */
    $entityManager = $this->get('entity_manager');
    $message = '';
    $page = 'calendar/add';
    $calendar = new \Caiofior\CatholicLiturgical\CalendarProperties();
    if(!empty($args['id'])) {
        $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\CalendarProperties', $args['id']);
    }
    /** @var \Caiofior\Core\Login $login * */
    $login = $entityManager->find('\Caiofior\Core\Login', ($_SESSION['username'] ?? ''));
    if (isset($request->getParsedBody()['salva'])) {
        try {
            $data = $request->getParsedBody();
            $data['profile_id'] = $login->getProfileId();
            $calendar->setData($data);
            $entityManager->persist($calendar);
            $entityManager->flush();
            return $response->withHeader('Location', $this->get('settings')['baseUrl'].'/index.php/calendari')->withStatus(302);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    if (!empty($theme)) {
        require __DIR__ . '/theme/' . $theme . '/index.php';
    }
    return $response;
});
$app->any('/index.php/calendari/cancella/{id}', function (Request $request, Response $response, $args) {
    $theme = ($this->get('settings')['theme'] ?? '');
    /** @var \Doctrine\ORM\EntityManager $entityManager */
    $entityManager = $this->get('entity_manager');
    $message = '';
    $page = 'calendar/add';
    $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\CalendarProperties', $args['id']);
    $entityManager->remove($calendar);
    $entityManager->flush();
    return $response->withHeader('Location', $this->get('settings')['baseUrl'].'/index.php/calendari')->withStatus(302);
});
$app->run();

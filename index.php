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

$app->setBasePath(($app->getContainer()->get('settings')['basePath']??''));
$app->redirect('/index.php', ($app->getContainer()->get('settings')['basePath']??'').'/', 301);
$app->get('/', function (Request $request, Response $response, $args) {
    $theme = ($this->get('settings')['theme']??'');
    if (!empty($theme)) {
        require __DIR__.'/theme/'.$theme.'/index.php';
    }
    return $response;
}); 
$app->get('/index.php/admin', function (Request $request, Response $response, $args) {
    $theme = ($this->get('settings')['theme']??'');
    if (!empty($theme)) {
        require __DIR__.'/theme/'.$theme.'/index.php';
    }
    return $response;
});

$app->run();
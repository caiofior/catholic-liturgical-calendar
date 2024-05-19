<?php
use DI\ContainerBuilder;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\HelperSetManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\ConnectionFromManagerProvider;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Add DI container definitions
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');

// Create Slim App instance
AppFactory::setContainer($containerBuilder->build());
$app = AppFactory::create();

$entityManager = $app->getContainer()->get('entity_manager');
$singleManagerProvider = new SingleManagerProvider($entityManager);
$commands=[];
$cli = ConsoleRunner::createApplication($singleManagerProvider,
    $commands);
$cli->add(new Caiofior\CatholicLiturgical\command\Sitemap($app->getContainer()));
$cli->run();
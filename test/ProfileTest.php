<?php
declare(strict_types=1);

use Caiofior\Core\ProfileValidation;
use PHPUnit\Framework\TestCase;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-10-21 at 11:54:01.
 */
class ProfileTest extends TestCase
{
    
    public function testProfile() {

        $containerBuilder = new ContainerBuilder();
        // Add DI container definitions
        $containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
        $container = $containerBuilder->build(); 
        $entityManager = $container->get('entity_manager');
        $profile = $entityManager->find('\Caiofior\Core\model\Profile',1 );
        
        $profileValidation = new ProfileValidation($container);

        $url = $profileValidation->generateValidationUrl($profile);
        $this->assertTrue (is_string($url));

        $profileValidation->sentValidationMail($profile);


        parse_str(parse_url($url,PHP_URL_QUERY),$parameters);

        $token = $parameters['validation'];

        $profile = $profileValidation->validateToken($token);

        $this->assertTrue (is_object($profile));
    }

}
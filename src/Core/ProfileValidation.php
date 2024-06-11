<?php
declare(strict_types=1);
namespace Caiofior\Core;
use DI\Container;
use Caiofior\Core\model\Profile;
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Creates and rertives data of a chatolic lithurgic calendar
 * @author Claudio Fior caiofior@gmail.com
 */
class ProfileValidation {
    /**
     * @var Container
     */
    private $container = null;
    private $method = null;
    private $encryption_key = null;
    private $iv = null;

    public function __construct(Container $container) {
        $this->container = $container;        
        $this->initializeSecrets();
    }
    /**
     * Initializes secrets
     */
    private function initializeSecrets() {
        $this->method = $this->container->get('settings')['encryption']['method']??'';
        if (!empty($this->container->get('settings')['encryption']['encryption_key'])) {
            $this->encryption_key = base64_decode($this->container->get('settings')['encryption']['encryption_key']);
        } else {
            $key_size = openssl_cipher_key_length($this->method);
            $password = $this->container->get('settings')['encryption']['password']??'';
            $this->encryption_key = openssl_random_pseudo_bytes($key_size, $password);
            throw new \Exception('Encription key '.base64_encode($this->encryption_key),202406101630);
        }
        if (!empty($this->container->get('settings')['encryption']['iv'])) {
            $this->iv = base64_decode($this->container->get('settings')['encryption']['iv']);
        } else {
            $iv_size = openssl_cipher_iv_length($this->method);
            $salt = $this->container->get('settings')['encryption']['salt']??'';
            $this->iv = openssl_random_pseudo_bytes($iv_size, $salt);
            throw new \Exception('IV '.base64_encode($this->iv),202406101630);
        }
    }

    public function sentValidationMail (Profile $profile) {
        $baseDir = $this->container->get('settings')['baseDir']??'';
        $entityManager = $this->container->get('entity_manager');
        $profile->active(false);
        $entityManager->persist($profile);
        $entityManager->flush();

        $url = $this->generateValidationUrl($profile);

        $bodyTemplateContent =  file_get_contents($baseDir.'/mail/validate_profile.html');

        $bodyContent = sprintf(
            $bodyTemplateContent,
            $this->container->get('settings')['siteName']??'',
            $url
        );

        $mail = new PHPMailer(true);

        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = $this->container->get('settings')['mail']['host']??'';
        $mail->SMTPAuth   = $this->container->get('settings')['mail']['auth']??'';
        $mail->Username   = $this->container->get('settings')['mail']['username']??'';
        $mail->Password   = $this->container->get('settings')['mail']['password']??'';
        $mail->SMTPSecure = $this->container->get('settings')['mail']['secure']??'';
        $mail->Port       = $this->container->get('settings')['mail']['port']??'';

        

        //Recipients
        $mail->setFrom(
            $this->container->get('settings')['mail']['fromMail']??'',
            $this->container->get('settings')['mail']['fromName']??''
        );
        $mail->addAddress(
            $profile->getData()['email'],
            trim(($profile->getData()['first_name']??'').' '.($profile->getData()['last_name']??''))
        );


        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Iscrizione al sito '.$this->container->get('settings')['siteName']??'';
        $mail->Body    = $bodyContent;

        $mail->send();

        
    }

    /**
     * Generates a profile vaòidation url with token
     */
    public function generateValidationUrl(Profile $profile) {
        $url = $this->container->get('settings')['siteUrl']??'';
        $url .= '/index.php/login?validation=';
        $profileData = [
            'email'=>$profile->getData()['email'],
            'created'=>date('Y-m-d H:i:s')
        ];
        
        $url .= urlencode(base64_encode(openssl_encrypt(
            json_encode($profileData),
            $this->method,
            $this->encryption_key,
            0,
            $this->iv
        )));
        return $url;
    }
    /**
     * Validates the token
     */
    public function validateToken(string $token) {
        $profileData = openssl_decrypt(
            base64_decode($token),
            $this->method,
            $this->encryption_key,
            0,
            $this->iv
        );
        if (!is_string($profileData)) {
            throw new \Exception('Token corrupted',202406101529);
        }
        $profileData = json_decode($profileData);
        if (!is_object($profileData)) {
            throw new \Exception('Token corrupted',202406101529);
        }
        $date = new DateTime($profileData->created??null);
        if (!is_object($date)) {
            throw new \Exception('Token corrupted',202406101529);
        }
        $now = new DateTime();
        $interval = $now->diff($date);
        if ($interval->format('%R%a')< -2) {
            throw new \Exception('Token expired',202406101530);
        }
        $entityManager = $this->container->get('entity_manager');

        $queryBuilder = $entityManager
                    ->getConnection()
                    ->createQueryBuilder();

        $query = $queryBuilder
                    ->select('id')
                    ->from('profile')
                    ->where(
                        $queryBuilder->expr()->eq('email', ':email')
                )
                    ->setParameter('email', $profileData->email);
        $profileIds = $query
                    ->fetchAllAssociative();

        if (sizeof($profileIds) != 1) {
            throw new \Exception('Profile not found',202406101531);
        }
        $profile = $entityManager->find('\Caiofior\Core\model\Profile',$profileIds[0]['id'] );
        $profile->active(true);
        $entityManager->persist($profile);
        $entityManager->flush();
        return $profile;
    }
}
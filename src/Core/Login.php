<?php
declare(strict_types=1);
namespace Caiofior\Core;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of User
 *
 * @author caiofior
 */
#[Entity, Table(name: 'login')]
final class Login {
    #[Id, Column(type: 'string', unique: true, nullable: false)]
    private string $username;

    #[Column(type: 'string', nullable: false)]
    private string $password;

    #[Column(type: 'integer', nullable: false)]
    private int $profile_id;
    
    #[Column(type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $creation_datetime;
    
    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $change_datetime;
    
    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $confirm_datetime;
    
    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $last_login;
    
    #[Column(type: 'string', nullable: true)]
    private string $confirm_code;
    /**
     * Set username
     * @param string $username
     */
    public function setUsername($username) {
        if (empty($username)) {
            throw new \Exception('Username required',1);
        }
        $this->username = $username;
    }
    /**
     * Gets username
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }
    /**
     * Set password
     * @param string $password
     */
    public function setPassword($password) {
        if (empty($password)) {
            throw new \Exception('Password required',2);
        }
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }
    /**
     * Set profile_id
     * @param int $profile_id
     */
    public function setProfileId($profile_id) {
        $this->profile_id = (int)$profile_id;
    }
    /**
     * Set creation datetime
     * @param DateTimeImmutable $creation_datetime
     */
    public function setCreationDatetime($creation_datetime) {
        $this->creation_datetime = $creation_datetime;
    }
    /**
     * Set last login
     * @param DateTimeImmutable $last_login
     */
    public function setLastLogin($last_login) {
        $this->last_login = $last_login;
    }
    /**
     * Check the password
     * @param string $password
     * @throws \Exception
     */
    public function checkPassword($password) {
        if (!password_verify($password, $this->password)) {
            throw new \Exception('Password not valid',3);
        }
    }
}

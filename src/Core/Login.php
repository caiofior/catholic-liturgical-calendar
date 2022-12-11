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
    private string $profile_id;
    
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
}

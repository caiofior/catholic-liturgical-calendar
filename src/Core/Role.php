<?php
declare(strict_types=1);
namespace Caiofior\Core;

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
#[Entity, Table(name: 'role')]
final class Role {
    #[Id, Column(type: 'integer', unique: true, nullable: false), GeneratedValue]
    private string $id;

    #[Column(type: 'string', nullable: false)]
    private string $description;
    /**
     * Returns description
     * @return string
     */
    public function getDescription() : string {
        return $this->description;
    }

}

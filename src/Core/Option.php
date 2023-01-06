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
#[Entity, Table(name: 'option')]
final class Option {
    #[Id, Column(type: 'string', unique: true, nullable: false)]
    private string $option;

    #[Column(type: 'text', nullable: false)]
    private string $value;

    /**
     * Sets an option
     * @param string $value
     * @throws \Exception
     */
    public function setValue(string $value) {
        $this->value = $value;
    }
    /**
     * Get value
     * @return string
     */
    public function getValue() :string {
        return $this->value;
    }
}

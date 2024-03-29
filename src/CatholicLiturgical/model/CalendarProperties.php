<?php

declare(strict_types=1);

namespace Caiofior\CatholicLiturgical\model;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Calendar Properties
 *
 * @author caiofior
 */
#[Entity, Table(name: 'calendar_properties'), Index(name: "name", columns: ["name"])]
final class CalendarProperties implements \JsonSerializable {

    #[Id, Column(type: 'integer', unique: true, nullable: false), GeneratedValue]
    private int $id;

    #[Column(type: 'integer', nullable: false)]
    private int $profile_id;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 0])]
    private int $public = 0;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 0])]
    private int $approved = 0;

    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $creation;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 0])]
    private int $lithurgicYear = 0;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 0])]
    private int $lithurgicEve = 0;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 0])]
    private int $salther = 0;

    #[Column(type: 'text', nullable: true)]
    private string $name = '';

    #[Column(type: 'string', nullable: true)]
    private string $description = '';

    /**
     * Get id
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }
    /**
     * Returns profile id
     * @return int
     */
    public function getProfileId() : int {
        return $this->profile_id;
    }

    /**
     * Set data
     * @param array $data
     */
    public function setData(array $data) {
        $obj = new \ReflectionObject($this);
        foreach ($obj->getProperties() as $property) {
            $field = $property->name;
            $attr = [];
            foreach ($property->getAttributes() as $attibute) {
                if ($attibute->getName() == 'Doctrine\ORM\Mapping\Column') {
                    $attr = $attibute->getArguments();
                    break;
                }
            }

            switch ($attr['type']) {
                case 'smallint':
                    $this->$field = 0;
                    if (!empty($data[$field])) {
                        $this->$field = 1;
                    }
                    break;
                case 'integer':
                    if (isset($data[$field])) {
                        $this->$field = (int) $data[$field];
                    }
                    break;
                default;
                    if (isset($data[$field])) {
                        $this->$field = $data[$field];
                    }
            }
        }
    }

    /**
     * Get data
     * @return array
     */
    public function getData() {
        $data = array();
        $obj = new \ReflectionObject($this);
        foreach ($obj->getProperties() as $property) {
            $field = $property->name;

            foreach ($property->getAttributes() as $attibute) {
                if ($attibute->getName() == 'Doctrine\ORM\Mapping\Column') {
                    $field = $property->getName();
                    if (isset($this->$field)) {
                        $data[$field] = $this->$field;
                    }
                    break;
                }
            }
        }
        return $data;
    }

    public function jsonSerialize(): mixed {
        return $this->getData();
    }

}

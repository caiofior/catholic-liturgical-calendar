<?php

declare(strict_types=1);

namespace Caiofior\Core\model;

use Doctrine\DBAL\Types\Type;
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
#[Entity, Table(name: 'content')]
final class Content implements \JsonSerializable {

    #[Id, Column(type: 'integer', unique: true, nullable: false), GeneratedValue]
    private int $id;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 0])]
    private int $approved = 0;
    
    #[Column(type: 'smallint', nullable: false, options: ["default" => 0])]
    private int $deletable = 0;

    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $creation;
    
    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $modify;
    
    #[Column(type: 'integer', nullable: true)]
    private $sort ;
    
    #[Column(type: 'string', nullable: true)]
    private $code ;

    #[Column(type: 'string', nullable: true)]
    private $title ;
       
    #[Column(type: 'text', nullable: true)]
    private $content;  

    /**
     * Get id
     * @return int
     */
    public function getId() : int {
        return $this->id;
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
                    if($field=='today') {
                        $this->$field=null;
                    }
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

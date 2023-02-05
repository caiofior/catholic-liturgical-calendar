<?php

declare(strict_types=1);

namespace Caiofior\CatholicLiturgical;

use Doctrine\DBAL\Types\Type;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

Type::addType('point', 'CrEOF\Spatial\DBAL\Types\Geometry\PointType');
/**
 * Description of Calendar Properties
 *
 * @author caiofior
 */
#[Entity, Table(name: 'prey')]
final class Prey implements \JsonSerializable {

    #[Id, Column(type: 'integer', unique: true, nullable: false), GeneratedValue]
    private int $id;

    #[Column(type: 'integer', nullable: false)]
    private int $calendar_id;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 0])]
    private int $approved = 0;

    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $creation;

    #[Column(type: 'string', nullable: true)]
    private $lithurgic_year;

    #[Column(type: 'string', nullable: true)]
    private $lithurgic_eve ;

    #[Column(type: 'integer', nullable: true)]
    private $salter_week=0;
    
    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private $today;
    
    #[Column(type: 'integer', nullable: true)]
    private $day_of_year ;
    
    #[Column(type: 'integer', nullable: true)]
    private $day_of_week ;
    
    #[Column(type: 'integer', nullable: true)]
    private $hour ;
    
    #[Column(type: 'integer', nullable: true)]
    private $sort ;

    #[Column(type: 'string', nullable: true)]
    private $title ;
    
    #[Column(type: 'string', nullable: true)]
    private $reference ;
    
    #[Column(type: 'text', nullable: true)]
    private $name;
    
    #[Column(type: 'point', nullable: true)]
    private $place;
    
    public function __construct() {
        $this->today = new DateTimeImmutable();
    }
    

    /**
     * Get id
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }
    /**
     * Returns calendar id
     * @return int
     */
    public function getCalendarId() : int {
        return $this->calendar_id;
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

<?php
declare(strict_types=1);
namespace Caiofior\CatholicLiturgical;

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
#[Entity, Table(name: 'calendar_properties')]
final class CalendarProperties {
    #[Id, Column(type: 'integer', unique: true, nullable: false), GeneratedValue]
    private int $id;
    
    #[Column(type: 'integer', nullable: false)]
    private int $profile_id;
    
    #[Column(type: 'smallint', nullable: false, options: ["default" => 1])]
    private int $active=0;
    
    #[Column(type: 'smallint', nullable: false, options: ["default" => 1])]
    private int $approved=0;
    
    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $creation;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 1])]
    private int $lithurgicYear=0;
    
    #[Column(type: 'smallint', nullable: false, options: ["default" => 1])]
    private int $lithurgicEve=0;
    
    #[Column(type: 'smallint', nullable: false, options: ["default" => 1])]
    private int $salther=0;
    
    #[Column(type: 'string', nullable: true)]
    private string $name='';
    
    #[Column(type: 'string', nullable: true)]
    private string $description='';
    
     /**
     * Get id
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set data
     * @param array $data
     */
    public function setData(array $data) {
        $obj = new \ReflectionObject($this); 
        foreach ((array)$this as $field => $value) {
            $field = substr(str_replace($obj->getName(), '', $field),2);
            if (isset($data[$field])) {
                $this->$field=$data[$field];
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
        foreach ((array)$this as $field => $value) {
            $field = substr(str_replace($obj->getName(), '', $field),2);
            $data[$field]=$value; 
        }
        return $data;
    }
    
}

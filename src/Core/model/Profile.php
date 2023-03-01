<?php
declare(strict_types=1);
namespace Caiofior\Core\model;

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
#[Entity, Table(name: 'profile')]
final class Profile {
    #[Id, Column(type: 'integer', unique: true, nullable: false), GeneratedValue]
    private int $id;

    #[Column(type: 'integer', nullable: false)]
    private int $role_id;

    #[Column(type: 'smallint', nullable: false, options: ["default" => 1])]
    private int $active=1;
    
    #[Column(type: 'string', nullable: true)]
    private string $first_name;
    
    #[Column(type: 'string', nullable: true)]
    private string $last_name;
    
    #[Column(type: 'string', nullable: true)]
    private string $address;
    
    #[Column(type: 'string', nullable: true)]
    private string $city;
    
    #[Column(type: 'string', nullable: true)]
    private string $province;
    
    #[Column(type: 'string', nullable: true)]
    private string $state;
    
    #[Column(type: 'string', nullable: true)]
    private string $phone;
    
    #[Column(type: 'string', nullable: true)]
    private string $email;
    
    #[Column(type: 'datetimetz_immutable', nullable: true)]
    private DateTimeImmutable $expire;
    
    #[Column(type: 'string', nullable: true)]
    private string $token;
     /**
     * Get id
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Return role id
     * @return int
     */
    function getRoleId () : int {
        return $this->role_id;
    }
     /**
     * Set role id
     * @param int $role_id
     */
    public function setRoleId($role_id) {
        $this->role_id = $role_id;
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

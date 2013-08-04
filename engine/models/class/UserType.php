<?php
/** UserType File
 * @package models @subpackage core */
include_once Router::rel('models').'core/Object.php';
/**
 * UserType Class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage core
 */
class UserType extends Object{
    /** Identificator
     * @var int
     */
    protected $id;
    /** name 
     * @var string
     */
    protected $name;
    /**
     * Constructor
     * @param int identificator (optional)
     * @param string name (optional)
     */
    function UserType($id=0,$name=""){
        $this->id=$id;
        $this->name=$name;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Setter: id
     * @param int $id
     * @return void
     */
    public function setId($id) {
        $this->id = $id;
    }
    /**
     * Setter: name
     * @param String $name
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   GETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Getter: id
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Getter: name
     * @return String
     */
    public function getName() {
        return $this->name;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
} 
?>

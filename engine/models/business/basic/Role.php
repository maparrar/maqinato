<?php
/** Role File
* @package  @subpackage  */
/**
* Role Class
*
* @author https://github.com/maparrar/maqinato
* @author maparrar <maparrar@gmail.com>
* @package 
* @subpackage 
*/
class Role extends Object{
    /** 
     *  
     * 
     * @var int
     */
    protected $id;
    /** 
     *  
     * 
     * @var string
     */
    protected $name;
    /**
    * Constructor
    * @param int $id         
    * @param string $name         
    */
    function __construct($id=0,$name=""){        
        $this->id=$id;
        $this->name=$name;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
    * Setter id
    * @param int $value 
    * @return void
    */
    public function setId($value) {
        $this->id=$value;
    }
    /**
    * Setter name
    * @param string $value 
    * @return void
    */
    public function setName($value) {
        $this->name=$value;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
    * Getter: id
    * @return int
    */
    public function getId() {
        return $this->id;
    }
    /**
    * Getter: name
    * @return string
    */
    public function getName() {
        return $this->name;
    }    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}
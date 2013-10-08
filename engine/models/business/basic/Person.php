<?php
/** Person File
* @package  @subpackage  */
/**
* Person Class
*
* @author https://github.com/maparrar/maqinato
* @author maparrar <maparrar@gmail.com>
* @package 
* @subpackage 
*/
class Person extends Object{
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
     *  
     * 
     * @var string
     */
    protected $lastname;
    /**
    * Constructor
    * @param int $id         
    * @param string $name         
    * @param string $lastname         
    */
    function __construct($id=0,$name="",$lastname=""){        
        $this->id=$id;
        $this->name=$name;
        $this->lastname=$lastname;
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
    /**
    * Setter lastname
    * @param string $value 
    * @return void
    */
    public function setLastname($value) {
        $this->lastname=$value;
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
    /**
    * Getter: lastname
    * @return string
    */
    public function getLastname() {
        return $this->lastname;
    }    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}
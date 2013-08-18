<?php
/** User File
* @package  @subpackage  */
/**
* User Class
*
* @author https://github.com/maparrar/maqinato
* @author maparrar <maparrar@gmail.com>
* @package 
* @subpackage 
*/
class User extends Person{
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
    protected $password;
    /** 
     *  
     * 
     * @var string
     */
    protected $salt;
    /**
    * Constructor
    * @param int         
    * @param string         
    * @param string         
    */
    function __construct($id=0,$password="",$salt=""){        
        $this->id=$id;
        $this->password=$password;
        $this->salt=$salt;
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
    * Setter password
    * @param string $value 
    * @return void
    */
    public function setPassword($value) {
        $this->password=$value;
    }
    /**
    * Setter salt
    * @param string $value 
    * @return void
    */
    public function setSalt($value) {
        $this->salt=$value;
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
    * Getter: password
    * @return string
    */
    public function getPassword() {
        return $this->password;
    }
    /**
    * Getter: salt
    * @return string
    */
    public function getSalt() {
        return $this->salt;
    }    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}
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
    function __construct($password="",$salt=""){
        $this->password=$password;
        $this->salt=$salt;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
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
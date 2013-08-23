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
    protected $username;
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
    function __construct($username="",$password="",$salt=""){
        $this->username=$username;
        $this->password=$password;
        $this->salt=$salt;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
    * Setter username
    * @param string $value 
    * @return void
    */
    public function setUsername($value) {
        $this->username=$value;
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
    * Getter: username
    * @return string
    */
    public function getUsername() {
        return $this->username;
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
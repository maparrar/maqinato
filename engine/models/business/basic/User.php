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
    protected $email;
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
     *  
     * 
     * @var Role
     */
    protected $role;
    /**
    * Constructor
    * @param int $id         
    * @param string $email         
    * @param string $password         
    * @param string $salt         
    */
    function __construct($id=0,$email="",$password="",$salt=""){  
        parent::__construct();
        $this->id=$id;
        $this->email=$email;
        $this->password=$password;
        $this->salt=$salt;
        $this->role=new Role();
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
    * Setter email
    * @param string $value 
    * @return void
    */
    public function setEmail($value) {
        $this->email=$value;
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
    /**
    * Setter role
    * @param Role $value 
    * @return void
    */
    public function setRole($value) {
        $this->role=$value;
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
    * Getter: email
    * @return string
    */
    public function getEmail() {
        return $this->email;
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
    /**
    * Getter: role
    * @return Role
    */
    public function getRole() {
        return $this->role;
    }  
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Return the name and lastname in a string
     * @return string Name and lastname
     */
    public function name(){
        return $this->name." ".$this->lastname;
    }
}
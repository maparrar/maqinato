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
    protected $phone;
    /**
    * Constructor
    * @param int $id         
    * @param string $name         
    * @param string $lastname         
    * @param string $email         
    * @param string $phone         
    */
    function __construct($id=0,$name="",$lastname="",$email="",$phone=""){        
        $this->id=$id;
        $this->name=$name;
        $this->lastname=$lastname;
        $this->email=$email;
        $this->phone=$phone;
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
    /**
    * Setter email
    * @param string $value 
    * @return void
    */
    public function setEmail($value) {
        $this->email=$value;
    }
    /**
    * Setter phone
    * @param string $value 
    * @return void
    */
    public function setPhone($value) {
        $this->phone=$value;
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
    /**
    * Getter: email
    * @return string
    */
    public function getEmail() {
        return $this->email;
    }
    /**
    * Getter: phone
    * @return string
    */
    public function getPhone() {
        return $this->phone;
    }    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}
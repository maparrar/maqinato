<?php
/** User File
 * @package models @subpackage core */
include_once Router::rel('models').'core/Object.php';
/**
 * User Class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage core
 */
class User extends Object{
    /** User id 
     * @var int
     */
    protected $id;
    /** User email 
     * @var string
     */
    protected $email;
    /** User name 
     * @var string
     */
    protected $name;
    /** User lastname 
     * @var string
     */
    protected $lastname;
    /** Born date
     * @var date
     */
    protected $born;
    /** User sex 
     * @var string
     */
    protected $sex;
    /** I am
     * @var string
     */
    protected $iam;
    /** If the user is validated
     * @var bool
     */
    protected $validated;
    /** User city
     * @var int
     */
    protected $city;
    /** User type (superuser,team,standard,nonprofit)
     * @var UserType Objeto de tipo UserType
     */
    protected $type;
    /** User country
     * @var string
     */
    protected $country;
    /** maqinato session id 
     * @var string
     */
    private $sessionId;
    /** True if is friend of another relative User. i.e. If this user is friend 
     * of the logged user. False if not are friends. NOTE: Is only relative to 
     * another User, and depends of the context. Returned:
     *      - true if the user THIS is friend of B
     *      - false if the user THIS is not friend of B
     *      - "sent" if THIS sent an invitation to B
     *      - "received" if THIS received an invitation from B
     * @var mixed
     */
    protected $isFriend;
    /** Image of the user
     * @var string i.e.: 123.png or default.png
     */
    protected $image;
    /**
     * Constructor
     * @param int identifier of the user
     * @param string email user
     * @param string user name
     * @param string user lastname
     */
    function User($id=0,$email="",$name="",$lastname=""){
        $this->id=$id;
        $this->email=$email;
        $this->name=$name;
        $this->lastname=$lastname;
        $this->born=date('1900-01-01 H:i:s');
        $this->sex="";
        $this->iam="";
        $this->validated=true;
        $this->city=0;
        $this->type=null;
        $this->country='';
        $this->isFriend=false;
        $this->image="";
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
     * Setter: email
     * @param String $email
     * @return void
     */
    public function setEmail($email) {
        $this->email = $email;
    }
    /**
     * Setter: name
     * @param String $name
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }
     /**
     * Setter: lastname
     * @param String $lastname
     * @return void
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }
    /**
     * Setter: born
     * @param date $born
     * @return void
     */
    public function setBorn($born) {
        $this->born = $born;
    }
    /**
     * Setter: lastname
     * @param String $lastname
     * @return void
     */
    public function setSex($value) {
        $this->sex = $value;
    }
    /**
     * Setter: iam
     * @param string $iam
     * @return void
     */
    public function setIam($iam) {
        $this->iam = $iam;
    }
    /**
     * Setter: validated
     * @param bool $value
     * @return void
     */
    public function setValidated($value) {
        $this->validated = $value;
    }
    /**
     * Setter: city
     * @param int $value
     * @return void
     */
    public function setCity($value) {
        $this->city = $value;
    }
    /**
     * Setter: type
     * @param UserType $value
     * @return void
     */
    public function setType($value) {
        $this->type = $value;
    }
    /**
     * Setter: country
     * @param string $value
     * @return void
     */
    public function setCountry($value) {
        $this->country = $value;
    }
    /**
     * Setter: sessionId
     * @param int $sessionId
     * @return void
     */
    public function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }
    /**
     * Setter: isFriend
     * @param mixed $isFriend
     * @return void
     */
    public function setIsFriend($isFriend) {
        $this->isFriend = $isFriend;
    }
    /**
     * Setter: image
     * @param string $image
     * @return void
     */
    public function setImage($image) {
        $this->image = $image;
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
     * Getter: email
     * @return String
     */
    public function getEmail() {
        return $this->email;
    }
    /**
     * Getter: name
     * @return String
     */
    public function getName() {
        return $this->name;
    }
    /**
     * Getter: lastname
     * @return String
     */
    public function getLastname() {
        return $this->lastname;
    }
    /**
     * Getter: born
     * @return date
     */
    public function getBorn() {
        return $this->born;
    }
    /**
     * Getter: sex
     * @return String
     */
    public function getSex() {
        return $this->sex;
    }
    /**
     * Getter: iam
     * @return string
     */
    public function getIam() {
        return $this->iam;
    }
    /**
     * Getter: validated
     * @return bool
     */
    public function getValidated() {
        return $this->validated;
    }
    /**
     * Getter: city
     * @return int
     */
    public function getCity() {
        return $this->city;
    }
    /**
     * Getter: type
     * @return UserType
     */
    public function getType() {
        return $this->type;
    }
    /**
     * Getter: country
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }
    /**
     * Getter: sessionId
     * @return String
     */
    public function getSessionId() {
        return $this->sessionId;
    }
    /**
     * Getter: isFriend
     * @return mixed
     */
    public function getIsFriend() {
        return $this->isFriend;
    }
    /**
     * Getter: image
     * @return string
     */
    public function getImage() {
        return $this->image;
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
?>

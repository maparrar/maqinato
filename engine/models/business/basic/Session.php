<?php
/** Session File
* @package  @subpackage  */
/**
* Session Class
*
* @author https://github.com/maparrar/maqinato
* @author maparrar <maparrar@gmail.com>
* @package 
* @subpackage 
*/
class Session extends Object{
    /** 
     *  
     * 
     * @var int
     */
    protected $id;
    /** 
     *  
     * 
     * @var date
     */
    protected $ini;
    /** 
     *  
     * 
     * @var date
     */
    protected $end;
    /** 
     *  
     * 
     * @var tinyint
     */
    protected $state;
    /** 
     *  
     * 
     * @var string
     */
    protected $ipIni;
    /** 
     *  
     * 
     * @var string
     */
    protected $ipEnd;
    /** 
     *  
     * 
     * @var string
     */
    protected $phpSession;
    /** 
     *  
     * 
     * @var int
     */
    protected $user;
    /**
    * Constructor
    * @param int $id         
    * @param date $ini         
    * @param date $end         
    * @param tinyint $state         
    * @param string $ipIni         
    * @param string $ipEnd         
    * @param string $phpSession         
    * @param int $user         
    */
    function __construct($id=0,$ini="",$end="",$state=0,$ipIni="",$ipEnd="",$phpSession="",$user=0){        
        $this->id=$id;
        $this->ini=$ini;
        $this->end=$end;
        $this->state=$state;
        $this->ipIni=$ipIni;
        $this->ipEnd=$ipEnd;
        $this->phpSession=$phpSession;
        $this->user=$user;
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
    * Setter ini
    * @param date $value 
    * @return void
    */
    public function setIni($value) {
        $this->ini=$value;
    }
    /**
    * Setter end
    * @param date $value 
    * @return void
    */
    public function setEnd($value) {
        $this->end=$value;
    }
    /**
    * Setter state
    * @param tinyint $value 
    * @return void
    */
    public function setState($value) {
        $this->state=$value;
    }
    /**
    * Setter ipIni
    * @param string $value 
    * @return void
    */
    public function setIpIni($value) {
        $this->ipIni=$value;
    }
    /**
    * Setter ipEnd
    * @param string $value 
    * @return void
    */
    public function setIpEnd($value) {
        $this->ipEnd=$value;
    }
    /**
    * Setter phpSession
    * @param string $value 
    * @return void
    */
    public function setPhpSession($value) {
        $this->phpSession=$value;
    }
    /**
    * Setter user
    * @param int $value 
    * @return void
    */
    public function setUser($value) {
        $this->user=$value;
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
    * Getter: ini
    * @return date
    */
    public function getIni() {
        return $this->ini;
    }
    /**
    * Getter: end
    * @return date
    */
    public function getEnd() {
        return $this->end;
    }
    /**
    * Getter: state
    * @return tinyint
    */
    public function getState() {
        return $this->state;
    }
    /**
    * Getter: ipIni
    * @return string
    */
    public function getIpIni() {
        return $this->ipIni;
    }
    /**
    * Getter: ipEnd
    * @return string
    */
    public function getIpEnd() {
        return $this->ipEnd;
    }
    /**
    * Getter: phpSession
    * @return string
    */
    public function getPhpSession() {
        return $this->phpSession;
    }
    /**
    * Getter: user
    * @return int
    */
    public function getUser() {
        return $this->user;
    }    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}
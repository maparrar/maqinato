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
    * @param int $user         
    */
    function __construct($user=0){        
        $this->id=0;
        $this->state=false;
        $this->phpSession=session_id();
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
    /**
     * Start the session
     * @param string IP where the session starts
     */
    function start($ip){
        $this->ini=date('Y-m-d H:i:s');
        $this->ipIni=$ip;
        $this->state=true;
    }
    /**
     * Stop the session
     * @param string IP where the session stops
     */
    function stop($ip){
        $this->end=date('Y-m-d H:i:s');
        $this->ipEnd=$ip;
        $this->state=false;
    }
}
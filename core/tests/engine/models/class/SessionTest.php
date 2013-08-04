<?php
/** Session File
 * @package models @subpackage core */
/**
 * Session Class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage core
 * @todo store the lat and lon
 */
class Session{
    /** Session id 
     * @var int
     */
    private $id;
    /** Start date and time  
     * @var Date
     */
    private $ini;
    /** End date and time 
     * @var Date
     */
    private $end;
    /** true if the session is open, false otherwise 
     * @var bool
     */
    private $state;
    /** IP where the session starts 
     * @var string
     */
    private $ipIni;
    /** IP where the session ends 
     * @var string
     */
    private $ipEnd;
    /** Session ID from the $_SESSION PHP variable 
     * @var string
     */
    private $phpSession;
    /** Id user 
     * @var int
     */
    private $user;
    /**
     * Constructor
     * @param User user that use the session
     */
    function Session($user=0){
        $this->id=0;
        $this->state=false;
        $this->phpSession=session_id();
        $this->user=$user;
    }
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
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Set id of the Object
     * @param int id of the Object
     */
    function setId($value){$this->id=$value;}
    /**
     * Set ini of the Object
     * @param Date init of the Object
     */
    function setIni($value){$this->ini=$value;}
    /**
     * Set end of the Object
     * @param Date end of the Object
     */
    function setEnd($value){$this->end=$value;}
    /**
     * Set state of the Object
     * @param bool state of the Object
     */
    function setState($value){$this->state=$value;}
    /**
     * Set the initial IP
     * @param string IP init of the Object
     */
    function setIpIni($value){$this->ipIni=$value;}
    /**
     * Set the end IP
     * @param string IP end of the Object
     */
    function setIpEnd($value){$this->ipEnd=$value;}
    /**
     * Set the phpSession string
     * @param string PHP Session
     */
    function setPhpSession($value){$this->phpSession=$value;}
    /**
     * Set the User of the session
     * @param int User Id
     */
    function setUser($value){$this->user=$value;}
    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   GETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Get id of the Object
     * @return int name of the Object
     */
    function getId(){return $this->id;}
    /**
     * Get initial date of the Object
     * @return Date date initial of the Object
     */
    function getIni(){return $this->ini;}
    /**
     * Get end date of the Object
     * @return Date date end of the Object
     */
    function getEnd(){return $this->end;}
    /**
     * Get state of the Object
     * @return bool state of the Object
     */
    function getState(){return $this->state;}
    /**
     * Get start IP
     * @return string with the IP from the user
     */
    function getIpIni(){return $this->ipIni;}
    /**
     * Get end IP
     * @return string with the IP from the user
     */
    function getIpEnd(){return $this->ipEnd;}
    /**
     * Get PhpSession from the browser
     * @return string PhpSession
     */
    function getPhpSession(){return $this->phpSession;}
    /**
     * Get User id in the session
     * @return int User id
     */
    function getUser(){return $this->user;}
} 
?>

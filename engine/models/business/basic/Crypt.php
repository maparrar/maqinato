<?php
/** Crypt File
 * @package models @subpackage security */
/**
 * Crypt Class
 *
 * Class that can encode and decode the information system access.
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage security
 */
class Crypt {
    /** Delay to protect the process by hardware requirement 
     * @var int
     */
    private $loops;
    /** Number of rounds to use SHA 
     * @var int
     */
    private $rounds;
    /** Output salt for start the crypt 
     * @var string
     */
    private $salt;
    /** Salt prefix 
     * @var string
     */
    private $saltIni;
    /** Salt suffix 
     * @var string
     */
    private $saltEnd;
    /** Salt total length 
     * @var int
     */
    private $saltLength;
    /** Output encrypt password 
     * @var string
     */
    private $password;
    /**
     * Constructor: Define the starting variables
     */
    function Crypt(){
        $this->loops=100000;
        $this->rounds=5000;
        $this->saltLength=18;
        $this->saltIni = '$6$rounds='.$this->rounds.'$';
        $this->saltEnd = '$';
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   GETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Getter: salt
     * @return String
     */
    public function getSalt() {
        return $this->salt;
    }
    /**
     * Getter: password
     * @return String
     */
    public function getPassword() {
        return $this->password;
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Encoding password and set a salt and codified password output.
     * @param string password to encode
     * @return string $this->salt with a random salt
     * @return string $this->password encoded password with the random salt
     */
    function encrypt($password){
        $this->salt=$this->randomSalt();
        $salt=$this->loopSalt($this->salt);
        $encrypt=crypt($password,$this->saltIni.$salt.$this->saltEnd);
        $this->password = substr($encrypt,strlen($this->saltIni)+$this->saltLength);
    }
    /**
     * Checks if a password encrypted with a given salt match with a hash stored
     * in the database.
     * @param string $password last password by the user
     * @param string $salt salt stored in the database
     * @param string $hash hash of the encrypted password and stored in the database
     * @return true if the password match with the hash
     * @return false if not
     */
    function validate($password,$salt,$hash){
        $salt=$this->loopSalt($salt);
        $decrypt=crypt($password,$this->saltIni.$salt.$this->saltEnd);
        $decrypt=substr($decrypt,strlen($this->saltIni)+$this->saltLength);
        if($decrypt===$hash){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Generates a random salt
     * @return string with a random salt
     */
    private function randomSalt(){
        $allowed ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
        $chars = 63;
        $salt = "";
        for($i=0; $i<$this->saltLength; $i++){
            $salt .= $allowed[mt_rand(0,$chars)];
        }
        return $salt;
    }
    /**
     * Repeat the process of encoding a lot of times to avoid some brute force, 
     * demanding hardware resources.
     * @param string input salt
     * @return string with a the salt encoded a lot of times
     */
    private function loopSalt($salt){
        $signs=array("$");
        for($i=0;$i<$this->loops;$i++){
            $salt=str_replace($signs,"",$salt);
            $salt=sha1($this->saltIni.$salt.$this->saltEnd);
            $salt=substr($salt,strlen($this->saltIni),$this->saltLength);
        }
        return $salt;
    }
    /**
     * Retorna una cadena cifrada con sha256
     * @param string a codificar
     * @return string Codificado
     */
    public function encryptString($string){
        return hash("sha256",$string);
    }
}
?>

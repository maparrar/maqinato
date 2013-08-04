<?php
/** Access File
 * @package models @subpackage core */
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('models').'core/Crypt.php';
include_once Router::rel('models').'core/Session.php';
include_once Router::rel('models').'dal/core/DaoUser.php';
include_once Router::rel('models').'dal/core/DaoSession.php';
/**
 * Access Class
 *
 * Class that defines how users access the system via the data access layer DAL.
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage core
 */
class Access{
    /** Data Access Object for sessions 
     * @var DaoSession
     */
    private $daoSession;
    /**
     * Constructor: Set the DAO objects
     */
    function Access(){
        $this->daoSession=new DaoSession();
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   GETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   METHODS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Create a system user with the email and the password provided, encrypts 
     * the password before storing
     * @param string valid name
     * @param string valid lastname
     * @param string valid email
     * @param string valid password
     * @return string 'exist' if the email has already been registered
     * @return User user object
     */
    function signup($name,$lastname,$sex,$email,$city,$password){
        $daoUser=new DaoUser("all");
        $daoUserType=new DaoUserType();
        $crypt=new Crypt();
        $crypt->encrypt($password);
        $user=new User(0,$email,$name,$lastname);
        $user->setSex($sex);
        $user->setCity($city);
        $user->setType($daoUserType->readFromName("new"));
        $user=$daoUser->create($user,$crypt->getPassword(),$crypt->getSalt());
        return $user;
    }
    /**
     * Login a system user and creates the session maqinato
     * @param string valid email
     * @param string valid password
     * @return string 'error' if the email or password are incorrect
     * @return User user object
     * @todo Develop the geoposition
     */
    function login($email,$password,$fromSignup=false){
        $daoUser=new DaoUser("read");
        $hash=$daoUser->readHash($email);
        $salt=$daoUser->readSalt($email);
        if(!empty($hash)&&!empty($salt)){
            $crypt=new Crypt();
            if($crypt->validate($password,$salt,$hash)){
                $user=new User();
                $user=$daoUser->read($email);
                //Verifica si han pasado las 24 horas para que el usuario se pueda conectar
//                if($daoUser->checkWaitingUser($user)||$fromSignup){
                    //Save the start of the session
                    $session=new Session($user->getId());
                    $session->start($this->getIp());
                    $user->setSessionId($this->daoSession->create($session));
                    return $user;
//                }else{
//                    return 'error';
//                }
            }else{
                return 'error';
            }
        }else{
            return 'error';
        }
    }
    /**
     * Logout a system user and destroy the maqinato session
     * @param string valid user email
     */
    function logout($email){
        $daoUser=new DaoUser("read");
        $user=new User();
        $user=$daoUser->read($email);
        $session=new Session();
        $session=$this->daoSession->lastSession($user->getId());
        $session->stop($this->getIp());
        $user->setSessionId($session->getId());
        $this->daoSession->update($session);
    }
    /**
     * Return the user IP
     * @return string ip from the user
     */
    function getIp(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
/**
     * Login a system user and creates the session maqinato
     * @param string valid password
     * @param string valid password
     * @param string valid password
     * @return string 'error' password is incorrect
     * @return User user object
     * @todo Develop the geoposition
     */
    function changePasword($lastPwr,$newPwr,$email){
        $pass="";
        $saltsave="";
        $daoUser=new DaoUser("all");
        $hash=$daoUser->readHash($email);
        $salt=$daoUser->readSalt($email);
        if(!empty($hash)&&!empty($salt)){
            $crypt=new Crypt();
            if(!$lastPwr){
                $validate=true;
            }else{
                $validate=$crypt->validate($lastPwr,$salt,$hash);
            }
            if($validate){
                $crypt->encrypt($newPwr);
                $pass=$crypt->getPassword();
                $saltsave=$crypt->getSalt();
                if(!$crypt->validate($newPwr,$salt,$hash)){
                    $response=$daoUser->updatePass($email,$pass,$saltsave);
                }else{
                    $response="2";
                }
            }else{
                $response='3';
            }
        }else{
            $response='Error';
        }
        return $response;
    }
} 
?>

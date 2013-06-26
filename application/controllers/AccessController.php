<?php
/** AccessController File
 * @package controllers @subpackage core */
//namespace maqinato\application\controllers;
//use maqinato\core\Router as Router;

//if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'core/Access.php';
include_once Router::rel('models').'organization/Organization.php';
include_once Router::rel('models').'dal/core/DaoUser.php';
include_once Router::rel('models').'dal/organization/DaoOrganization.php';
include_once Router::rel('controllers').'SecurityController.php';
include_once Router::rel('controllers').'MediaController.php';
include_once Router::rel('controllers').'CommunicationController.php';
include_once Router::rel('controllers').'SocialController.php';
/**
 * AccessController Class
 *
 * This class manages user access to the system. Any data received is verified 
 * by the security manager. The data returned are also verified.
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package controllers
 * @subpackage core
 */
class AccessController {
    /** Access object 
     * @var Access
     */
    private $access;
    /** Max number of fails before time-blocking the IP 
     * @ int
     */
    private $maxFails;
    /**
     * Constructor: Define the Access object and set the max of login fails
     */
    function AccessController(){
        $this->access=new Access();
        $this->maxFails=5;
        
        
        
    }
    /** 
     * Return the active User in the Session
     * @return User active in the session
     * @return false if the session does not have an User
     */
    public static function getSessionUser(){
        $active=false;
        if (isset($_SESSION["user"])){
            $daoUser=new DaoUser();
            $userSession=new User();
            $userSession=unserialize($_SESSION['user']);
            $user=$daoUser->read($userSession->getEmail());
            $active=$user;
        }else{
            $active=false;
        }
        return $active;
    }
    /**
     * Create an user account on the system
     * @param string formated name
     * @param string formated lastname
     * @param string formated email
     * @param string formated password
     * @return string 'logged'  if mail and password are correct
     * @return string 'exist' if the user already exist
     * @return string 'error' if the email and password are bad-formated or are wrong
     */
    function signup($name,$lastname,$sex,$email,$city,$password,$isPreregister=false){
        $response='error';
        $name=SecurityController::sanitizeString($name);
        $lastname=SecurityController::sanitizeString($lastname);
        if(SecurityController::isemail($email)&&SecurityController::ispassword($password)){
            $response=$this->access->signup($name,$lastname,$sex,$email,$city,$password);
            if(is_object($response)){
                $user=$response;
                $daoSet=new DaoSet();
                //Save the points per signup
                $daoSet->saveSignupPoints($user->getId());
                $response=$this->login($email,$password,false,true);
                if(!$isPreregister){
                    //Envia el email de confirmación al usuario
                    CommunicationController::sendValidationEmail($user);
                }
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."Signup user: Username and/or password not allowed");
        }
        return SecurityController::sanitizeString($response);
    }
    /**
     * Create an Organization account on the system
     * @param string formated name
     * @param string formated responsible
     * @param string formated email
     * @param string formated password
     * @return string 'logged'  if mail and password are correct
     * @return string 'exist' if the user already exist
     * @return string 'error' if the email and password are bad-formated or are wrong
     */
    function signupNop($name,$responsible,$email,$password){
        $response='error';
        $name=SecurityController::sanitizeString($name);
        $responsible=SecurityController::sanitizeString($responsible);
        if(SecurityController::isemail($email)&&SecurityController::ispassword($password)){
            $response=$this->access->signup($name,"",$email,$password,true);
            if(is_object($response)){
                MediaController::copyImage(Router::rel('data').'users/images/default.png',Router::rel('data').'users/images/'.$response->getId().'.png');
                MediaController::copyImage(Router::rel('data').'users/thumbnails/default.png',Router::rel('data').'users/thumbnails/'.$response->getId().'.png');
                //Create the Organization and the first branch in the database
                $daoOrg=new DaoOrganization();
                $organization=new Organization(0,$name,$responsible,false,$response->getId());
                $branch=new Branch(0,"Headquarters");
                $organization->addBranch($branch);
                $daoOrg->create($organization);
                $response=$this->login($email,$password,false);
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."Signup nop: Username and/or password not allowed");
        }
        return SecurityController::sanitizeString($response);
    }
    /**
     * Function that allows entry into the system
     * @param string formated email
     * @param string formated password
     * @param bool to keep the session active.
     * @return string 'logged'  if mail and password are correct
     * @return string 'nonprofit'  if is an NPO
     * @return string 'error' if the email and password are bad-formated or are wrong
     */
    function login($email,$password,$keep,$fromSignup=false){
        $response=false;
        if(SecurityController::isemail($email)&&SecurityController::ispassword($password)){
            $user=$this->access->login($email,$password,$fromSignup);
            if(is_object($user)){
                //Established session length
                if($keep){
                    Config::$sessionLifeTime=0;
                }
                $this->startSession($user);
                //Incrementa el contador de logins
                $daoUserUpdate=new DaoUser();
                $daoUserUpdate->incrementTimesConected($user);
                //Envía el correo de bienvenida
                if($daoUserUpdate->timesConected($user)==2){
                    CommunicationController::sendAcceptanceEmail($user);
                }
                if($user->getType()->getName()==="nonprofit"){
                    $response="nonprofit";
                }else{
                    $response="logged";
                }
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Failed login");
                $response="error";
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."Login: Username and/or password not allowed");
        }
        return SecurityController::sanitizeString($response);
    }
    /**
     * Logout system, close only the specified session
     * @param string formated email (optional)
     * @param int session id from the user object (optional)
     */
    public function logout($email="",$sessionId=false){
        if(SecurityController::isemail($email)){
            if($sessionId){
                $this->access->logout($email,$sessionId);
            }else{
                $this->access->logoutAll($email);
            }
        }
        $this->destroy();
    }
    /**
     * Close all the user sessions
     * @param string formated email
     */
    function logoutAll($email){
        $this->access->logout($email);
        $this->destroy();
    }
    
    
    /**
     * Initialize the user session and start the fail counter in 0
     * @param User user object
     */
    function startSession($user){
        $_SESSION["user"]=serialize($user);
        $_SESSION["fails"]=0;
        $_SESSION["sessionLifetime"]=Config::$sessionLifeTime;
    }
    /** 
     * Check if the user session is live. If the user need validate the account
     * redirect to the page
     * @return true if the session is active
     * @return false otherwise
     */
    public static function checkSession(){
        $active=false;
        $user=new User();
        if (isset($_SESSION["user"])){
            $user=unserialize($_SESSION['user']);
            if(SecurityController::isemail($user->getEmail())){
                $active=true;
            }else{
                $active=false;
            }
        }else{
            $active=false;
        }
        return $active;
    }
    /**
     * Comprueba si la clave de validación y el correo corresponden
     * @param string $email Email del usuario que se quiere verificar
     * @param string $key Clave enviada al email
     * @return bool True si la clave corresponde con el correo
     */
    public static function verifyValidationKey($email,$key){
        $response=false;
        $daoUser=new DaoUser();
        $email=SecurityController::sanitizeString($email);
        $key=SecurityController::sanitizeString($key);
        if($daoUser->verifyValidationKey($email,$key)){
            $user=SocialController::getUser($email);
            $daoUser->updateValidationState($user,true);
            $response=true;
        }
        return $response;
    }
    /**
     * Verifica si el usuario ha validado su cuenta
     * @param User $user Objeto de tipo usuario para el que se verifica si se ha 
     * validado la cuenta
     * @return bool True si se ha validado
     */
    public static function validatedAccount($user){
        $response=false;
        if(SecurityController::isclass($user,"User")){
            $daoUser=new DaoUser();
            $response=$daoUser->isValidated($user);
        }
        return $response;
    }
    /** 
     * Plus 1 when the login fails
     */
    function addFail(){
        $_SESSION["fails"]++;
    }
    /** 
     * Destroy the PHP session and delete all the user variables
     */
    function destroy(){
        //Destroy session variables
        $_SESSION = array();
        //Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        //Destroy the session
        session_destroy();
    }
    /**
     * Update the basic user information
     * @param string name
     * @param string lastname
     * @param string born
     * @param string iam
     * @param string city
     * @return true if update successfully, false otherwise
     */
    public static function updateUser($nameInput,$lastnameInput,$bornInput,$sexInput,$iamInput,$cityInput){
        $response=false;
        if (self::checkSession()){
            $daoUser=new DaoUser();
            $name=SecurityController::sanitizeString($nameInput);
            $lastname=SecurityController::sanitizeString($lastnameInput);
            $born='1900/01/01';
            $checkborn=SecurityController::isdate($bornInput);
            if($checkborn){
                $born=$checkborn;
            }
            $iam=SecurityController::sanitizeString($iamInput);
            $city=SecurityController::sanitizeString($cityInput);
            $sex=SecurityController::sanitizeString($sexInput);
            $user=AccessController::getSessionUser();
            $user->setName($name);
            $user->setLastname($lastname);
            $user->setBorn($born);
            $user->setSex($sex);
            $user->setIam($iam);
            $user->setCity($city);
            $response=$daoUser->update($user);
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
            $response=false;
        }
        return $response;
    }
    /**
     * Send the message to an user
     * @param string Email for the user
     * @param string Subject for the message
     * @param string Message of the Contact
     */
    public static function sendSystemEmail($email,$subject,$message){
        $result=false;
        $correct=true;
        if(is_array($email)){
            foreach ($email as $mail) {
                if(!SecurityController::isemail($mail)){
                    $correct=false;
                    break;
                }
            }
        }else{
            if(!SecurityController::isemail($email)){
                $correct=false;
            }
        }
        if($correct){
            if(is_array($email)){
                $stringEmail=implode(",",$email);
            }else{
                $stringEmail=$email;
            }
            $to=$stringEmail;
            $subject=SecurityController::sanitizeString($subject);
            $shell="/home/operador/sendMail.sh";
            if(file_exists($shell)){
                $command='sh /home/operador/sendMail.sh '.$to.' "'.$subject.'" "'.$message.'"';
                $result=system($command);
            }
        }
        return $result;
    }
    /**
     * Function that allows entry into the system
     * @param string formated email
     * @param string formated password
     * @param bool to keep the session active.
     * @return string 'logged'  if mail and password are correct
     * @return string 'nonprofit'  if is an NPO
     * @return string 'error' if the email and password are bad-formated or are wrong
     */
    function passwordChange($lastPwr,$newPwr,$email){
        $response=false;
        $user=AccessController::getSessionUser();
        if(AccessController::verifyValidationKey($email, $lastPwr)){
            $changed=$this->access->changePasword(false,$newPwr,$email);
            $response=$changed;
        }else if(SecurityController::ispassword($lastPwr)&&SecurityController::ispassword($newPwr)){
            $email=$user->getEmail();
            $changed=$this->access->changePasword($lastPwr,$newPwr,$email);
            $response=$changed;
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."Password data incorrect");
        }
        return SecurityController::sanitizeString($response);
    }
}
?>
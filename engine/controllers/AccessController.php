<?php
/** AccessController File
 * @package controllers @subpackage core */
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
class AccessController{
    /** Max number of fails before time-blocking the IP 
     * @ int
     */
    private $maxFails;
    /** Cantidad de minutos que debe esperar antes de poder volver a conectar luego
     * de fallar los $maxFails intentos
     * @ int
     */
    private $blockTime;
    /**
     * Constructor: Define the Access object and set the max of login fails
     * @param int $maxFails Máximo de intentos fallidos permitidos
     * @param int $blockTime Minutos que debe esperar antes de poder volver a conectar
     */
    function __construct($maxFails=5,$blockTime=5){
        $this->maxFails=$maxFails;
        $this->blockTime=$blockTime;
        if(!array_key_exists("fails",$_SESSION)){
            $_SESSION["fails"]=0;
        }
    }
    /**
     * Create an user account on the system
     * @param string $email Email con formato de email
     * @param string $password Clave de acceso
     * @param string $name [optional] Nombre de usuario
     * @param string $lastname [optional] Apellido del usuario
     * @return string Valor con el registro válido o no válido<br/>
     *      'success'  if mail and password are correct<br/>
     *      'exist' if the user already exist<br/>
     *      'error' if the email and password are bad-formated or are wrong
     */
    function signup($email,$password,$name="",$lastname=""){
        $response='error';
        $name=SecurityController::sanitizeString($name);
        $lastname=SecurityController::sanitizeString($lastname);
        if(SecurityController::isemail($email)&&SecurityController::ispassword($password)){
            $access=new Access();
            $user=new User(0,$email,$password);
            $user->setName($name);
            $user->setLastname($lastname);
            $response=$access->signup($user);
            if(SecurityController::isClass($response,"User")){
                $response=$this->login($email,$password,false);
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."Signup user: Username and/or password not allowed");
        }
        return SecurityController::sanitizeString($response);
    }
    /**
     * Function that allows entry into the system
     * @param string formated email
     * @param string formated password
     * @param bool to keep the session active.
     * @return string Valor con el registro válido o no válido<br/>
     *      'success'  if mail and password are correct<br/>
     *      'error' if the email and password are bad-formated or are wrong
     */
    function login($email,$password,$keep=false){
        $response=false;
        if(SecurityController::isemail($email)&&SecurityController::ispassword($password)&&SecurityController::isBool($keep)){
            $access=new Access();
            $user=$access->login(new User(0,$email,$password));
            if(SecurityController::isClass($user,"User")){
                //Established session length
                if($keep){
                    Maqinato::$config["client"]["sessionLifeTime"]=0;
                }
                $this->startSession($user);
                $response="success";
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Failed login ".$_SESSION["fails"]);
                $response="error";
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."Login: Username and/or password not allowed");
        }
        //Aumenta los fails si no se puede registrar
        if(!$response||$response==="error"){
            $_SESSION["fails"]++;
            if(intval($_SESSION["fails"])>=$this->maxFails){
                //TODO: Bloquear x minutos por intentos (almacenar en la base de datos)
            }
        }
        return SecurityController::sanitizeString($response);
    }
    /**
     * Logout system, close only the specified session
     * @param string formated email (optional)
     * @param int session id from the user object (optional)
     */
    public function logout($email=""){
        if(SecurityController::isemail($email)){
            $access=new Access();
            $access->logout($email);
        }
        $this->destroy();
    }
    /**
     * Initialize the user session and start the fail counter in 0
     * @param User user object
     */
    function startSession($user){
        $_SESSION["user"]=serialize($user);
        $_SESSION["fails"]=0;
        $_SESSION["sessionLifetime"]=Maqinato::$config["client"]["sessionLifeTime"];
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
    
    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    /** 
//     * Return the active User in the Session
//     * @return User active in the session
//     * @return false if the session does not have an User
//     */
//    public static function getSessionUser(){
//        $active=false;
//        if (isset($_SESSION["user"])){
//            $daoUser=new DaoUser();
//            $userSession=new User();
//            $userSession=unserialize($_SESSION['user']);
//            $user=$daoUser->read($userSession->getEmail());
//            $active=$user;
//        }else{
//            $active=false;
//        }
//        return $active;
//    }
//    
//    
    
//    /**
//     * Close all the user sessions
//     * @param string formated email
//     */
//    function logoutAll($email){
//        $this->access->logout($email);
//        $this->destroy();
//    }
//    
//    
    
//    /** 
//     * Check if the user session is live. If the user need validate the account
//     * redirect to the page
//     * @return true if the session is active
//     * @return false otherwise
//     */
//    public static function checkSession(){
//        $active=false;
//        $user=new User();
//        if (isset($_SESSION["user"])){
//            $user=unserialize($_SESSION['user']);
//            if(SecurityController::isemail($user->getEmail())){
//                $active=true;
//            }else{
//                $active=false;
//            }
//        }else{
//            $active=false;
//        }
//        return $active;
//    }
//    /**
//     * Comprueba si la clave de validación y el correo corresponden
//     * @param string $email Email del usuario que se quiere verificar
//     * @param string $key Clave enviada al email
//     * @return bool True si la clave corresponde con el correo
//     */
//    public static function verifyValidationKey($email,$key){
//        $response=false;
//        $daoUser=new DaoUser();
//        $email=SecurityController::sanitizeString($email);
//        $key=SecurityController::sanitizeString($key);
//        if($daoUser->verifyValidationKey($email,$key)){
//            $user=SocialController::getUser($email);
//            $daoUser->updateValidationState($user,true);
//            $response=true;
//        }
//        return $response;
//    }
//    /**
//     * Verifica si el usuario ha validado su cuenta
//     * @param User $user Objeto de tipo usuario para el que se verifica si se ha 
//     * validado la cuenta
//     * @return bool True si se ha validado
//     */
//    public static function validatedAccount($user){
//        $response=false;
//        if(SecurityController::isclass($user,"User")){
//            $daoUser=new DaoUser();
//            $response=$daoUser->isValidated($user);
//        }
//        return $response;
//    }
    
//    /**
//     * Function that allows entry into the system
//     * @param string formated email
//     * @param string formated password
//     * @param bool to keep the session active.
//     * @return string 'logged'  if mail and password are correct
//     * @return string 'nonprofit'  if is an NPO
//     * @return string 'error' if the email and password are bad-formated or are wrong
//     */
//    function passwordChange($lastPwr,$newPwr,$email){
//        $response=false;
//        $user=AccessController::getSessionUser();
//        if(AccessController::verifyValidationKey($email, $lastPwr)){
//            $changed=$this->access->changePasword(false,$newPwr,$email);
//            $response=$changed;
//        }else if(SecurityController::ispassword($lastPwr)&&SecurityController::ispassword($newPwr)){
//            $email=$user->getEmail();
//            $changed=$this->access->changePasword($lastPwr,$newPwr,$email);
//            $response=$changed;
//        }else{
//            error_log("[".__FILE__.":".__LINE__."]"."Password data incorrect");
//        }
//        return SecurityController::sanitizeString($response);
//    }
}
?>
<?php
/** Access File
 * @package models @subpackage core */
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
     * @param User $user Usuario que se quiere registrar
     * @return mixed Objeto de tipo Usuario que se registró. Si no se pudo registrar
     *      retorna false.
     */
    function signup($user){
        $daoUser=new DaoUser();
        $crypt=new Crypt();
        $crypt->encrypt($user->getPassword());
        $user->setPassword($crypt->getPassword());
        $user->setSalt($crypt->getSalt());
        $user=$daoUser->create($user);
        return $user;
    }
    /**
     * Login a system user and creates the session maqinato
     * @param User $user Usuario que se quiere logear
     * @return string 'error' if the email or password are incorrect. Si los datos
     *          son correctos, retorna el objeto User.
     */
    function login($user){
        $daoUser=new DaoUser();
        $daoSession=new DaoSession();
        $pass=$daoUser->readPassword($user->getEmail());
        $salt=$daoUser->readSalt($user->getEmail());
        if(!empty($pass)&&!empty($salt)){
            $crypt=new Crypt();
            if($crypt->validate($user->getPassword(),$salt,$pass)){
                $logedUser=$daoUser->readByEmail($user->getEmail());
                //Save the start of the session
                $session=new Session($logedUser->getId());
                $session->start($this->getIp());
                $daoSession->create($session);
                return $logedUser;
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
        $daoUser=new DaoUser();
        $daoSession=new DaoSession();
        $user=$daoUser->readByEmail($email);
        $session=$daoSession->lastSession($user);
        $session->stop($this->getIp());
        $daoSession->update($session);
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
    
    
///**
//     * Login a system user and creates the session maqinato
//     * @param string valid password
//     * @param string valid password
//     * @param string valid password
//     * @return string 'error' password is incorrect
//     * @return User user object
//     * @todo Develop the geoposition
//     */
//    function changePasword($lastPwr,$newPwr,$email){
//        $pass="";
//        $saltsave="";
//        $daoUser=new DaoUser("all");
//        $hash=$daoUser->readHash($email);
//        $salt=$daoUser->readSalt($email);
//        if(!empty($hash)&&!empty($salt)){
//            $crypt=new Crypt();
//            if(!$lastPwr){
//                $validate=true;
//            }else{
//                $validate=$crypt->validate($lastPwr,$salt,$hash);
//            }
//            if($validate){
//                $crypt->encrypt($newPwr);
//                $pass=$crypt->getPassword();
//                $saltsave=$crypt->getSalt();
//                if(!$crypt->validate($newPwr,$salt,$hash)){
//                    $response=$daoUser->updatePass($email,$pass,$saltsave);
//                }else{
//                    $response="2";
//                }
//            }else{
//                $response='3';
//            }
//        }else{
//            $response='Error';
//        }
//        return $response;
//    }
} 
?>

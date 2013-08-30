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
        if($session){
            $session->stop($this->getIp());
            $daoSession->update($session);
        }
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
     * @param string $email Email válido
     * @param string $prev Password anterior
     * @param string $new Password nuevo
     * @return bool false if password is incorrect, true if could change the pass
     */
    function changePasword($email,$prev,$new){
        $response=false;
        $daoUser=new DaoUser();
        $prevHash=$daoUser->readHash($email);
        $prevSalt=$daoUser->readSalt($email);
        if(!empty($prevHash)&&!empty($prevSalt)){
            $crypt=new Crypt();
            if($crypt->validate($prev,$prevSalt,$prevHash)){
                $user=$daoUser->readByEmail($email);
                $crypt->encrypt($new);
                $user->setPassword($crypt->getPassword());
                $user->setSalt($crypt->getSalt());
                $response=$daoUser->updatePassword($user);
            }
        }
        return $response;
    }
} 
?>

<?php
/** DaoSession File
 * @package models @subpackage dal */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'dal/Database.php';
include_once Router::rel('models').'core/Session.php';
/**
 * DaoSession Class
 *
 * Class data layer for the Session class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage dal
 */
class DaoSession extends Dao{
    /**
     * Constructor: sets the database Object and the PDO handler
     */
    function DaoSession(){
        parent::Dao();
    }
    /**
     * Create a session in the database
     * @param Session Session object
     * @return false if could'nt create it
     * @return Session Session created
     */
    function create($session){
        $created=false;
        if(!$this->sessionExist($session->getId())){
            $stmt = $this->handler->prepare("INSERT INTO sessions (ini,end,state,ipIni,ipEnd,phpSession,user) VALUES (:ini,:end,:state,:ipIni,:ipEnd,:phpSession,:user)");
            $stmt->bindParam(':ini', $session->getIni());
            $stmt->bindParam(':end',$session->getEnd());
            $stmt->bindParam(':state',$session->getState());
            $stmt->bindParam(':ipIni',$session->getIpIni());
            $stmt->bindParam(':ipEnd',$session->getIpEnd());
            $stmt->bindParam(':phpSession',$session->getPhpSession());
            $stmt->bindParam(':user',$session->getUser());
            $stmt->execute();
            $session->setId(intval($this->handler->lastInsertID()));
            $created=$session->getId();
        }
        return $created;
    }
    /**
     * Read a session from the database
     * @param string idSession
     * @return Session Session loaded
     */
    function read($idSession){
        $stmt = $this->handler->prepare("SELECT * FROM sessions WHERE id = ?");
        if ($stmt->execute(array($idSession))) {
            $list=$stmt->fetch();
            $session=new Session();
            $session->setId($idSession);
            $session->setIni($list["ini"]);
            $session->setEnd($list["end"]);
            $session->setState($list["state"]);
            $session->setIpIni($list["ipIni"]);
            $session->setIpEnd($list["ipEnd"]);
            $session->setPhpSession($list["phpSession"]);
            $session->setUser(intval($list["user"]));
            return $session;
        }
    }
    /**
     * Update a session in the database
     * @param Session Session object
     * @return false if could'nt update it
     * @return true if the session was updated
     */
    function update($session){
        $updated=false;
        if($this->sessionExist($session->getId())){
            $stmt = $this->handler->prepare("UPDATE sessions SET end=?,state=?,ipEnd=? WHERE id=?");
            $stmt->execute(array($session->getEnd(),$session->getState(),$session->getIpEnd(),$session->getId()));
            $updated=true;
        }else{
            $updated=false;
        }
        return $updated;
    }
    /**
     * Delete a session from the database
     * @param string idSession
     * @return false if could'nt delete it
     * @return true if was deleted
     * @todo implement
     */
    function delete($idSession){
        
    }
    /**
     * Return if a session exist in the database
     * @param string idSession
     * @return false if doesn't exist
     * @return true if exist
     */
    function sessionExist($idSession){
        $exist=false;
        $stmt = $this->handler->prepare("SELECT id FROM sessions WHERE id = ?");
        if ($stmt->execute(array($idSession))) {
            $list=$stmt->fetch();
            if($list){
                if(intval($list["id"])===intval($idSession)){
                    $exist=true;
                }else{
                    $exist=false;
                }
            }
        }
        return $exist;
    }
    /**
     * Return the last session of an user
     * @param string user id
     * @return false if fail
     * @return int last session id for the user
     */
    function lastSession($userId){
        $session=false;
        $stmt = $this->handler->prepare("SELECT id FROM sessions WHERE user = ? ORDER BY id DESC LIMIT 1");
        if ($stmt->execute(array($userId))) {
            $list=$stmt->fetch();
            $session=new Session();
            $session=$this->read($list["id"]);
        }
        return $session;
    }
}

?>

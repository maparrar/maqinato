<?php
/** DaoSession File
 * @package models @subpackage dal */
/**
 * DaoSession Class
 *
 * Class data layer for the Session class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author maparrar <maparrar@gmail.com>
 * @package models
 * @subpackage dal
 */
class DaoSession{
    /**
     * Create an Session in the database
     * @param Session $session new Session
     * @return Session Session stored
     * @return string "exist" if the Session already exist
     * @return false if the Session couldn't created
     */
    function create($session){
        $created=false;
        if(!$this->exist($session)){    
            $handler=Maqinato::connect("write");
            $stmt = $handler->prepare("INSERT INTO Session 
                (`id`,`ini`,`end`,`state`,`ipIni`,`ipEnd`,`phpSession`,`user`) VALUES 
                (:id,:ini,:end,:state,:ipIni,:ipEnd,:phpSession,:user)");
            $stmt->bindParam(':id',$session->getId());
            $stmt->bindParam(':ini',$session->getIni());
            $stmt->bindParam(':end',$session->getEnd());
            $stmt->bindParam(':state',$session->getState());
            $stmt->bindParam(':ipIni',$session->getIpIni());
            $stmt->bindParam(':ipEnd',$session->getIpEnd());
            $stmt->bindParam(':phpSession',$session->getPhpSession());
            $stmt->bindParam(':user',$session->getUser());
            if($stmt->execute()){
                $session->setId(intval($handler->lastInsertID()));
                $created=$session;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }else{
            $created="exist";
        }
        return $created;
    }
    /**
     * Read a Session from the database
     * @param int $id Session identificator
     * @return Session Session loaded
     */
    function read($id){
        $response=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT * FROM Session WHERE id=:id");
        $stmt->bindParam(':id',$id);
        if ($stmt->execute()) {
            if($stmt->rowCount()>0){
                $row=$stmt->fetch();
                $session=new Session();
                $session->setId(intval($row["id"]));
                $session->setIni($row["ini"]);
                $session->setEnd($row["end"]);
                $session->setState($row["state"]);
                $session->setIpIni($row["ipIni"]);
                $session->setIpEnd($row["ipEnd"]);
                $session->setPhpSession($row["phpSession"]);
                $session->setUser(intval($row["user"]));
                $response=$session;
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $response;
    }
    /**
     * Update a Session in the database
     * @param Session $session Session object
     * @return false if could'nt update it
     * @return true if the Session was updated
     */
    function update($session){
        $updated=false;
        if($this->exist($session)){
            $handler=Maqinato::connect();
            $stmt = $handler->prepare("UPDATE Session SET 
                `ini`=:ini,
                `end`=:end,
                `state`=:state,
                `ipIni`=:ipIni,
                `ipEnd`=:ipEnd,
                `phpSession`=:phpSession,
                `user`=:user,
                WHERE id=:id");
            $stmt->bindParam(':id',$session->getId());
            $stmt->bindParam(':ini',$session->getIni());
            $stmt->bindParam(':end',$session->getEnd());
            $stmt->bindParam(':state',$session->getState());
            $stmt->bindParam(':ipIni',$session->getIpIni());
            $stmt->bindParam(':ipEnd',$session->getIpEnd());
            $stmt->bindParam(':phpSession',$session->getPhpSession());
            $stmt->bindParam(':user',$session->getUser());
            if($stmt->execute()){
                $updated=true;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }else{
            $updated=false;
        }
        return $updated;
    }
    /**
     * Delete an Session from the database
     * @param Session $session Session object
     * @return false if could'nt delete it
     * @return true if the Session was deleted
     */
    function delete($session){
        $deleted=false;
        if($this->exist($session)){
            $handler=Maqinato::connect("delete");
            $stmt = $handler->prepare("DELETE Session WHERE id=:id");
            $stmt->bindParam(':id',$session->getId());
            if($stmt->execute()){
                $deleted=true;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }else{
            $deleted=false;
        }
        return $deleted;
    }
    /**
     * Return if a Session exist in the database
     * @param Session $session Session object
     * @return false if doesn't exist
     * @return true if exist
     */
    function exist($session){
        $exist=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM Session WHERE id=:id");
        $stmt->bindParam(':id',$session->getId());
        if ($stmt->execute()) {
            $list=$stmt->fetch();
            if($list){
                if(intval($list["id"])===intval($session->getId())){
                    $exist=true;
                }else{
                    $exist=false;
                }
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $exist;
    }
    /**
     * Get the list of Session
     * @return Session[] List of Session
     */
    function listing(){
        $list=array();
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM Session");
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $session=$this->read($row["id"]);
                array_push($list,$session);
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $list;
    }
}
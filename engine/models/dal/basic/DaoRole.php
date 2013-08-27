<?php
/** DaoRole File
 * @package models @subpackage dal */
/**
 * DaoRole Class
 *
 * Class data layer for the Role class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author maparrar <maparrar@gmail.com>
 * @package models
 * @subpackage dal
 */
class DaoRole{
    /**
     * Create an Role in the database
     * @param Role $role new Role
     * @return Role Role stored
     * @return string "exist" if the Role already exist
     * @return false if the Role couldn't created
     */
    function create($role){
        $created=false;
        if(!$this->exist($role)){    
            $handler=Maqinato::connect("write");
            $stmt = $handler->prepare("INSERT INTO Role 
                (`id`,`name`) VALUES 
                (:id,:name)");
            $stmt->bindParam(':id',$role->getId());
            $stmt->bindParam(':name',$role->getName());
            if($stmt->execute()){
                $role->setId(intval($handler->lastInsertID()));
                $created=$role;
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
     * Read a Role from the database
     * @param int $id Role identificator
     * @return Role Role loaded
     */
    function read($id){
        $response=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT * FROM Role WHERE id=:id");
        $stmt->bindParam(':id',$id);
        if ($stmt->execute()) {
            if($stmt->rowCount()>0){
                $row=$stmt->fetch();
                $role=new Role();
                $role->setId(intval($row["id"]));
                $role->setName($row["name"]);
                $response=$role;
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $response;
    }
    /**
     * Update a Role in the database
     * @param Role $role Role object
     * @return false if could'nt update it
     * @return true if the Role was updated
     */
    function update($role){
        $updated=false;
        if($this->exist($role)){
            $handler=Maqinato::connect();
            $stmt = $handler->prepare("UPDATE Role SET 
                `name`=:name 
                WHERE id=:id");
            $stmt->bindParam(':id',$role->getId());
            $stmt->bindParam(':name',$role->getName());
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
     * Delete an Role from the database
     * @param Role $role Role object
     * @return false if could'nt delete it
     * @return true if the Role was deleted
     */
    function delete($role){
        $deleted=false;
        if($this->exist($role)){
            $handler=Maqinato::connect("delete");
            $stmt = $handler->prepare("DELETE Role WHERE id=:id");
            $stmt->bindParam(':id',$role->getId());
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
     * Return if a Role exist in the database
     * @param Role $role Role object
     * @return false if doesn't exist
     * @return true if exist
     */
    function exist($role){
        $exist=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM Role WHERE id=:id");
        $stmt->bindParam(':id',$role->getId());
        if ($stmt->execute()) {
            $list=$stmt->fetch();
            if($list){
                if(intval($list["id"])===intval($role->getId())){
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
     * Get the list of Role
     * @return Role[] List of Role
     */
    function listing(){
        $list=array();
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM Role");
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $role=$this->read($row["id"]);
                array_push($list,$role);
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $list;
    }
}
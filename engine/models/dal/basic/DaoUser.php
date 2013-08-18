<?php
/** DaoUser File
 * @package models @subpackage dal */
/**
 * DaoUser Class
 *
 * Class data layer for the User class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author maparrar <maparrar@gmail.com>
 * @package models
 * @subpackage dal
 */
class DaoUser extends Dao{
    /**
     * Constructor: sets the database Object and the PDO handler
     * @param string Type of connection string to use
     */
    function DaoUser(){
        parent::Dao();
    }
    /**
     * Create an User in the database
     * @param User $user new User
     * @return User User stored
     * @return string "exist" if the User already exist
     * @return false if the User couldn't created
     */
    function create($user){
        $created=false;
        if(!$this->exist($user->getId())){    
            $handler=Maqinato::connect("write");
            $stmt = $handler->prepare("INSERT INTO User 
                (`id`,`password`,`salt`) VALUES 
                (:id,:password,:salt)");
            $stmt->bindParam(':id',$user->getId());
            $stmt->bindParam(':password',$user->getPassword());
            $stmt->bindParam(':salt',$user->getSalt());
            if($stmt->execute()){
                $user->setId(intval($handler->lastInsertID()));
                $created=$user;
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
     * Read a User from the database
     * @param int $id User identificator
     * @return User User loaded
     */
    function read($id){
        $response=null;
        if($this->exist($id)){
            $handler=Maqinato::connect("read");
            $stmt = $handler->prepare("SELECT * FROM User WHERE id= ?");
            if ($stmt->execute(array($id))) {
                $row=$stmt->fetch();
                $user=new User();
                $user->setId(intval($row["id"]));
                $user->setPassword($row["password"]);
                $user->setSalt($row["salt"]);
                $response=$user;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }
        return $response;
    }
    /**
     * Update a User in the database
     * @param User $user User object
     * @return false if could'nt update it
     * @return true if the User was updated
     */
    function update($user){
        $updated=false;
        if($this->exist($user->getId())){
            $handler=Maqinato::connect();
            $stmt = $handler->prepare("UPDATE User SET 
                `password`=:password,
                `salt`=:salt,
                WHERE id=:id");
            $stmt->bindParam(':id',$user->getId());
            $stmt->bindParam(':password',$user->getPassword());
            $stmt->bindParam(':salt',$user->getSalt());
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
     * Delete an User from the database
     * @param User $user User object
     * @return false if could'nt delete it
     * @return true if the User was deleted
     */
    function delete($user){
        $deleted=false;
        if($this->exist($user->getId())){
            $handler=Maqinato::connect("delete");
            $stmt = $handler->prepare("DELETE User WHERE id=:id");
            $stmt->bindParam(':id',$user->getId());
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
     * Return if a User exist in the database
     * @param int $id User identificator
     * @return false if doesn't exist
     * @return true if exist
     */
    function exist($id){
        $exist=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM User WHERE id=:id");
        $stmt->bindParam(':id',$id);
        if ($stmt->execute()) {
            $list=$stmt->fetch();
            if($list){
                if(intval($list["id"])===intval($id)){
                    $exist=true;
                }else{
                    $exist=false;
                }
            }
        }
        return $exist;
    }
    /**
     * Get the list of User
     * @return User[] List of User
     */
    function listing(){
        $list=array();
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM User");
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $user=$this->read($row["id"]);
                array_push($list,$user);
            }
        }
        return $list;
    }
}
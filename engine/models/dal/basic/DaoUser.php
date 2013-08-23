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
class DaoUser{
    /**
     * Create an User in the database
     * @param User $user new User
     * @return User User stored
     * @return string "exist" if the User already exist
     * @return false if the User couldn't created
     */
    function create($user){
        $created=false;
        if(!$this->exist($user)){    
            $handler=Maqinato::connect("write");
            //Guarda el objeto Persona y luego el Usuario
            $daoPerson=new DaoPerson();
            $person=$daoPerson->create($user);
            $stmt = $handler->prepare("INSERT INTO User 
                (`id`,`username`,`password`,`salt`) VALUES 
                (:id,:username,:password,:salt)");
            $stmt->bindParam(':id',$person->getId());
            $stmt->bindParam(':username',$user->getUsername());
            $stmt->bindParam(':password',$user->getPassword());
            $stmt->bindParam(':salt',$user->getSalt());
            if($stmt->execute()){
                $user->setId(intval($person->getId()));
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
        $response=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT * FROM User WHERE id=:id");
        $stmt->bindParam(':id',$id);
        if ($stmt->execute()) {
            if($stmt->rowCount()>0){
                $row=$stmt->fetch();
                $user=new User();
                $daoPerson=new DaoPerson();
                $person=$daoPerson->read($id);
                $user->setId(intval($row["id"]));
                $user->setUsername($row["username"]);
                $user->setPassword($row["password"]);
                $user->setSalt($row["salt"]);
                $user->setName($person->getName());
                $user->setLastname($person->getLastname());
                $user->setEmail($person->getEmail());
                $user->setPhone($person->getPhone());
                $response=$user;
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
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
        if($this->exist($user)){
            $handler=Maqinato::connect();
            $daoPerson=new DaoPerson();
            $daoPerson->update($user);
            $stmt = $handler->prepare("UPDATE User SET 
                `username`=:username,
                `password`=:password,
                `salt`=:salt,
                WHERE id=:id");
            $stmt->bindParam(':id',$user->getId());
            $stmt->bindParam(':username',$user->getUsername());
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
        if($this->exist($user)){
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
     * @param User $user User object
     * @return false if doesn't exist
     * @return true if exist
     */
    function exist($user){
        $exist=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id,username FROM User WHERE id=:id OR username=:username");
        $stmt->bindParam(':id',$user->getId());
        $stmt->bindParam(':username',$user->getUsername());
        if ($stmt->execute()) {
            $row=$stmt->fetch();
            if($row){
                if(intval($row["id"])===intval($user->getId())||trim($row["username"])===trim($user->getUsername())){
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
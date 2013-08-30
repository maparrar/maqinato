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
                (`id`,`email`,`password`,`salt`,`role`) VALUES 
                (:id,:email,:password,:salt,:role)");
            $stmt->bindParam(':id',$user->getId());
            $stmt->bindParam(':email',$user->getEmail());
            $stmt->bindParam(':password',$user->getPassword());
            $stmt->bindParam(':salt',$user->getSalt());
            $stmt->bindParam(':role',$user->getRole()->getId());
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
        $daoRole=new DaoRole();
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
                $user->setEmail($row["email"]);
                $user->setPassword("");
                $user->setSalt("");
                $user->setName($person->getName());
                $user->setLastname($person->getLastname());
                $user->setRole($daoRole->read(intval($row["role"])));
                $response=$user;
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $response;
    }
    /**
     * Read a User from the database
     * @param string $email Email del usuario
     * @return User User loaded
     */
    function readByEmail($email){
        $response=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM User WHERE email=:email");
        $stmt->bindParam(':email',$email);
        if ($stmt->execute()) {
            if($stmt->rowCount()>0){
                $row=$stmt->fetch();
                $user=$this->read(intval($row["id"]));
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
                `email`=:email,`role`=:role  
                WHERE id=:id");
            $stmt->bindParam(':id',$user->getId());
            $stmt->bindParam(':email',$user->getEmail());
            $stmt->bindParam(':role',$user->getRole()->getId());
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
        $stmt = $handler->prepare("SELECT id,email FROM User WHERE id=:id OR email=:email");
        $stmt->bindParam(':id',$user->getId());
        $stmt->bindParam(':email',$user->getEmail());
        if ($stmt->execute()) {
            $row=$stmt->fetch();
            if($row){
                if(intval($row["id"])===intval($user->getId())||trim($row["email"])===trim($user->getEmail())){
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
    /**
     * Return the hash password stored in the database
     * @param string user email
     * @return string the hash password for the email
     * @return false if doesn't exist
     */    
    function readPassword($email){
        $hash=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT password FROM User WHERE email = ?");
        if ($stmt->execute(array($email))) {
            $list=$stmt->fetch();
            $hash=$list["password"];
        }
        return $hash;
    }
    /**
     * Return the salt encoder stored in the database
     * @param string user email
     * @return string the salt encoder for the email
     * @return false if doesn't exist
     */ 
    function readSalt($email){
        $salt=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT salt FROM User WHERE email = ?");
        if ($stmt->execute(array($email))) {
            $list=$stmt->fetch();
            $salt=$list["salt"];
        }
        return $salt;
    }
    /**
     * Actualiza el password de usuario
     * @param User $user Usuario con email, password y salt definidos para almacenar
     * @return false if could'nt update it, true if the user was updated
     */
    function updatePassword($user){
        $updated=false;
        if($this->exist($user)){
            $stmt = $this->handler->prepare("UPDATE User SET 
                password=:password,
                salt=:salt 
                WHERE id=:id"
            );
            $stmt->bindParam(':password',$user->getPassword());
            $stmt->bindParam(':salt',$user->getSalt());
            $stmt->bindParam(':id',$user->getId());
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
}
<?php
/** DaoPerson File
 * @package models @subpackage dal */
/**
 * DaoPerson Class
 *
 * Class data layer for the Person class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author maparrar <maparrar@gmail.com>
 * @package models
 * @subpackage dal
 */
class DaoPerson{
    /**
     * Create an Person in the database
     * @param Person $person new Person
     * @return Person Person stored
     * @return string "exist" if the Person already exist
     * @return false if the Person couldn't created
     */
    function create($person){
        $created=false;
        if(!$this->exist($person->getId())){    
            $handler=Maqinato::connect("write");
            $stmt = $handler->prepare("INSERT INTO Person 
                (`id`,`name`,`lastname`,`email`,`phone`) VALUES 
                (:id,:name,:lastname,:email,:phone)");
            $stmt->bindParam(':id',$person->getId());
            $stmt->bindParam(':name',$person->getName());
            $stmt->bindParam(':lastname',$person->getLastname());
            $stmt->bindParam(':email',$person->getEmail());
            $stmt->bindParam(':phone',$person->getPhone());
            if($stmt->execute()){
                $person->setId(intval($handler->lastInsertID()));
                $created=$person;
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
     * Read a Person from the database
     * @param int $id Person identificator
     * @return Person Person loaded
     */
    function read($id){
        $response=null;
        if($this->exist($id)){
            $handler=Maqinato::connect("read");
            $stmt = $handler->prepare("SELECT * FROM Person WHERE id= ?");
            if ($stmt->execute(array($id))) {
                $row=$stmt->fetch();
                $person=new Person();
                $person->setId(intval($row["id"]));
                $person->setName($row["name"]);
                $person->setLastname($row["lastname"]);
                $person->setEmail($row["email"]);
                $person->setPhone($row["phone"]);
                $response=$person;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }
        return $response;
    }
    /**
     * Update a Person in the database
     * @param Person $person Person object
     * @return false if could'nt update it
     * @return true if the Person was updated
     */
    function update($person){
        $updated=false;
        if($this->exist($person->getId())){
            $handler=Maqinato::connect();
            $stmt = $handler->prepare("UPDATE Person SET 
                `name`=:name,
                `lastname`=:lastname,
                `email`=:email,
                `phone`=:phone,
                WHERE id=:id");
            $stmt->bindParam(':id',$person->getId());
            $stmt->bindParam(':name',$person->getName());
            $stmt->bindParam(':lastname',$person->getLastname());
            $stmt->bindParam(':email',$person->getEmail());
            $stmt->bindParam(':phone',$person->getPhone());
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
     * Delete an Person from the database
     * @param Person $person Person object
     * @return false if could'nt delete it
     * @return true if the Person was deleted
     */
    function delete($person){
        $deleted=false;
        if($this->exist($person->getId())){
            $handler=Maqinato::connect("delete");
            $stmt = $handler->prepare("DELETE Person WHERE id=:id");
            $stmt->bindParam(':id',$person->getId());
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
     * Return if a Person exist in the database
     * @param int $id Person identificator
     * @return false if doesn't exist
     * @return true if exist
     */
    function exist($id){
        $exist=false;
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM Person WHERE id=:id");
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
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $exist;
    }
    /**
     * Get the list of Person
     * @return Person[] List of Person
     */
    function listing(){
        $list=array();
        $handler=Maqinato::connect("read");
        $stmt = $handler->prepare("SELECT id FROM Person");
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $person=$this->read($row["id"]);
                array_push($list,$person);
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $list;
    }
}
<?php
/** DaoMedia File
 * @package models @subpackage dal\core */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'dal/Dao.php';
include_once Router::rel('models').'dal/core/DaoMediaType.php';
include_once Router::rel('models').'core/Media.php';
/**
 * DaoMedia Class
 *
 * Class data layer for the Media class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage dal\core
 */
class DaoMedia extends Dao{
    /**
     * Constructor: sets the database Object and the PDO handler
     */
    function DaoMedia(){
        parent::Dao();
    }
    /**
     * Create a Media in the database
     * @param Media Media to create in the database
     * @return Media Media created
     * @return string 'exist' if the Media already exist
     * @return false if the Media couldn't be created
     */
    function create($media){
        $created=false;
        if(!$this->exist($media->getId())){
            $stmt = $this->handler->prepare("INSERT INTO media 
                (name,type) VALUES 
                (:name,:type)"
            );
            $idType=1;
            if($media->getType()!=null){
                $idType=$media->getType()->getId();
            }
            $stmt->bindParam(':name',$media->getName());
            $stmt->bindParam(':type',$idType);
            $stmt->execute();
            $object=$media;
            $object->setId(intval($this->handler->lastInsertID()));
            $created=$object;
        }else{
            $created="exist";
        }
        return $created;
    }
    /**
     * Read a Media from the database
     * @param int Media id
     * @return Media Media loaded
     */
    function read($id){
        $response=null;
        if($this->exist($id)){
            $daoTyp=new DaoMediaType();
            $stmt = $this->handler->prepare("SELECT * FROM media WHERE id= ?");
            if ($stmt->execute(array($id))) {
                $list=$stmt->fetch();
                $object=new Media();
                $object->setId(intval($list["id"]));
                $object->setName($list["name"]);
                $object->setType($daoTyp->read($list["type"]));
                $response=$object;
            }
        }
        return $response;
    }
    /**
     * Update a Media in the database
     * @param Media Media object
     * @return false if could'nt update it
     * @return true if the Media was updated
     */
    function update($object){
        $updated=false;
        if($this->exist($object->getId())){
            $stmt = $this->handler->prepare("UPDATE media SET 
                name=?,
                type=? 
            WHERE id=?");
            $stmt->execute(array(
                $object->getName(),
                $object->getType()->getId(),
                $object->getId()
            ));
            $updated=true;
        }else{
            $updated=false;
        }
        return $updated;
    }
    /**
     * Delete a Media from the database
     * @param Media Media object
     * @return false if could'nt delete it
     * @return true if the Media was deleted
     */
    function delete($object){
        $deleted=false;
        if($this->exist($object->getId())){
            $stmt = $this->handler->prepare("DELETE media WHERE id=?");
            $stmt->execute(array($object->getId()));
            $deleted=true;
        }else{
            $deleted=false;
        }
        return $deleted;
    }
    /**
     * Get the list of Media
     * @return Media[] List of Media
     */
    function listing(){
        $filter=array();
        $list=array();
        $stmt = $this->handler->prepare("SELECT id FROM media");
        if ($stmt->execute($filter)) {
            while ($row = $stmt->fetch()){
                $object=$this->read($row["id"]);
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Return if a Media exist in the database
     * @param int Media identificator
     * @return false if doesn't exist
     * @return true if exist
     */
    function exist($id){
        $exist=false;
        $stmt = $this->handler->prepare("SELECT id FROM media WHERE id = ?");
        if ($stmt->execute(array($id))) {
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
}
?>

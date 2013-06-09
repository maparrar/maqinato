<?php
/** DaoMediaType File
 * @package models @subpackage dal\core */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'dal/Dao.php';
include_once Router::rel('models').'core/MediaType.php';
/**
 * DaoMediaType Class
 *
 * Class data layer for the MediaType class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage dal\core
 */
class DaoMediaType extends Dao{
    /**
     * Constructor: sets the database Object and the PDO handler
     */
    function DaoMediaType(){
        parent::Dao();
    }
    /**
     * Read a MediaType from the database
     * @param int MediaType id
     * @return MediaType MediaType loaded
     */
    function read($id){
        $stmt = $this->handler->prepare("SELECT * FROM media_types WHERE id= ?");
        if ($stmt->execute(array($id))) {
            $list=$stmt->fetch();
            $object=new MediaType();
            $object->setId(intval($list["id"]));
            $object->setName($list["name"]);
            return $object;
        }
    }
    /**
     * Get the list of MediaType
     * @return MediaType[] List of MediaType
     */
    function listing(){
        $filter=array();
        $list=array();
        $stmt = $this->handler->prepare("SELECT id FROM media_types");
        if ($stmt->execute($filter)) {
            while ($row = $stmt->fetch()){
                $object=$this->read($row["id"]);
                array_push($list,$object);
            }
        }
        return $list;
    }
}
?>

<?php
/** DaoUserType File
 * @package models @subpackage dal\core */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'dal/Dao.php';
include_once Router::rel('models').'core/UserType.php';
/**
 * DaoUserType Class
 *
 * Class data layer for the UserType class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage dal\core
 */
class DaoUserType extends Dao{
    /**
     * Constructor: sets the database Object and the PDO handler
     */
    function DaoUserType(){
        parent::Dao();
    }
    /**
     * Read a UserType from the database
     * @param int UserType id
     * @return UserType UserType loaded
     */
    function read($id){
        $stmt = $this->handler->prepare("SELECT * FROM user_types WHERE id= ?");
        if ($stmt->execute(array($id))) {
            $list=$stmt->fetch();
            $object=new UserType(intval($list["id"]),$list["name"]);
            return $object;
        }
    }
    /**
     * Get the list of UserType
     * @return UserType[] List of UserType
     */
    function listing(){
        $filter=array();
        $list=array();
        $stmt = $this->handler->prepare("SELECT id FROM user_types");
        if ($stmt->execute($filter)) {
            while ($row = $stmt->fetch()){
                $object=$this->read($row["id"]);
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Lee un UserType a partir de su nombre
     * @param string $name Nombre del tipo de usuario
     * @return UserType UserType loaded
     */
    function readFromName($name){
        $stmt = $this->handler->prepare("SELECT id FROM user_types WHERE LOWER(name)=:name");
        $stmt->bindParam(':name',$name);
        if ($stmt->execute()) {
            $list=$stmt->fetch();
            $object=$this->read(intval($list["id"]));
            return $object;
        }
    }
}
?>

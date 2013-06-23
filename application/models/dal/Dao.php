<?php
/** Dao File
 * @package models @subpackage dal\donation */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'dal/Database.php';
/**
 * Dao Class
 *
 * Class data layer for the access the databases
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage dal\donation
 */
class Dao{
    /** Database Object 
     * @var Database
     */
    protected $db;
    /** PDO handler object 
     * @var PDO
     */
    protected $handler;
    /**
     * Constructor: sets the database Object and the PDO handler
     * @param string Type of connection string to use
     */
    function Dao($type="all"){
        $this->db=new Database($type);
        $this->handler=$this->db->getHandler();
    }
}
?>

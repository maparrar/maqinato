<?php
/** Database File
 * @package models @subpackage dal */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'core/User.php';
include_once Router::rel('config').'Connection.php';
/**
 * Database Class
 *
 * Class for manage the database
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage dal
 */
class Database{
    /** PDO PHP Handler 
     * @var PDO
     */
    private $handler;
    /**
     * Constructor: Try to connect to the database or die
     * @param string Type of connection string to use
     */
    function Database($type="none"){
        $conection=new Connection();
        //Get the connection string
        $values=$conection->getConnection($type);
        try {
            $this->handler = new PDO(
                    $values['driver'].
                    ':host='.$values['host'].
                    ';dbname='.$values['database'],
                    $values['login'],
                    $values['password'],
                    array(PDO::ATTR_PERSISTENT => true)
                );
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>   GETTERS   <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Get PDO handler
     * @return PDO Object databse handler
     */
    function getHandler(){return $this->handler;}
}  
?>
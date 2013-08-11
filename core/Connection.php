<?php
/** Connection File
 * @package config @subpackage database */
/**
 * Connection Class
 *
 * Connecting with the database.
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package config
 * @subpackage database 
 */
class Connection{
    /** Connection Strings to write and read database
     * @var string
     */
    private $connections;
    /**
     * Connecting with the database.
     */
    function __construct() {
        $this->connections = array(
            'read'=>array(
                'driver'    => Config::$database["driver"],
                'persistent'=> Config::$database["persistent"],
                'host'      => Config::$database["host"][Router::server()],
                'login'     => Config::$database["read"]["login"],
                'password'  => Config::$database["read"]["password"],
                'database'  => Config::$database["database"]
             ),
            'write'=>array(
                'driver'    => Config::$database["driver"],
                'persistent'=> Config::$database["persistent"],
                'host'      => Config::$database["host"][Router::server()],
                'login'     => Config::$database["write"]["login"],
                'password'  => Config::$database["write"]["password"],
                'database'  => Config::$database["database"]
             ),
            'all'=>array(
                'driver'    => Config::$database["driver"],
                'persistent'=> Config::$database["persistent"],
                'host'      => Config::$database["host"][Router::server()],
                'login'     => Config::$database["all"]["login"],
                'password'  => Config::$database["all"]["password"],
                'database'  => Config::$database["database"]
             )
        );
    }
    /**
     * Return the connection string
     * @param string type of connection
     * @return string Connection string
     */
    function getConnection($type){
        $output="";
        if($type=="read"||$type=="all"){
            $output=$this->connections[$type];
        }
        return $output;
    }
}
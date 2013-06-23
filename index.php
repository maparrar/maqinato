<?php
/**
 * Redirect all the requests to the Maqinato class
 * 
 * @author https://github.com/maparrar/maqinato
 * @package views
 * @subpackage index
 */
/**
 * Get the application and the root folder
 */
$root=dirname(__FILE__);
$application=basename(dirname(__FILE__));
if (!defined('ROOT')) {
    define('ROOT', dirname(__FILE__));
}

/**
 * Get the application name
 */
if (!defined('APPLICATION')) {
    define('APPLICATION', basename(dirname(__FILE__)));
}


//Includes the Maqinato class
include_once 'core/Maqinato.php';


print_r("<br/>");
print_r("ROOT: ".ROOT."<br/>");
print_r("APP_DIR: ".APP_DIR."<br/>");
print_r("REQUEST_URI: ".$_SERVER['REQUEST_URI']."<br/>");
print_r("<br/>GET<br/>");
print_r($_GET);
print_r("<br/>POST<br/>");
print_r($_POST);


//header("Location: presentation/web/views/landing/");
?>
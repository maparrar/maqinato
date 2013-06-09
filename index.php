<?php
/**
 * Redirecting to the Landing page file **
 * 
 * @author https://github.com/maparrar/maqinato
 * @package views
 * @subpackage index
 */
/**
 * The full path to the directory which holds "app"
 */
if (!defined('ROOT')) {
    define('ROOT', dirname(__FILE__));
}

/**
 * The actual directory name for the "app"
 */
if (!defined('APP_DIR')) {
    define('APP_DIR', basename(dirname(__FILE__)));
}





print_r("<br/>");
print_r("ROOT: ".ROOT."<br/>");
print_r("APP_DIR: ".APP_DIR."<br/>");
print_r("REQUEST_URI: ".$_SERVER['REQUEST_URI']."<br/>");
print_r("HTTP_METHOD: ".$_SERVER['HTTP_METHOD']."<br/>");
print_r("<br/>GET<br/>");
print_r($_GET);
print_r("<br/>POST<br/>");
print_r($_POST);


//header("Location: presentation/web/views/landing/");
?>
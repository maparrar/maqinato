

<?php
/**
 * Redirect all the requests to the Maqinato class
 * 
 * @author https://github.com/maparrar/maqinato
 * @package views
 * @subpackage index
 */
//namespace maqinato\core;
/**
 * Get the application and the root folder
 */
$root=dirname(__FILE__);
$application=basename(dirname(__FILE__));

//Includes the Maqinato class
include_once 'core/Maqinato.php';



$maqinato=new Maqinato($root,$application);
$maqinato->route($_GET,$_POST);

?>
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

//Inicializa maqinato con los datos de root y aplicación
Maqinato::start($root, $application);
?>
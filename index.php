<?php
/**
 * Redirect all the requests to the Maqinato class
 * Se ejecuta cada que se hace una petición
 * 
 * @author https://github.com/maparrar/maqinato
 * @package views
 * @subpackage index
 */
//namespace maqinato\core;
/**
 * Get the application and the root folder
 */
session_start();
$_SESSION["root"]=dirname(__FILE__);
$_SESSION["application"]=basename(dirname(__FILE__));

//Includes the Maqinato class
include_once 'core/Maqinato.php';
//Inicializa maqinato
Maqinato::exec();
//Hace el router del request de la URL de entrada
Maqinato::route();
//Muestra la información de Maqinato (si debugLevel>0)
Maqinato::info();
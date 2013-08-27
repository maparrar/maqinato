<?php
/** jxRefreshSession File
 * @package controllers @subpackage core */
/**
 * jxRefreshSession
 *
 * Actualiza la sesión y genera un nuevo id en cada intento
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package engine
 * @subpackage ajax
 */
//Includes the Maqinato class
include_once '../../../core/Maqinato.php';
//Inicializa maqinato
Maqinato::exec();
//Se capturan los datos del POST
$email=$_POST['email'];
$password=$_POST['password'];
$keep=false;
if($_POST['keep']==="true"){
    $keep=true;
}
//Se hace el registro
$accessController = new AccessController();
echo $accessController->login($email,$password,$keep);
?>
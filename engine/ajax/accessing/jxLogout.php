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
$accessController = new AccessController();
//Close the session if the email is passed
$user=new User();
if($accessController->checkSession()){
    $user=unserialize($_SESSION['user']);
    $accessController->logout($user->getEmail());
}else{
    $accessController->destroy();
}
?>
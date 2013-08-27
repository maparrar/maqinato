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
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('models').'core/User.php';
//Close the session if the email is passed
if(AccessController::checkSession()){
    $user=new User();
    $user=unserialize($_SESSION['user']);
    $access = new AccessController();
    $access->logout($user->getEmail(),$user->getSessionId());
}else{
    AccessController::destroy();
}
?>
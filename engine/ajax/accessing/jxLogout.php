<?php
/**
 * jxLogout ajax File
 *
 * @author bonfolio.co
 * @author Alejandro Parra <alejandro.parra@bonfolio.co> - @author Juan Cárdenas <juan.cardenas@bonfolio.co>
 * @package web
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
<?php
/**
 * jxSendValidationEmail ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'CommunicationController.php';
$user=AccessController::getSessionUser();
if($user){
    $sended=CommunicationController::sendValidationEmail($user);
    if($sended){
        $response='{"response":"success"}';
    }
}else{
    $response='{"response":"not user"}';
}
echo $response;
?>
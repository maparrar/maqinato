<?php
/**
 * jxCommentSave ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'NonprofitController.php';

$email=$_POST["email"];//error_log(serialize($tags));
$response=CommunicationController::mailRecoveryPassword($email);
if($response){
echo '{"recovery": true}';
}else{
echo '{"recovery": false}';    
};
?>
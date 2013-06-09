<?php
/**
 * jxSignup ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'SocialController.php';
$email=array_key_exists('email',$_POST)?$_POST['email']:'';
$message=$_POST['message'];
$success=CommunicationController::contactUs($email,$message);
if($success){
    echo '{"response":"success"}';
}else{
    echo '{"response":"fail"}';
}
?>
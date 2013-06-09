<?php
/**
 * jxSignupReserve ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'SocialController.php';
$name=$_POST['name'];
$lastname=$_POST['lastname'];
$sex=$_POST['sex'];
$email=$_POST['email'];
$city=$_POST['city'];
$password=$_POST['password'];
$accessController = new AccessController();
$response=$accessController->signup($name,$lastname,$sex,$email,$city,$password,true);
//Auto-frienship with the maqinato Team
$sessionUser=AccessController::getSessionUser();
if($sessionUser&&$response){
    SocialController::teamFriends($sessionUser->getId());
    CommunicationController::sendReserveAccountEmail($sessionUser);
    $accessController->logout();
}
echo $response;
?>
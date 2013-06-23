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
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'SocialController.php';
$name=$_POST['name'];
$lastname=$_POST['lastname'];
$sex=$_POST['sex'];
$email=$_POST['email'];
$city=$_POST['city'];
$idCity=$_POST['idCity'];
$country=$_POST['country'];
if($idCity==""){
    $idCity=DonationController::newCity($country, $city);
}
error_log($idCity);
$password=$_POST['password'];
$accessController = new AccessController();
//Se hace el registro
$response=$accessController->signup($name,$lastname,$sex,$email,$idCity,$password);
//Si se registra con éxito, se acepta la invitación
if($response==="logged"){
    $invitedEmail=SecurityController::sanitizeString($_SESSION["invitationData"]["email"]);
    $invitedKey=SecurityController::sanitizeString($_SESSION["invitationData"]["key"]);
    //Verifica que la clave sea válida y guarda los puntos correspondientes
    SocialController::verifyInvitation($invitedEmail,$invitedKey);
}
//Elimina la variable de sesión de la invitación
unset($_SESSION["invitationData"]);
    
//Auto-frienship with the maqinato Team
$sessionUser=AccessController::getSessionUser();
if($sessionUser&&$response){
    SocialController::teamFriends($sessionUser->getId());
}
echo $response;
?>
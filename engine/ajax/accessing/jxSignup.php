<?php
/**
 * jxSignup ajax File
 *
 * @author bonfolio.co
 * @author Alejandro Parra <alejandro.parra@bonfolio.co> - @author Juan Cárdenas <juan.cardenas@bonfolio.co>
 * @package web
 * @subpackage ajax
 */


Maqinato::debug("Mensaje desde ajax");


//session_start();
//if(!class_exists('Router')) require_once '../../../config/Router.php';
//include_once Router::rel('controllers').'AccessController.php';
//include_once Router::rel('controllers').'SocialController.php';
//$name=$_POST['name'];
//$lastname=$_POST['lastname'];
//$sex=$_POST['sex'];
//$email=$_POST['email'];
//$city=$_POST['city'];
//$idCity=$_POST['idCity'];
//$country=$_POST['country'];
//$provider=$_POST['provider'];
//$providerId=$_POST['providerId'];
//$providerToken1=$_POST['token1'];
//if($idCity==""){
//    $idCity=DonationController::newCity($country, $city);
//}
//$password=$_POST['password'];
//$accessController = new AccessController();
////Se hace el registro
//$response=$accessController->signup($name,$lastname,$sex,$email,$idCity,$password,false,$provider,$providerId,$providerToken1);
////Si se registra con éxito, se acepta la invitación
//if($response==="logged"){
//    $invitedEmail=SecurityController::sanitizeString($_SESSION["invitationData"]["email"]);
//    $invitedKey=SecurityController::sanitizeString($_SESSION["invitationData"]["key"]);
//    //Verifica que la clave sea válida y guarda los puntos correspondientes
//    SocialController::verifyInvitation($invitedEmail,$invitedKey);
//}
////Elimina la variable de sesión de la invitación
//unset($_SESSION["invitationData"]);
//    
////Auto-frienship with the bonfolio Team
//$sessionUser=AccessController::getSessionUser();
//if($sessionUser&&$response){
//    SocialController::teamFriends($sessionUser->getId());
//}
//echo $response;
?>
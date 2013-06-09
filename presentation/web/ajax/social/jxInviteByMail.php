<?php
/**
 * jxInvite ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'SocialController.php';
if(array_key_exists("valid",$_POST)){
    $valid=$_POST["valid"];
    $invalid=$_POST["invalid"];
    foreach ($valid as $email){
        $email=SecurityController::sanitizeString($email);
        //Verificar que no existan
        $user=SocialController::getUser($email);
        if($user){
            $invalid[]=$user->getEmail();
        }else{
            //Enviar el email
            $inviter=AccessController::getSessionUser();
            //Almacena la invitación en la base de datos
            $invitation=SocialController::storeInvitation($inviter,$email);
            if(!CommunicationController::sendInviteEmail($inviter,$invitation)){
                $invalid[]=$user->getEmail();
            }
        }
    }
}
echo '{"invalid":'.json_encode($invalid).'}';
?>
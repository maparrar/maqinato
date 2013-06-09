<?php
/**
 * jxNotificationsLoad ajax File
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
$user=AccessController::getSessionUser();
$list=SocialController::getNotifications($user->getId());
$notifications=filter_var(SocialController::htmlNotifications($list),FILTER_SANITIZE_MAGIC_QUOTES);
//Indica al sistema si debe recargar el newsfeed, en este caso para cargar las actividades del nuevo amigo
$reloadNewsfeed="false";
foreach ($list as $notif){
    if($notif->getParameter("type")=="received"){
        $reloadNewsfeed="true";
    }
}
echo '{"notifications":"'.$notifications.'","reloadNewsfeed":"'.$reloadNewsfeed.'"}';
?>
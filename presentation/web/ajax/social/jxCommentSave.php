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
include_once Router::rel('controllers').'ActivityController.php';
$comment=$_POST["comment"];
$activityId=intval($_POST["activityId"]);
$toUser=intval($_POST["toUser"]);
$response=SocialController::saveComment($comment,$activityId,$toUser);
$response->setDate(ActivityController::timeAgo($response->getDate()));
$commentHtml=filter_var(ActivityController::htmlComment($response),FILTER_SANITIZE_MAGIC_QUOTES);
echo '{"id":'.$response->getId().',"html":"'.$commentHtml.'"}';
?>
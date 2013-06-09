<?php
/**
 * jxToggleLikeActivity ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'ActivityController.php';
include_once Router::rel('controllers').'SocialController.php';
$activityId=intval($_POST["activityId"]);
$activity=ActivityController::getActivity($activityId);
$response=SocialController::toggleLikeActivity($activity);
echo '{"likeToggled":"'.$response.'"}';
?>
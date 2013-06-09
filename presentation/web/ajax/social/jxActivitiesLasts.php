<?php
/**
 * jxActivitiesLast ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'ActivityController.php';
$lastClientActivity=intval($_POST["lastClientActivity"]);
$user=AccessController::getSessionUser();
$list=ActivityController::userLastActivities($user,$lastClientActivity);
$htmlActivities=filter_var(ActivityController::htmlActivities($list),FILTER_SANITIZE_MAGIC_QUOTES);
$lastActivity=ActivityController::userLastActivity($user);
echo '{"activities":"'.$htmlActivities.'","lastActivity":'.$lastActivity->getId().'}';
?>
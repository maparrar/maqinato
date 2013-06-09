<?php
/**
 * jxActivityGet ajax File
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
$activityId=intval($_POST["activityId"]);
$activity=ActivityController::getActivity($activityId);
if($activity){
    $htmlActivity=str_replace("'","´",ActivityController::htmlActivities(array($activity)));
    $htmlActivity=filter_var($htmlActivity,FILTER_SANITIZE_MAGIC_QUOTES);
}else{
    $htmlActivity='false';
}
echo '{"activity":"'.$htmlActivity.'"}';


?>
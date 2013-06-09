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
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'ActivityController.php';
$refActivity=intval($_POST["refActivity"]);
$direction=intval($_POST["direction"]);
$filter=$_POST["filter"];
$folio=$_POST["folio"] === 'false'? false: intval($_POST["folio"]);
$profileId=$_POST["profileId"] === 'false'? false: intval($_POST["profileId"]);
if(array_key_exists("tagsFilter",$_POST)){
    $tagsFilter=$_POST["tagsFilter"];
}else{
    $tagsFilter=array();
}
$user=AccessController::getSessionUser();
$byRefMin=0;
$byRefMax=0;
$list=ActivityController::userLoadActivities($user,$refActivity,Config::$activitiesLoadScroll,$direction,$filter,$tagsFilter,$byRefMin,$byRefMax,$folio,$profileId);
if(count($list)>0){
    $htmlActivities=str_replace("'","´",ActivityController::htmlActivities($list));
    $htmlActivities=filter_var($htmlActivities,FILTER_SANITIZE_MAGIC_QUOTES);
    $htmlActivities=str_replace("\n","<br/>",$htmlActivities);
    $response='{"activities":"'.$htmlActivities.'","min":'.$byRefMin.',"max":'.$byRefMax.'}';
}else{
    $response='{"activities":0}';
}
echo $response;

?>
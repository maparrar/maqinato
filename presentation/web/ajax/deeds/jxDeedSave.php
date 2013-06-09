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
include_once Router::rel('models').'donation/Deed.php';
include_once Router::rel('models').'dal/donation/DaoDeed.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'ActivityController.php';
include_once Router::rel('controllers').'FolioController.php';
$tagsList=$_POST["tags"];
$content=$_POST["string"];
$mediaName=$_POST["media"];
$type=$_POST["type"];
$folioId=$_POST["folio"]==='false'? false: intval($_POST["folio"]);
$daoTag=new DaoTag();

$tags=array();
$user=AccessController::getSessionUser();

//Load the Tags
foreach ($tagsList as $tagList) {
    array_push($tags,$daoTag->read($tagList));
}
//Crea el bondeed y retorna la actividad
$activity=SocialController::makeBondeed($content,$user,$tags,$mediaName,$folioId);

$response='{"created":"true"}';
if(!$activity){
    $response='{"created":"false"}';
}
echo $response;
?>
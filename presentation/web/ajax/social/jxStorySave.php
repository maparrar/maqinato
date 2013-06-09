<?php
/**
 * jxStorySave ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'ActivityController.php';
include_once Router::rel('controllers').'FolioController.php';
include_once Router::rel('controllers').'SocialController.php';

$title=$_POST["title"];
$url=$_POST["url"];
$description=$_POST["description"];
$content=$_POST["content"];
$image=false;
if(array_key_exists("image",$_POST)){
    $image=$_POST["image"];
}
$folioId=$_POST["folio"]==='false'? false: intval($_POST["folio"]);
//Guarda la historia usando el controlador
$story=SocialController::makeStory($title,$url,$description,$content,$image,$folioId);

if($story){
    $response='{"story":"true"}';
}else{
    $response='{"story":"false"}';
}
echo $response;
?>
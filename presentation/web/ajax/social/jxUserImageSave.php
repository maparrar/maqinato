<?php
/**
 * jxUserImageSave ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'SocialController.php';
$mediaName=$_POST["media"];
//Copia las imágenes del usuario actual
$name=SocialController::saveUserImage($mediaName);
$response='{"image":"'.Router::img('users/images/'.$name).'","thumbnail":"'.Router::img('users/thumbnails/'.$name).'"}';
echo $response;
?>
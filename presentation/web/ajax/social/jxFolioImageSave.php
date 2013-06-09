<?php
/**
 * jxFolioImageSave ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
$folioId=$_POST["folioId"];
$mediaName=$_POST["media"];
//Move the temporal image
if(trim($mediaName)!="false"){
    Router::copyFile("temp/".$mediaName,'folios/'.$folioId.'.jpg');
    Router::deleteFile("temp/".$mediaName);
}
$response='{"path":"'.Router::img('folios/'.$folioId.'.jpg').'"}';
echo $response;
?>
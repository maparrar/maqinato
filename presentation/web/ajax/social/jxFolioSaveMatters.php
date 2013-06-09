<?php
/**
 * jxFolioSaveMatters ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
//include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'FolioController.php';
$folioId=intval($_POST["folioId"]);
$matters=$_POST["matters"];
$folio=FolioController::readFolio($folioId);
$response=FolioController::saveMatters($folio,$matters);
if($response){
    $response='{"saved":"true"}';
}else{
    $response='{"saved":"false"}';
}
echo $response;
?>
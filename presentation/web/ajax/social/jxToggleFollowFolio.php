<?php
/**
 * jxToggleFollowFolio ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'FolioController.php';
include_once Router::rel('controllers').'SocialController.php';
$folioId=intval($_POST["folioId"]);
$folio=FolioController::readFolio($folioId);
$response=FolioController::toggleFollow($folio);
echo '{"followingFolio":"'.$response.'"}';
?>
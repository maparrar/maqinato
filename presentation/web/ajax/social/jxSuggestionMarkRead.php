<?php
/**
 * jxNotificationsMarkRead ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'SocialController.php';
$response=SocialController::readSuggestion(intval($_POST["suggestionId"]));
echo '{"markReaded":"'.$response.'"}';
?>
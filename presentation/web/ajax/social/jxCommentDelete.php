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
include_once Router::rel('controllers').'SocialController.php';
$commentId=intval($_POST["commentId"]);
$response=SocialController::deleteComment($commentId);
if($response){
    echo '{"deleted":"success"}';
}else{
    echo '{"deleted":"fail"}';
}
?>
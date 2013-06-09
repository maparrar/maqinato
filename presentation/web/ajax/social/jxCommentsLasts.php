<?php
/**
 * jxCommentsLasts ajax File
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
$comments=ActivityController::newComments($activityId);
$total=ActivityController::totalComments($activityId);
$response="[";
foreach ($comments as $comment) {
    $commencode='{"id":'.$comment->getId().',"html":';
    $commencode.='"'.filter_var(ActivityController::htmlComment($comment),FILTER_SANITIZE_MAGIC_QUOTES).'"}';
    $response.=$commencode.',';
}
if($comments){
    //Remove the last comma
    $response=substr($response,0,-1);
}
$response.="]";
echo '{"total":'.$total.',"comments":'.$response.'}';
?>
<?php
/**
 * jxFriendsSearch ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'SocialController.php';
$keyword=$_POST['keyword'];
$list=SocialController::searchUsers($keyword,10);
if(count($list)){
    $htmlUsers=filter_var(SocialController::htmlUsersSearch($list),FILTER_SANITIZE_MAGIC_QUOTES);
    $response='{"users":"'.$htmlUsers.'"}';
}else{
    $response='{"users":0}';
}
echo $response;
?>
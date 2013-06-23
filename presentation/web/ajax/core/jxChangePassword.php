<?php
/**
 * jxLogin ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
if($_POST['lastPwr']){
    $lastPwr=$_POST['lastPwr'];
}else{
    $lastPwr=$_POST['key'];
}

$newPwr=$_POST['newPwr'];
$email=$_POST['email'];
$access = new AccessController();
$updated=$access->passwordChange($lastPwr,$newPwr,$email);
$response='{"updated":"'.$updated.'"}';
echo $response;
?>
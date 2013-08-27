<?php
/** jxRefreshSession File
 * @package controllers @subpackage core */
/**
 * jxRefreshSession
 *
 * Actualiza la sesión y genera un nuevo id en cada intento
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package engine
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
$email=$_POST['email'];
$password=$_POST['password'];
$keep=false;
if($_POST['keep']==="true"){
    $keep=true;
}
$access = new AccessController();
$response=$access->login($email,$password,$keep);
echo $response;
?>
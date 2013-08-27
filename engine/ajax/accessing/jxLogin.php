<?php
/**
 * jxLogin ajax File
 *
 * @author bonfolio.co
 * @author Alejandro Parra <alejandro.parra@bonfolio.co> - @author Juan Cárdenas <juan.cardenas@bonfolio.co>
 * @package web
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
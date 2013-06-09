<?php
/**
 * jxSignup ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
$name=$_POST['name'];
$responsible=$_POST['responsible'];
$email=$_POST['email'];
$password=$_POST['password'];
$accessController = new AccessController();
$response=$accessController->signupNop($name,$responsible,$email,$password);
echo $response;
?>
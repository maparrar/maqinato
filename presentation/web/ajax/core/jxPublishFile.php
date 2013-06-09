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
if(!class_exists('Router')) require_once '../../../config/Router.php';
$file=$_POST['file'];
$ext=pathinfo($file,PATHINFO_EXTENSION);
$fileName=Router::publishFile($file,uniqid().".".$ext);
echo '{"published":"'.$fileName.'"}';
?>
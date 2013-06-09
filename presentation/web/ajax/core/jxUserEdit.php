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
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('models').'dal/donation/DaoCountry.php';
$daoCity=new DaoCity();
$name=$_POST['name'];
$lastname=$_POST['lastname'];
$born=$_POST['born'];
$sex=$_POST['sex'];
$iam=$_POST['iam'];
$city=intval($_POST['city']);
$updated=AccessController::updateUser($name,$lastname,$born,$sex,$iam,$city);
if($updated){
    $objCountry=$daoCity->getIdCountry($city);
    $objeCountry=$daoCity->listing($objCountry);
    $response='{"updated":"success","country":"'.$objeCountry.'"}';
}else{
    $response='{"updated":"fail"}';
}
echo $response;
?>
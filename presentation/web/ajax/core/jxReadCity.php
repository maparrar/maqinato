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
include_once Router::rel('controllers').'DonationController.php';
$country=$_POST['country'];
$city=ucwords(strtolower($_POST['city']));
$cities=DonationController::citiesList($country,$city);
$listCities=null;
foreach ($cities as $city) {
    $listCities.= '<p value=\"'.$city->getId().'\" class=\"thisCity\">'.ucwords(strtolower($city->getName())).'</p>';
}
$response='{"listCities":"'.$listCities.'"}';
echo $response;


?> 
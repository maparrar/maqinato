<?php
/**
 * jxPoints ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('models').'donation/Combination.php';
include_once Router::rel('controllers').'DonationController.php';
include_once Router::rel('controllers').'ActivityController.php';
@$amount=floatval($_POST['amount']);
@$tagsJson=json_decode($_POST['tags']);
@$reprntId=intval($_POST['reprntId']);
$tags=DonationController::tagsFromJson($tagsJson);
$points=DonationController::points($amount,$tags,$reprntId);
echo '{"points":'.json_encode($points).'}';
?>
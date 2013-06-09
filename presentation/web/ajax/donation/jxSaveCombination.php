<?php
/**
 * jxSaveCombination ajax File
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
include_once Router::rel('controllers').'FolioController.php';
$data=json_decode(json_encode($_POST['data']));
$folioId=$data->folio===false? false: intval($data->folio);
$slices=$data->slices;
$image=$data->image;
$response='{"created":"false"}';

//Crea la nueva combinación
$combination=DonationController::createCombinationFromSlices($slices,$image);

//Make the donations (set of givings) and create the Activity
if(SecurityController::isclass($combination,"Combination")){
    $folio=FolioController::readFolio($folioId);
    FolioController::replaceCombination($folio,$combination);
    $response='{"created":"true"}';
}else{
    $response='{"type":"error","description":"Failed replacing combination"}';
}
echo $response;
?>
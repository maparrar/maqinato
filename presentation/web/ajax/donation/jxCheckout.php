<?php
/**
 * jxCheckout ajax File
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
$tags=DonationController::tagsFromJson(@$tagsJson);

//Return the points array and the $tags with points by reference
$points=DonationController::points($amount,$tags,$reprntId);

//Search the organizations to donate
DonationController::organizationsFromTags($tags);
$json='{"points":'.json_encode($points).',';
if(count($tags)){
//    DonationController::badgesOfTags($tags);
    $json.='"tags":[';
    foreach ($tags as $tag) {
        $json.=$tag->jsonEncode().',';
    }
    //Remove the last comma
    $json=substr($json,0,-1);
    $json.="]";
}else{
    $json.='"tags":""';
}
echo $json.'}';
?>
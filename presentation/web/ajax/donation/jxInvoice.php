<?php
/**
 * jxCongratulationsEmail ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'CommunicationController.php';
$data=$_POST['data'];
$setId=intval($data["setId"]);
$paymentId=intval($data["paymentId"]);
$email=($data["email"] === 'true');
$pdf=($data["pdf"] === 'true');
$print=($data["print"] === 'true');
$user=AccessController::getSessionUser();
$response="fail";
if($email){
    if(CommunicationController::sendInvoice($user,$setId,$paymentId)){
        $response="success";
    }
}
echo '{"invoice":"'.$response.'"}';
?>
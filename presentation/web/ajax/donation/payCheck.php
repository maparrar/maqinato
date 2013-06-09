<?php
/**
 * paypalCheck File
 * Sirve para verificar los pagos realizados a Paypal, usando cuentas de Paypal
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'PaymentController.php';
$success=false;
if(isset($_GET['success']) && $_GET['success'] == 'true'){
    $success=true;
}
$paymentId=$_SESSION['paymentId'];
$payerId=isset($_GET['PayerID'])?$_GET['PayerID']:false;
PaymentController::checkPaypal($success,$paymentId,$payerId);
?>
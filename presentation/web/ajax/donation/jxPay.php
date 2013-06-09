<?php
/**
 * paypalPay File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'PaymentController.php';

//Guarda los datos del SET mientras realiza la operación en PayPal
$_SESSION["pendingSet"]=$_POST["data"];
//Retorna los datos almacenados en la sesión
$data=json_decode($_SESSION["pendingSet"]);
//Ruta a la que se debe redireccionar cuando termine la transacción
$redirectTo=Router::serverUrl()."/".Router::application()."/views/giving/index.php?";
if(property_exists($data,"addToFolio")){
    $redirectTo=Router::serverUrl()."/".Router::application()."/views/folio/index.php?folio=".$data->addToFolio."&";
}
$_SESSION["redirectTo"]=$redirectTo;
//Obtiene el usuario de la sesión y verifica que sea correcto
$user=AccessController::getSessionUser();
$token=array_key_exists("token",$_POST)?$_POST["token"]:false;
$agent=$_POST["agent"];
$method=$_POST["method"];
$amount=floatval($_POST["amount"]);
//Se almacenan los datos de pago en la sesión
$_SESSION['paymentAgent']=$agent;
$_SESSION['paymentMethod']=$method;

/************************* SISTEMA DE PAGO CON STRIPE *************************/
if($agent==="stripe"){
    $response=PaymentController::payStripe($token,$user,$amount,$method);
}
if($response){
    echo '{"response":"success","url":"'.$response.'"}';
}else{
    echo '{"response":"fail"}';
}

/*************** SISTEMA DE PAGO DE PRUEBAS, SIN NINGÚN AGENTE ****************/
//PaymentController::payDebug($user,$amount,$method,$methodDetails);

/************ ACTIVAR CUANDO SE VUELVAN A RECIBIR PAGOS DE PAYPAL *************/
//Selecciona la forma de pago de acuerdo a los parámetros pasados
//if($agent==="paypal"){
//    if($method==="paypal"){
//        PaymentController::payPaypal($user,$amount);
//    }elseif($method==="visa"||$method==="master"||$method==="amex"||$method==="discover"){
//        $methodDetails=json_decode($_POST["methodDetails"]);
//        if(!PaymentController::payPaypalCredit($user,$amount,$method,$methodDetails)){
//            
//            header("Location: ".Router::rel('views')."giving/index.php?payment=false&amount=".$amount);
//        }
//    }
//}else{
//    error_log("Bank Agent");
//}

?>
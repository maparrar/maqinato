<?php
/**
 * jxWithoutPay File
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

/****************************** SISTEMA SIN PAGO ******************************/
$response=false;
if($user->getId()===1){
    $response=PaymentController::WithoutPay($user);
}
if($response){
    echo '{"response":"success","folio":"'.$response.'"}';
}else{
    echo '{"response":"fail"}';
}

/*************** SISTEMA DE PAGO DE PRUEBAS, SIN NINGÚN AGENTE ****************/
//PaymentController::payDebug($user,$amount,$method,$methodDetails);

?>
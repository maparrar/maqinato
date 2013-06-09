<?php
/**
 * jxFolioSaveMatters ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
//include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'FolioController.php';
$folioId=intval($_POST["folioId"]);
$folio=FolioController::readFolio($folioId);
if($folio){
    //Retorna el amount total de los giving en el folio
    $amount=number_format(FolioController::getAmountFolio($folio));
    $points=number_format($folio->getPoints());
    $toDistribute=number_format($folio->getToDistribute());
    $nextDate=" july 12 2013";
    $response='{
        "amount":"'.$amount.'",
        "points":"'.$points.'",
        "toDistribute":"'.$toDistribute.'",
        "nextDate":"'.$nextDate.'"
    }';
}else{
    $response='{"loaded":"false"}';
}
echo $response;
?>
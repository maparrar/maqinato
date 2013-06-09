<?php
/**
 * jxCommentSave ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'NonprofitController.php';
$ein=intval($_POST["ein"]);
$name=$_POST["name"];
$mail=$_POST["mail"];
$contact=$_POST["contact"];
$phone=$_POST["phone"];
$contactEmail=$_POST["contactEmail"];
$bankName=$_POST["bankName"];
$routerSwitch=$_POST["routerSwitch"];
$bankNumberAccount=$_POST["bankNumberAccount"];
$paypalEmail=$_POST["paypalEmail"];
$preferredMethod=intval($_POST["preferredMethod"]);
$tags=$_POST["tags"];

//error_log(serialize($tags));
$response=NonprofitController::saveNonprofit($ein,$name,$mail,$contact,$phone,$contactEmail,$bankName,$routerSwitch,$bankNumberAccount,$paypalEmail,$preferredMethod,$tags);
$sended=CommunicationController::sendAcceptanceNonprofit($response);
////$response->setDate(ActivityController::timeAgo($response->getDate()));
//$commentHtml=filter_var(ActivityController::htmlComment($response),FILTER_SANITIZE_MAGIC_QUOTES);
if(SecurityController::isclass($response,"Nonprofit")){
echo '{"nonprofit":"true"}';
}else{
echo '{"nonprofit":"false"}';    
};
?>
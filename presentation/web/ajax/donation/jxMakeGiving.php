<?php
/**
 * jxCombinationCreate ajax File
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
include_once Router::rel('controllers').'PaymentController.php';
$data=$_POST['data'];
$slices=$data["slices"];
$amount=floatval($data["amount"]);
$points=$data["points"];        //Array of footpoints
$image=$data["image"];
$reprntId=intval($data["reprntId"]);
$shareAmount=$data["shareAmount"];
$shareFacebook=$data["shareFacebook"];
$shareTwitter=$data["shareTwitter"];
$createFolio=$data["createFolio"] === 'true'? true: false;
$folioName=$data["folioName"];
//Indica si se debe asociar este giving al folio
$addToFolio=false;
if(array_key_exists("addToFolio",$data)){
    $addToFolio=$data["addToFolio"]==='false'? false: intval($data["addToFolio"]);
}
$response='{"created":"false"}';
//Load or saved the combination
if($reprntId){
    $combination=DonationController::getCombination($reprntId);
    DonationController::loadJSONSlicesToCombination($slices,$combination);
}elseif($addToFolio){
    $combinationId=$data["combinationId"]==='false'? false: intval($data["combinationId"]);
    $combination=DonationController::getCombination($combinationId);
    DonationController::loadJSONSlicesToCombination($slices,$combination);
}else{
    $combination=DonationController::createCombinationFromSlices($slices,$image);
}
//Make the donations (set of givings) and create the Activity
if(get_class($combination)=="Combination"){
    $user=AccessController::getSessionUser();
    $set=DonationController::makeSet($combination,$amount,$points,$reprntId);
    
    $amountVisible=$amount;
    if($shareAmount=="false"){
        $amountVisible=false;
    }
    //Save the activity in the database and return it to send to browser
    $activity=ActivityController::activityFromSet($amountVisible,$set,$combination,$points,$reprntId);
    //Crea el folio si se requiere y lo asocia a la actividad
    if($createFolio){
        $folio=FolioController::createFolio($folioName,$combination->getId(),$activity->getId(),$points["total"],$set->getId());
    }
    //Si $addToFolio=[entero] indica que es un giving hecho desde la página de un
    //folio y la actividad debe ser asociada al folio de la variable $addToFolio
    if($addToFolio){
        $folio=FolioController::readFolio($addToFolio);
        FolioController::addActivityToFolio($folio,array($activity->getId()));
        FolioController::setFolioPoints($user->getId(),$folio,"giving",intval($points["total"]),$set->getId());
        //Se hace seguidor del folio automáticamente
        FolioController::toggleFollow($folio);
    }
    //If is reprnt, send the notification to the creator
    if($reprntId){
        //Create the notification
        $notification=new Notification();
        $notification->setType("reprnt");
        $notification->addParameter("user",$user->getId());
        $notification->addParameter("activityId",$activity->getId());
        $notification->addParameter("points",$points["reprnt"]);
        $notification->setRecipient($combination->getCreator());
        SocialController::sendNotification($notification,array(SocialController::getUser("",$combination->getCreator())));
    }
    //Check if the user get new Badges
    $newBadges=DonationController::userNewBadges($user->getId());
    if(count($newBadges)>0){
        foreach ($newBadges as $badge) {
            //Save the activity in the database and return it to send to browser
            $activity=ActivityController::activityFromBadge($badge);
            //Create the notification
            $notification=new Notification();
            $notification->setType("badge");
            $notification->addParameter("user",$user->getId());
            $notification->addParameter("activityId",$activity->getId());
            $notification->addParameter("badgeId",$badge->getId());
            $notification->setRecipient($user->getId());
            SocialController::sendNotification($notification,array($user));  
        }
    }
    $response='{"created":"true"}';
}else{
    $response='{"type":"error","description":"Failed creating giving"}';
}
echo $response;
?>
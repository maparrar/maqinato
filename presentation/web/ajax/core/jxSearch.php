<?php
/**
 * jxSearch ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'SocialController.php';
$keyword=$_POST['keyword'];
$users=SocialController::searchUsers($keyword,Config::$searchMaxUsers);
//Calcula la imagen para cada usuario
foreach ($users as $user) {
    $user->setImage(Router::img("users/thumbnails/".$user->getId().".jpg"));
}
$tags=DonationController::searchTags($keyword,Config::$searchMaxTags,1);
$nonprofits=DonationController::searchTags($keyword,Config::$searchMaxTags,4);
foreach($nonprofits as $nonprofit){
    $nonprofit->getOrganization()->setLogo(Router::img("nonprofit/".$nonprofit->getOrganization()->getLogo().".jpg"));
}
$response='{'.
    Router::arrayToJson($users,"users").','.
    Router::arrayToJson($tags,"tags").','.
    Router::arrayToJson($nonprofits,"nonprofits").
'}';
echo $response;
?>
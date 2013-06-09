<?php
/**
 * jxSearchTags ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'DonationController.php';
$keyword=$_POST['keyword'];
$list=DonationController::searchTags($keyword);
//Ordena la lista por tipo, para mostrar de últimas los tags de organización
usort($list,function($a,$b){
    return ($a->getType() < $b->getType()) ? -1 : 1;
});
$json="{";
//Add tags
if(count($list)){
    $json.='"tags":[';
    foreach ($list as $tag) {
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
<?php

/**
 * jxCropImage ajax File
 * Corta una imagen de acuerdo a los datos recibidos, la guarda en tmp, elimina 
 * la imagen original y retorna la ruta de la imagen cortada
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'MediaController.php';

//Recibe las variables
$srcName=$_POST["srcName"];
$srcX=$_POST["srcX"];
$srcY=$_POST["srcY"];
$srcH=$_POST["srcH"];
$srcW=$_POST["srcW"];
$dstH=$_POST["dstH"];
$dstW=$_POST["dstW"];
$previous=$_POST["previous"];

//Retorna la ruta de la imagen del sistema de archivos
$srcPath=Router::img("temp/".$srcName);

//Descarga la imagen y la guarda en el tmp del sistema
$user=AccessController::getSessionUser();
$srcExt=pathinfo($srcName,PATHINFO_EXTENSION);
$srcTempPath=sys_get_temp_dir().'/'.$user->getId()."_".uniqid().".".$srcExt;
file_put_contents($srcTempPath,file_get_contents($srcPath));

//Calcula tamaños y crea los objetos de imagen source
$srcImage=MediaController::gdImage($srcTempPath);
//Calcula tamaños y crea los objetos de imagen destiny
$dstImage=imagecreatetruecolor($dstW,$dstH);

//Elimina la imagen original si existe
if(trim($previous)!=""){
    Router::deleteFile("temp/".$previous);
}

//Corta la imagen original
imagecopyresampled($dstImage,$srcImage,0,0,$srcX,$srcY,$dstW,$dstH,$srcW,$srcH);

//Guarda la nueva imagen en la carpeta temporal
$dstName=$user->getId()."_".uniqid().".png";
$dstTempPath=sys_get_temp_dir().'/'.$dstName;
MediaController::saveImageJpeg($dstTempPath,$dstImage,Config::$deedsQuailty);

//Sube el archivo al repositorio temporal de archivos del sistema
$destination="temp/".$dstName;
if(Router::saveFile($dstTempPath,$destination)){
    //Retorna la URL de la imagen para mostrarla
    $url=Router::img($destination);
    $response='{"type":"Success","cropped":"'.$url.'","name":"'.$dstName.'"}';
}else{
    $response='{"type":"Error","name":"unknow","description":"Unknow error"}';
}

//Elimina la imagen original del temp del sistema y del temp de la aplicación
unlink($srcTempPath);
Router::deleteFile("temp/".$srcName);


//Retorna el nombre de la nueva imagen
echo $response
?>

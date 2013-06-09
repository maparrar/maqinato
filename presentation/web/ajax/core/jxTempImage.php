<?php

/**
 * jxTempImage ajax File
 * Sube una imagen a la carpeta temporal del sistema y retorna su nombre
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
//Max file size in bytes: 1.000.000B=1.000KB=1MB
$maxSizeFile=Config::$maxSizeDeedImage*1000000;
//Verify the formats
$allowedExts=Config::$allowedImages;
$extension = end(explode(".", $_FILES["file"]["name"]));
if ((($_FILES["file"]["type"] == "image/gif")||($_FILES["file"]["type"] == "image/png")||($_FILES["file"]["type"] == "image/jpeg")||($_FILES["file"]["type"] == "image/pjpeg"))&&in_array($extension, $allowedExts)){
    //Verify the size
    if($_FILES["file"]["size"]<$maxSizeFile){
        //Verify file errors
        if ($_FILES["file"]["error"] <= 0) {
            $user=AccessController::getSessionUser();
            //Obtiene el tamaño del archivo original
            $size=getimagesize($_FILES["file"]["tmp_name"]);
            $width=$size[0];
            $height=$size[1];
            //Define paths and filenames
            $response=false;
            //Mueve el archivo a la carpeta temporal de maqinato
            $source=$_FILES["file"]["tmp_name"];
            $ext=pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
            $newName=$user->getId()."_".uniqid().".".$ext;
            $destination="temp/".$newName;
            if(Router::saveUploadedFile($source,$destination)){
                //Retorna la URL de la imagen para mostrarla
                $url=Router::img($destination);
                $response='{"upload":"success","name":"'.$newName.'","url":"'.$url.'","width":'.$width.',"height":'.$height.'}';
            }else{
                $response='{"type":"Error","name":"unknow","description":"Unknow error"}';
            }
        }else{
            $response='{"type":"Error","name":"unknow","description":"Unknow error"}';
        }
    }else{
        $response='{"type":"Error","name":"maxSize","description":"The size of the file is over the max value ['.Config::$maxSizeDeedImage.' MB]"}';
    }
} else {
    $response='{"type":"Error","name":"formatImage","description":"This image is not supported, please use: jpg,jpeg,gif,png"}';
}
echo $response;
?>

<?php
/**
 * cron File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package transactions
 * @subpackage cron
 */
session_start();
//Obtiene el directorio raíz de la aplicación
$start= strpos($argv[0],"transactions");
$current=substr($argv[0],0,$start);
$retrievedKey=$argv[1];
$autorizedKey="33217625920c9441dcf583036d2314b382ae75fa8732ae4fa87ffb8f168ba387";

require_once $current.'config/Connection.php';
require_once $current.'config/S3.php';
require_once $current.'config/Router.php';
require_once $current.'controllers/CommunicationController.php';
require_once $current.'controllers/CronController.php';
require_once $current.'controllers/SecurityController.php';
require_once $current.'controllers/SocialController.php';
require_once $current.'models/dal/Dao.php';
require_once $current.'models/dal/Database.php';
require_once $current.'models/dal/core/DaoUser.php';
require_once $current.'models/dal/core/DaoUserType.php';
require_once $current.'models/dal/donation/DaoCity.php';
require_once $current.'models/core/Object.php';
require_once $current.'models/core/User.php';
require_once $current.'models/core/UserType.php';
require_once $current.'vendors/mandrill/Mandrill.php';

//Verifica que la clave del cron sea la misma
if($retrievedKey===$autorizedKey){
    //Si encuentra el directorio raiz de la aplicación
    if($current){
        //Activa los usuarios que no hayan sido activados
//        CronController::activateWaitingUsers();
    }
}else{
    CommunicationController::errorCronKey();
}
?>
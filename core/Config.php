<?php
/** Config File
 * @package config */
@session_start();
/**
 * Config Class
 * All configuration data for the application
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package config
 */
class Config{
    
/******************************************************************************/
/*********************************** ROUTING **********************************/
/******************************************************************************/
    /** Application name
     * @var string
     */
    public static $application="maqinato";
    
    /**
     * Protocolo usado por maqinato
     */
    public static $protocol="http";
    
    /** Array with the possible server names
     * @var mixed
     */
    public static $servers=array(
        "development"=>array(
            "localhost",
            "127.0.0.1",
            "10.0.2.2",
            "10.0.0.102"
        ),
        "testing"=>array(
            "www.testing.server",
            "www.other.testing.server",
            "02.02.02.02"
        ),
        "release"=>array(
            "www.release.candidate.server",
            "www.other.release.candidate.server",
            "01.01.01.01"
        ),
        "production"=>array(
            "www.production.server.com",
            "www.other.production.server.com",
            "production.server.com",
            "other.production.server.com",
            "00.00.00.00"
        )
    );
    
    /**
     * Array of JS scripts to use in the application.
     * Search in {application}/web/js/ 
     * @var mixed[]
     */
    public static $jsScripts=array(
        "jquery"                =>  "js/jquery/jquery-1.8.3.min.js",
        "jqueryui"              =>  "js/jquery/jquery-ui-1.9.2.custom.min.js",
        "jqueryvalidate"        =>  "js/jquery/jquery-validate.min.js",
        "jquerymobile"          =>  "js/jquery/jquerymobile.min.js",
        
        "system"                =>  "js/system/System.js",
        "ajax"                  =>  "js/system/ajax/ajax.min.js",
        "ajaxCore"              =>  "js/system/ajax/AjaxCore.js",
        "ajaxSocial"            =>  "js/system/ajax/AjaxSocial.js",
        "access"                =>  "js/system/components/Access.js",
        "daemons"               =>  "js/system/components/Daemons.js",
        "landing"               =>  "js/system/components/Landing.js",
        "home"                  =>  "js/system/components/Home.js",
        "security"              =>  "js/system/components/Security.js",
        "settings"              =>  "js/system/components/Settings.js",
        "tools"                 =>  "js/system/components/Tools.js",
        "uploader"              =>  "js/system/components/Uploader.js"
    );
    
    
    /**
     * Array of css scripts to use in the application.
     * Search in {application}/web/css/
     * @var mixed[]
     */
    public static $cssScripts=array(
        "jqueryui"              =>  "css/jquery/smoothness/jquery-ui-1.9.2.custom.min.css"
        
    );

/******************************************************************************/
/********************************** DATABASE **********************************/
/******************************************************************************/
    
    /** Array with the database values for each server type
     * @var mixed
     */
    public static $database=array(
        "driver" => "mysql",
        "persistent" => false,
        "database" => "maqinato",
        "read"=>array(
            "login" => "maqinatoRead",
            "password" => "asdasd"
         ),
        "write"=>array(
            "login" => "maqinatoWrite",
            "password" => "asdasd"
         ),
        "delete"=>array(
            "login" => "maqinatoDelete",
            "password" => "asdasd"
         ),
        "all"=>array(
            "login" => "maqinato",
            "password" => "asdasd"
         ),
        "host"=>array(
            "development"=>"localhost",
            "testing"=>"localhost",
            "release"=>"release.database.server.com",
            "production"=>"production.database.server.com"
        )
    );
    
/******************************************************************************/
/**************************** USER DATA MANAGEMENT ****************************/
/******************************************************************************/
           
    //+++++ FILESYSTEM DATA ACCESS +++++
    /** Define if load users data from the filesystem or an REST service (like AWS)
     * @var string:
     *          "file": take the user data from the filesystem, use the dataPath variable
     *          "rest": take the user data from a REST server (like AWS)
     */
    public static $dataSource = "file";
//    public static $dataSource = "rest";
    /** Used if $dataSource="file", a folder in the server, must be accessible from Apache
     * @var string: a valid path where the data will be saved 
     * @todo Configure Apache config file to read data from an external path (alias)
     */
    //Especifica la url donde se deben escribir los datos si no es AWS
    public static $dataWrite = "/var/www/data";
    //Especifica la url de donde se deben leer los datos si no es AWS
    public static $dataRead = "http://localhost/data";
    
    
    //+++++ REST DATA ACCESS (AWS) +++++    
    /** Store the key variables of AWS S3 service
     * Keys are in the AWS Account Page/Security Credentials/Access Credentials/
     * Click in "show" to view the Secret Access Key
     */
    private static $awsAccessKey = "SDFSDFSFSF"; // AWS Access key
    private static $awsSecretKey = "SDFSA987SDF987SD9F78SA9D87F"; // AWS Secret key
    /** URL of the S3 storage server
     * @var string
     */
    //maqinato
    public static $awsS3Server = "s3.amazonaws.com";
    /** If the connection is with SSL
     * @var bool
     */
    public static $awsUseSSL = true;
    /** Data Bucket name
     * @var string
     */
    public static $awsBucket = "maqinato";
    /********************************* METHODS ********************************/
    /**
     * Returns the AWS access key
     * @return string AWS access key
     */
    public static function awsAccessKey(){
        return self::$awsAccessKey;
    }
    /**
     * Returns the AWS secret key
     * @return string AWS secret key
     */
    public static function awsSecretKey(){
        return self::$awsSecretKey;
    }
    
    
    
/******************************************************************************/
/****************************** FRONT END OPTIONS *****************************/
/******************************************************************************/
    /** Default lifetime for the session in minutes. If 0 the session will not 
     * end, else keep the specified time. If the keep option is set in login, 
     * the lifetime will be set on 0 and keeped.
     * @var int: Time (in minutes) to keep the session
     */
    public static $sessionLifeTime = 30;
    /** Time to check the session in minutes. Each x minutes the system will check
     * if the session must killed or keep.
     * @var int: Time to check if the session must keep
     */
    public static $sessionCheckTime = 10;
    
    
    /********************************* DAEMONS ********************************/
    /** Intervalo (milisegundos) en que los demonios consultan el servidor */
    public static $daemonsInterval = 10000;
    
    
    /******************************** NEWSFEED ********************************/
    /** Número de actividades que se cargan al iniciar el newsfeed*/
    public static $activitiesLoadInit = 10;
    /** Número de actividades que se cargan al llegar al final de la página*/
    public static $activitiesLoadScroll = 10;
    
    /********************************* GIVING *********************************/
    /** Monto mínimo que se puede donar*/
    public static $amountMin = 10;
    public static $amountDefault = 25;
    public static $amountIncrement = 5;
    
    /********************************* SEARCH *********************************/
    /* Cantidad máxima de usuarios, tags o nonprofits a mostrar en la búsqueda*/
    public static $searchMaxUsers = 5;
    public static $searchMaxTags = 3;
    public static $searchMaxNonprofits = 3;
    
/******************************************************************************/
/************************************ FILES ***********************************/
/******************************************************************************/
    /**
     * Matriz de formatos de imagen permitidos
     */
    public static $allowedImages = array("jpg","jpeg","gif","png","JPG","JPEG","GIF","PNG");
    /**
     * Define el tamaño máximo de las fotos de perfil de los Usuarios en MB
     */
    public static $maxSizeProfileImage = 5;
    /**
     * Define el tamaño máximo de las imágenes de los bondeeds en MB
     */
    public static $maxSizeDeedImage = 5;
    /**
     * Calidad de las imagenes de los Good Deeds (0: baja, 100: alta)
     */
    public static $deedsQuailty = 60;
    
/******************************************************************************/
/***************************** ANALYTICS OPTIONS ******************************/
/******************************************************************************/
    /**
     * Indica el servidor en el que se deben generar los datos para Google Analytics
     */
    public static $analyticsServer="production";
    
/******************************************************************************/
/******************************** VENDORS API's *******************************/
/******************************************************************************/
    /**
     * Mailchimp y Mandrill
     */
    public static $apiMailChimp='SDFSADF,O987SD9FSF87S0DF98SDSA0DF';
    public static $apiMandrill='oisdfjosd0s9df08sdf0';
}
?>

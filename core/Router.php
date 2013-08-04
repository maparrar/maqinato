<?php
/** Router File
 * @package config */
//namespace maqinato\core;
/**
 * Router Class
 * Specifies the paths and the application name for all the System.
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package config
 */
class Router{
    /** Server type.
     *  - "development": locate in development server
     *  - "testing": Debian server (maqinato server)
     *  - "rc": Release Candidate in the Amazon AWS servers
     *  - "production": Version installed in the Amazon AWS servers
     * @var string
     */
    private static $serverType="development";
    /** Server path
     *  El primero de la lista de servidores para cada tipo de servidor
     * @var string
     */
    private static $serverUrl="";
    /** Paths of the main folders in the application
     * @var string[]
     */
    private static $paths=null;
    /** AWS S3 Class
     * @var S3
     */
    private static $s3=null;
    /** AWS S3 Public Class
     * @var S3
     */
    private static $publicS3=null;
    /** Claves del API de Stripe
     * @var string
     */
    private static $apiStripePublic="";
    private static $apiStripeSecret="";
    
    
    
    function __construct() {
        //Define the paths variables
        self::$paths=array(
            'application'   =>Maqinato::application(),
            'root'          =>Maqinato::root(),
            'api'           =>Maqinato::root().'api/',
            'core'          =>Maqinato::root().'core/',
            'engine'        =>Maqinato::root().'engine/',
                'controllers'   =>Maqinato::root().'engine/controllers/',
                'models'        =>Maqinato::root().'engine/models/',
                'tests'         =>Maqinato::root().'engine/tests/',
                'vendors'       =>Maqinato::root().'engine/vendors/',
                'views'         =>Maqinato::root().'engine/views/',
            'public'        =>Maqinato::root().'public/',
            'data'          =>Config::$dataRead.'/'
        );
        
        
    }
    
    
    /**
     * Detecta la URL de entrada y procesa los datos
     */
    public function parseRequest($requestUri){
        $controller=false;
        $function=false;
        $parameters=array();
        $requestArray=explode("/",$requestUri);
        //Recorre cada valor pasado en la URL. El primero es el controlador, el
        //segundo la función y el resto los parámetros
        $i=0;
        foreach ($requestArray as $value){
            if(trim($value)!=""){
                if($i===0){
                    $controller=$value;
                }elseif($i===1){
                    $function=$value;
                }else{
                    $parameters[]=$value;
                }
                $i++;
            }
        }
        return array(
            "controller"=>$controller,
            "function"=>$function,
            "parameters"=>$parameters
        );
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    


        /**
     * Define the paths variables
     */
//    public static function init(){
//        include_once 'Config.php';
//        //Define the server type and the application name using the type of server
//        //En caso de que no existe el SERVER_NAME se reemplaza por production
//        if(!array_key_exists("SERVER_NAME",$_SERVER)){
//            $serverName='www.maqinato.com';
//        }else{
//            $serverName=$_SERVER['SERVER_NAME'];
//        }
//        
//        //Toma como dataRead el primer servidor de la lista de servidores para cada tipo de servidor
//        Config::$dataRead=self::$serverUrl."/data";
//        
//        /*PATHS*/
//        //Define the application root folder
//        self::$root=preg_replace('#/+#','/',$_SERVER['DOCUMENT_ROOT'].'/'.self::$application."/");
//
//        
//        //Define the paths variables
//        self::$paths=array(
//            'application'   =>self::$application,
//            'root'          =>self::$root,
//            'config'        =>self::$root.'config/',
//            'data'          =>self::$root.'data/',
//            'models'        =>self::$root.'models/',
//            'controllers'   =>self::$root.'controllers/',
//            'transactions'  =>self::$root.'transactions/',
//            'vendors'       =>self::$root.'vendors/',
//            'views'         =>self::$root.'views/',
//                'home'          =>self::$root.'views/home/',
//                'pages'         =>self::$root.'views/pages/',
//                'profile'       =>self::$root.'views/profile/',
//                'settings'      =>self::$root.'views/settings/',
//            'web'           =>self::$root.'web/',
//                'ajax'          =>self::$root.'web/ajax/',
//                'css'           =>self::$root.'web/css/',
//                'img'           =>self::$root.'web/img/',
//                'js'            =>self::$root.'web/js/',
//                'templates'     =>self::$root.'web/templates/',
//            'data'          =>Config::$dataRead.'/'
//        );
//        
//        //Configure the users data source
//        if(Config::$dataSource=="file"){
//            
//        }elseif(Config::$dataSource=="rest"){
//            if(!class_exists('S3')) include_once Router::rel("config")."S3.php";
//            self::$s3=new S3(Config::awsAccessKey(),Config::awsSecretKey(),Config::$awsUseSSL,Config::$awsS3Server);
//            self::$publicS3=new S3(Config::awsAccessKey(),Config::awsSecretKey(),Config::$awsUseSSL,Config::$awsS3Server);
//        }
//    }
    /**
     * Returns the application name
     * @return string Application name
     */
    public static function root(){
        return self::$root;
    }
    /**
     * Returns the application name
     * @return string Application name
     */
    public static function application(){
        return self::$application;
    }
    /**
     * Returns the server name
     * @return string Server type
     */
    public static function server(){
        return self::$serverType;
    }
    /**
     * Returns the server url
     * @return string Server type
     */
    public static function serverUrl(){
        return self::$serverUrl;
    }
    /**
     * Retorna la clave pública del API de Stripe
     * @return string Clave pública para el API de Stripe
     */
    public static function apiStripePublic(){
        return self::$apiStripePublic;
    }
    /**
     * Retorna la clave privada del API de Stripe
     * @return string Clave privada para el API de Stripe
     */
    public static function apiStripeSecret(){
        return self::$apiStripeSecret;
    }
    /** 
     * Calculate and return the absolute path 
     * @param string Folder from which you want to find the absolute path
     * @return string absolute path
     */
    public static function abs($folder){
        $path=self::$paths[$folder];
        return $path;
    }
    /** 
     * Calculate and return the relative path 
     * @param string Folder from which you want to find the relative path
     * @param string (Optional) Se define cuando se quiere forzar la llamada desde otro folder
     * @return string relative path
     */
    public static function path($folder,$forceCaller=false){
        $path="";
        if($folder=='application'){
            $path=self::$paths[$folder];
        }else{            
            //Caller folder of script 
            $folder=self::$paths[$folder];
            $caller=$_SERVER['SCRIPT_FILENAME'];            
            if($forceCaller){
                $caller=self::abs($forceCaller);
            }
            //Convert \ by /
            $folder=str_replace("\\","/",$folder);
            $caller=str_replace("\\","/",$caller);
            
            //Remove absolute path part
            $folder=substr($folder,strpos($folder,self::$paths['root'])+strlen(self::$paths['root']));
            $caller=substr($caller,strpos($caller,self::$paths['root'])+strlen(self::$paths['root']));
            
            //Si están en la misma carpeta, no se sale de ella
            if (dirname($folder."/dummie.php")==dirname($caller."/dummie.php")){
                $path=substr($folder,strrpos($folder,"/")+1);
            }else{
                //Counts the exits to reach the folder
                $foldersExit=substr_count($caller,"/");
                //Relative path from the present folder
                for ($i=0;$i<$foldersExit;$i++){
                    $path.="../";
                }
                $path.=$folder;
            }
        }
        return $path;
    }
    /** 
     * Calculate and return the relative path of an external path (even outside 
     * of the apache root document)
     * @param string Folder from which you want to find the relative path
     * @return string relative path
     */
    public static function relExternal($folder){
        $path="";
        //Caller folder of script 
        $folder=self::$paths[$folder];
        $caller=getcwd().'/';

        //Convert \ by /
        $folder=str_replace("\\","/",$folder);
        $caller=str_replace("\\","/",$caller);
        //Remove the common path
        $common=self::commonPath(array($folder,$caller));
        $folder=substr($folder,strlen($common));
        $caller=substr($caller,strlen($common));

        //Si están en la misma carpeta, no se sale de ella
        if (dirname($folder."dummie.php")==dirname($caller."dummie.php")){
            $path=substr($folder,strrpos($folder,"/")+1);
        }else{
            //Counts the exits to reach the folder
            $foldersExit=substr_count($caller,"/");
            //Relative path from the present folder
            for ($i=0;$i<$foldersExit;$i++){
                $path.="../";
            }
            $path.=$folder;
        }
        
        return $path;
    }
    /**
     * Calculates the common path betwen two paths
     * @param string() Array of paths
     * @return string Common Path
     */
    public static function commonPath($paths){
        $lastOffset = 1;
        $common = '/';
        while (($index = strpos($paths[0], '/', $lastOffset)) !== FALSE) {
            $dirLen = $index - $lastOffset + 1; // include /
            $dir = substr($paths[0], $lastOffset, $dirLen);
            foreach ($paths as $path) {
                if (substr($path, $lastOffset, $dirLen) != $dir)
                    return $common;
            }
            $common .= $dir;
            $lastOffset = $index + 1;
        }
        return substr($common, 0, -1);
    }
    /**
     * Return the html includes of a JS script or an array of scripts, if is not
     * registered, search the name in js folder
     * @param mixed Array or sigle name of JS scripts
     * @return string String with the includes for each JS provided
     */
    public static function js(){
        $string="";
        $values = func_get_args();
        foreach ($values as $value){
            if($value=="google"){
                $string.='<script class="component" type="text/javascript" src="'.Config::$jsScripts[$value].'"></script>';
            }elseif(array_key_exists($value,Config::$jsScripts)){
                $string.='<script class="component" type="text/javascript" src="/'.Maqinato::application()."/".Config::$jsScripts[$value].'"></script>';
            }else{
                $ext=pathinfo($value,PATHINFO_EXTENSION);
                if(!$ext){
                    $value.=".js";
                }
                if(file_exists(self::path("js").$value)){
                    $string.='<script class="component" type="text/javascript" src="'.self::path("js").$value.'"></script>';
                }else{
                    $string.='JS script NOT Found: '.$value.'<br/>';
                }
            }
        }
        echo $string;
    }
    /**
     * Return the html includes of a CSS script or an array of scripts, if is not
     * registered, search the name in css folder
     * @param mixed Array or sigle name of CSS scripts
     * @return string String with the includes for each CSS provided
     */
    public static function css(){
        $string="";
        $values = func_get_args();
        foreach ($values as $value){
            if(array_key_exists($value,Config::$cssScripts)){
                $string.='<link rel="stylesheet" type="text/css" href="/'.Maqinato::application()."/".Config::$cssScripts[$value].'">';
            }else{
                $ext=pathinfo($value,PATHINFO_EXTENSION);
                if(!$ext){
                    $value.=".css";
                }
                if(file_exists(self::path("css").$value)){
                    $string.='<link rel="stylesheet" type="text/css" href="'.self::path("css").$value.'">';
                }else{
                    $string.='CSS script NOT Found: '.$value.'<br/>';
                }
            }
        }
        echo $string;
    }
    /** Return a file from the data folder, selected from filesystem or rest
     * @param string Path and filename, i.e.: 
     *      - "combinations/110.png"
     *      - "users/images/10.png"
     * @return mixed Return the object of the Data file
     */
    public static function getFile($filePath){
        if(Config::$dataSource=="file"){
//            $path=self::relExternal('data').$filePath;
        }elseif(Config::$dataSource=="rest"){
            $object=self::$s3->getObject(Config::$awsBucket,"data/".$filePath);
        }
        return $object;
    }
    /** Delete an object from the data folder, selected from filesystem or rest
     * @param string Path and filename, i.e.: 
     *      - "combinations/110.png"
     *      - "users/images/10.png"
     * @return bool True if successful
     */
    public static function deleteFile($filePath){
        $object=false;
        if(Config::$dataSource=="file"){
            unlink(self::relExternal('data').$filePath);
        }elseif(Config::$dataSource=="rest"){
            $object=self::$s3->deleteObject(Config::$awsBucket,"data/".$filePath);
        }
        return $object;
    }
    /** Copy a file from one place to another in selected filesystem or rest
     * @param string Path and filename, i.e.: 
     *      - "combinations/110.png"
     *      - "users/images/10.png"
     * @param string Path and filename, i.e.: 
     *      - "combinations/110.png"
     *      - "users/images/10.png"
     * @return bool True if successful
     */
    public static function copyFile($srcFilePath,$dstFilePath){
        $success=false;
        if(Config::$dataSource=="file"){
            $success=copy(self::relExternal('data').$srcFilePath,self::relExternal('data').$dstFilePath);
        }elseif(Config::$dataSource=="rest"){
            $success=self::$s3->copyObject(Config::$awsBucket,"data/".$srcFilePath,Config::$awsBucket,"data/".$dstFilePath);
        }
        return $success;
    }
    /** Publica un arcivo del bucket privado al bucken público, solo para AWS
     * @param string Path and filename, i.e.: 
     *      - "combinations/110.png"
     *      - "users/images/10.png"
     * @param string new name
     *      - "abcde1234.png"
     * @return bool True if successful
     */
    public static function publishFile($file,$publicName){
        $success=false;
        if(Config::$dataSource=="rest"){
            self::$s3->copyObject(Config::$awsBucket,"data/".$file,Config::$awsPublicBucket,$publicName);
            $success="https://".Config::$awsS3Server."/".Config::$awsPublicBucket."/".$publicName;
        }
        return $success;
    }
    /**
     * Return the path for an image from filesystem or by REST. If the file is
     * is not found, return the default.png image from that folder
     * @param string Path and filename (data is used like root folder), i.e.
     *      - "combinations/110.png"
     * @return string Return the path of the Data image, i.e.:
     *      - "http://s3-sa-east-1.amazonaws.com/maqinato/data/combinations/110.png?AWSAccessKeyId=AKIAJP2UTAR7UQEPY72Q&Expires=1356955251&Signature=WfmXKKXhBzjIU1ZsM4UO7F%2Bo3QM%3D"
     *      - "/home/operator/maqinato/data/combinations/110.png"
     */
    public static function img($filePath){
        $path=Router::dataUrl($filePath);
        return $path;
    }
    /** 
     * Alias para dataPut, sube un archivo al sistema de archivos definido en Config
     * @param string Path and filename, i.e.: 
     *      - "/tmp/tempfile123.png"
     * @param string Name to save the file in the data folder: i.e: 
     *      - "combinations/110.png"
     * @return bool:
     *      true if could save the file
     *      false otherwise
     */
    public static function saveFile($source,$destination){
        return self::dataPut($source,$destination);
    }
    /** 
     * Función utilizada para guardar una imagen que ha sido subida por medio de
     * un FILE input.
     * @param string Path and filename, i.e.: 
     *      - $_FILES["file"]["tmp_name"]
     * @param string Name to save the file in the data folder: i.e: 
     *      - "combinations/110.png"
     * @return bool:
     *      true if could save the file
     *      false otherwise
     */
    public static function saveUploadedFile($source,$destination){
        $success=false;
        if(Config::$dataSource=="file"){
            if(move_uploaded_file($source,Config::$dataWrite."/".$destination)){
                $success=true;
            }
        }elseif(Config::$dataSource=="rest"){
            if(self::dataPut($source,$destination)){
                $success=true;
            }
        }
        return $success;
    }
    /** Return a file path from the data folder, selected from filesystem or rest
     * @param string Path and filename, i.e.: 
     *      - "combinations/110.png"
     *      - "users/images/10.png"
     * @return string Return the path of the Data file, i.e.:
     *      - "http://s3-sa-east-1.amazonaws.com/maqinato/data/combinations/110.png?AWSAccessKeyId=AKIAJP2UTAR7UQEPY72Q&Expires=1356955251&Signature=WfmXKKXhBzjIU1ZsM4UO7F%2Bo3QM%3D"
     *      - "/home/operator/maqinato/data/combinations/110.png"
     */
    public static function dataUrl($filePath){
        if(Config::$dataSource=="file"){
            $path=Config::$dataRead."/".$filePath;
        }elseif(Config::$dataSource=="rest"){
            //Funcionando
            $path=self::$s3->getAuthenticatedURL(Config::$awsBucket,"data/".$filePath,10000,false,true);
        }
        return $path;
    }
    /** Save a file in the data folder, selected from filesystem or rest
     * @param string Path and filename, i.e.: 
     *      - "/tmp/tempfile123.png"
     * @param string Name to save the file in the data folder: i.e: 
     *      - "combinations/110.png"
     * @return bool:
     *      true if could save the file
     *      false otherwise
     */
    public static function dataPut($source,$destination){
        $success=false;
        if(Config::$dataSource=="file"){
            if(rename($source,Config::$dataWrite."/".$destination)){
                $success=true;
            }
        }elseif(Config::$dataSource=="rest"){
            if (self::$s3->putObjectFile($source,Config::$awsBucket,"data/".$destination,S3::ACL_AUTHENTICATED_READ)) {
                $success=true;
            }
        }
        return $success;
    }
    /**
     * Chek if a file exist. If is remote, check the URL, if is local, check with
     * file_exist() function
     * @abstract Debido a la demora en la comprobación si una imagen existe o no
     * se verifica si la imagen carga directamente en la etiqueta img y
     * se crea el directorio para imágenes por default que va con el
     * código de la aplicación. Esta función no se usa en self::img() por ahora.
     * @param string relative path to the data folder 
     */
    public static function fileExist($file){
        //Debido a la demora en la comprobación si una imagen existe o no
        //se verifica si la imagen carga directamente en la etiqueta img y
        //se crea el directorio para imágenes por default que va con el 
        //código de la aplicación. Esta función no se usa por ahora.
        
        $exist=false;
        if(Config::$dataSource=="file"){
            if(file_exists(Router::dataUrl($file))){
                $exist=true;
            }
        }elseif(Config::$dataSource=="rest"){
            
        }
        return $exist;
    }    
    /**
     * Write the main configuration variables in html to be readed from JS
     * @return string Write the variables in html
     */
    public static function configInHtml(){
        $user=false;
        $id=0;
        $sex="M";
        $name="";
//        if(AccessController::checkSession()){
//            $user=AccessController::getSessionUser();
//            $id=$user->getId();
//            $sex=$user->getSex();
//            $name=$user->name();
//        }else{
//            $_SESSION["sessionLifetime"]=Config::$sessionLifeTime;
//        }
        //Create the paths array to JS
        foreach (self::$paths as $key => $path) {
            $paths["$key"]=str_replace(self::root(),"",$path);
        }
        $data=array(
            "application"=>self::application(),
            "server"=>self::server(),
            "serverUrl"=>self::serverUrl(),
            "protocol"=>Config::$protocol,
            "user"=>$id,
            "userGender"=>$sex,
            "userName"=>$name,
            "paths"=>$paths,
            "location"=>$_SERVER['SERVER_NAME'],
            "sessionLifetime"=>$_SESSION["sessionLifetime"],
            "sessionCheckTime"=>Config::$sessionCheckTime,
            "daemonsInterval"=>Config::$daemonsInterval,
            "activities"=>array(
                "loadInit"=>Config::$activitiesLoadInit,
                "loadScroll"=>Config::$activitiesLoadScroll
            ),
            "maqinato"=>array(
                "percentFees"=>Config::$percentFees,
                "amountMin"=>Config::$amountMin,
                "amountDefault"=>Config::$amountDefault,
                "amountIncrement"=>Config::$amountIncrement
            )
        );
        $html=
            "<!--Configuration data-->
            <input type='hidden' id='config' 
                value='".json_encode($data)."'
            />";
        echo $html;
    }
    
    
/******************************************************************************/
/****************************** SYSTEM FUNCTIONS ******************************/
/******************************************************************************/
/**
 * Estas funciones deben estar en el futuro en una clase "ominpresente" llamada 
 * System o Master en la que estarán las funciones comunes y el Router. Además se
 * encargará de orquestar todo para que funcione con la menor configuración posible
 */
    /**
     * Convierte un array en un string en json, aprovechando el método jsonEncode() 
     * de la clase Object de la que heredan todos los demás objetos.
     * @param array $array con el contenido de los objetos
     * @param string $name (opcional) Nombre que tiene el objeto de Json retornado
     * @return string Cadena con los objetos convertidos a json
     */
    public static function arrayToJson($array,$name="array"){
        $string='"'.$name.'":[';
        if(is_array($array)){
            foreach ($array as $value){
                $string.=$value->jsonEncode().',';
            }
            if(count($array)>0){
                //Elimina la última coma
                $string=substr($string,0,-1);
            }
        }else{
            $string.=$array->jsonEncode().',';
        }
        $string.=']';
        return $string;
    }
}
?>

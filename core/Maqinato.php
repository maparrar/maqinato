<?php
/** Maqinato File
 * @package core */
/**
 * Maqinato Class
 * Core class for Maqinato
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package core
 * @todo Implementar el manejo de errores
 * @todo Usar el desarrollo orientado a tests
 * @todo Detección de tipo de servidor (development, testing, release, production)
 */
class Maqinato{
    /** Variable donde se almacena la configuración de maqinato
     * @var array
     */
    public static $config=array();
    /** Root folder of the application (i.e. /var/www/maqinato)
     * @var string
     */
    private static $root=null;
    /** Application name  (i.e. maqinato)
     * @var string
     */
    private static $application="";
    /** Uri with the request, witouth application name
     * @var string Uri request
     */
    private static $requestUri="";
    /** Datos del servidor detectado por maqinato
     * @var string
     */
    private static $environment="";
    /** Array asociativo de controlador+función+parámetros tomado de la URL de entrada
     * $request=array(
     *      "controller"=>"controllerName",
     *      "function"=>"functionName",
     *      "parameters"=>array(
     *          [0]=>"firstParamater",
     *          [1]=>"secondParamater"
     *      )
     * );
     */
    private static $request=array(
        "controller"=>false,
        "function"=>false,
        "parameters"=>array()
    );
    
    /**************************** DEBUG VARIABLES ****************************/
    /** Nivel de debug.
     *  0   No muestra ningún mensaje de maqinato
     *  1   Muestra la información básica y el request actual
     *  2   Muestra el nivel 1 + todos los datos de configuración de maqinato
     *  3   Muestra el nivel 2 + la lista de mensajes del debug
     */
    private static $debugLevel=3;
    /**
     * Array para almacenar todos los mensajes debug que se requieran
     */
    private static $debug=array();
    /** Timers to debug methods, procedures, functions or blok of codes
     * Each value of array must countain an array with:
     *  "name"=>"timer_name",
     *  "ini"=>"timer_start",
     *  "end"=>"timer_end",
     */
    private static $procTimers=array();
    
    
    
    
    

    /**
     * Principal función of the Maqinato Class
     * @param string $root Root path in the system. Ie. /var/www/maqinato
     * @param string $application Name of the application folder. Ie. maqinato
     */
    public static function exec($root,$application){
        $ini=microtime(true);
        self::$root=$root;
        self::$application=$application;
        self::$requestUri=str_replace(self::$application."/","",filter_input(INPUT_SERVER,'REQUEST_URI',FILTER_SANITIZE_STRING));
        
        //Registra la función que carga las clases cuando no están include o require
        self::autoload();
        
        //Incluye los archivos de configuración
        self::$config=self::loadConfig();
        
        //Detecta el nombre del servidor y selecciona el ambiente
        self::$environment=self::loadEnvironment();
        
        
        
        
        
        
        
        
        
        //Incluye los estilos básicos de maqinato
        Router::css("template");
        
        //Obtiene los comandos pasados en la URL
        self::$request=Router::parseRequest(self::$requestUri);
        
        
        $controller=new TempController();
//        $controller->probando(self::$request["parameters"][0]);
        
        
        
        self::load(self::$request);
        
//        phpinfo();
        
        
        
        $end=microtime(true);
        array_push(self::$procTimers,array("name"=>"maqinato","ini"=>$ini,"end"=>$end));
        self::info();
    }
    
    private static function loadConfig(){
        return array(
            "app"           =>  require_once 'engine/config/app.php',
            "environment"   =>  require_once 'engine/config/environment.php',
            "client"        =>  require_once 'engine/config/client.php',
            "database"      =>  require_once 'engine/config/database.php',
            "paths"         =>  require_once 'engine/config/paths.php'
        );
    }
    
    private static function loadEnvironment(){
        $serverName=$_SERVER['SERVER_NAME'];
        /*DEVELOPMENT*/
        if(in_array($serverName,self::$config["environment"]["development"]["urls"])){
            $environment="development";
        /*RELEASE CANDIDATE*/
        }elseif(in_array($serverName,self::$config["environment"]["release"]["urls"])){
            $environment="release";
        /*PRODUCTION*/
        }elseif(in_array($serverName,self::$config["environment"]["production"]["urls"])){
            $environment="production";
        }
        return $environment;
    }
    
    public static function load($request){
        switch ($request["controller"]) {
            case "":
                self::redirect("landing");
                break;
            case "landing":
                View::load("landing");
                break;
            case "home":
                View::load("home");
                break;
            case "error":
                View::error();
                break;
            default:
                Maqinato::debug("Controller not detected",__FILE__,__LINE__);
                self::redirect("error/notFound");
                break;
        }
    }
    
    public static function redirect($url){
        header( 'Location: /'.self::application().'/'.$url) ;
    }


    /**************************************************************************/
    /*************************** GETTERS AND SETTERS **************************/
    /**************************************************************************/
    public static function root(){return self::$root;}
    public static function application(){return self::$application;}
    public static function request(){return self::$request;}
    
    
    /**************************************************************************/
    /********************************** UTILS *********************************/
    /**************************************************************************/
    /**
     * Función que carga automáticamente un archivo de una clase cuando no ha sido
     * cargado usando include o require. Esta función los carga con require.
     */
    private static function autoload(){
        $ini=microtime(true);
        spl_autoload_register(function($className){
            //Lista de directorios en los que se quiere buscar la clase
            $directories = array(
                self::$root."/engine/controllers",
                self::$root."/engine/models",
                self::$root."/core"
            );
            //Crea un iterador por cada directorio y busca las clases
            foreach ($directories as $directory){
                $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
                while($it->valid()){
                    //Si es un directorio de tipo . y no ..
                    if (!$it->isDot()){
                        //Obtiene el nombre del archivo
                        $fileName=end(explode(DIRECTORY_SEPARATOR,$it->getSubPathName()));
                        if($fileName==$className.'.php'){
                            require_once $it->key();
                            break;
                        }
                    }
                    $it->next();
                }
            }
        });
        $end=microtime(true);
        array_push(self::$procTimers,array("name"=>"autoload classes","ini"=>$ini,"end"=>$end));
    }
    
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
    
    


    
    /**
     * Agrega un mensaje de error al array de debug
     */
    public static function debug($message,$file="",$line=""){
        self::$debug[]="[".date("Y-m-d H:i:s")."] - - - [".$file."] - - - [line: ".$line."] - - - [".$message.']';
    }
    
    /**
     * Print the Maqinato information
     */
    public static function info(){
        $info=$config=$debug="";
        if(self::$debugLevel>0){
            if(self::$debugLevel>=1){
                $info='<div class="section">';
                    $info.='<div class="title">INFO</div>';
                    $info.='<ul>';
                        $info.='<li>root: '.self::$root.'</li>';
                        $info.='<li>application: '.self::$application.'</li>';
                        $info.='<li>environment: '.self::$environment.'</li>';
                        $info.='<li>request:</li>';
                            $info.='<ul>';
                                $info.='<li>uri: '.self::$requestUri.'</li>';
                                $info.='<li>controller: '.self::$request["controller"].'</li>';
                                $info.='<li>function: '.self::$request["function"].'</li>';
                                $info.='<li>params:</li>';
                                    $info.='<ul>';
                                        foreach(self::$request["parameters"] as $key => $parameter){
                                            $info.='<li>'.$parameter.'</li>';
                                        }
                                    $info.='</ul>';
                            $info.='</ul>';
                        $info.='<li>procTimers:</li>';    
                            $info.='<ul>';
                                foreach (self::$procTimers as $timer){
                                    $info.='<li>'.$timer["name"].": ".sprintf('%f',$timer["end"]-$timer["ini"])." ms</li>";
                                }
                            $info.='</ul>';
                    $info.='</ul>';
                $info.='</div>';
            }
            if(self::$debugLevel>=2){
                $config='<div class="section">';
                    $config.='<div class="title">CONFIG</div>';
                    $config.=self::makeList(self::$config);
                $config.='</div>';
            }
            if(self::$debugLevel>=3){
                $debug='<div class="section">';
                    $debug.='<div class="title">DEBUG</div>';
                    $debug.='<ul>';
                        foreach (self::$debug as $key => $message){
                            $debug.='<li>'.$message.'</li>';
                        }
                    $debug.='</ul>';
                $debug.='</div>';
            }
            $output='<div class="maqinato_debug">';
            $output.='<div class=title>MAQINATO</div>';
            $output.='<div class="column left">';
                $output.=$info;
                $output.=$config;
            $output.='</div>';
            $output.='<div class="column right">';
                $output.=$debug;
            $output.='</div>';
            $output.='</div>';
            echo $output;
        }
    }
    //Make a list from an array 
    private static function makeList($array){
        if(is_array($array)&&count($array)>0){
            $output='<ul>';
            foreach ($array as $key => $value){
                if(is_array($value)){
                    $output.='<li>['.$key.']: </li>';
                    $output.=self::makeList($value);
                }else{
                    $output.='<li>['.$key.']: '.$value.'</li>';
                }
            }
            $output.='</ul>'; 
        }
        return $output; 
    }
    
}
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
    /** Router object
     * @var Router
     */
    private static $router=null;
    /** Server type.
     *  - "development": locate in development server
     *  - "testing": Debian server (maqinato server)
     *  - "rc": Release Candidate in the Amazon AWS servers
     *  - "production": Version installed in the Amazon AWS servers
     * @var string
     */
    private static $serverType="development";
    /** Two types of platforms: web y app. i.e:
     * Web
     *      - desktop: Un navegador de escritorio estándar
     *      - mobile: navegador de un dispositivo móvil: tabletas, celulares, ...
     * App
     *      - android: aplicación Android
     *      - ios: aplicación iOS
     *      - blackberry: aplicación Blackberry
     *      - ...
     * @var string
     */
    private static $platform="desktop";
    /** Características de la ventana detectada
     * 
     */
    private static $screen=array();
    
    /** Array asociativo de controlador+función+parámetros tomado de la URL de entrada
     * $command=array(
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
     * Constructor of the Maqinato Class
     * @param string $root Root path in the system. Ie. /var/www/maqinato
     * @param string $application Name of the application folder. Ie. maqinato
     */
    public static function start($root,$application){
        self::$root=$root;
        self::$application=$application;
        self::$requestUri=str_replace(self::$application."/","",$_SERVER['REQUEST_URI']);
        
        //Incluye los archivos de configuración
        self::$config=self::loadConfig();
        print_r(self::$config);
        
        
        //Registra la función que carga las clases cuando no están include o require
        self::autoload();
        
        
        
        
        
        
        //Creates the router object
        self::$router=new Router();
        
        Router::css("template");
        
        //Obtiene los comandos pasados en la URL
        self::$request=self::$router->parseRequest(self::$requestUri);
        
        
        $controller=new TempController();
        
        $controller->probando(self::$request["parameters"][0]);
        
        
        
        self::redirectRequest(self::$request);
        
        
        
        self::info();
//        
//        require_once 'engine/views/landing/index.php';
        
    }
    
    private static function loadConfig(){
        return array(
            "paths"=>require_once 'engine/config/paths.php'
        );
    }
    
    public static function redirectRequest($request){
        switch ($request["controller"]) {
            case "landing":
                View::load("landing");
                break;
            case "error":
                View::error();
                break;
            default:
                Maqinato::debug("Controller not detected",__FILE__,__LINE__);
                self::redirectRequest(array(
                    "controller"=>"error"
                ));
                break;
        }
    }
    
    
    
    
//    function my_autoloader($className){
//        $parts = explode('\\', $className); //split out namespaces
//        $classname = strtolower(end($parts)); //get classname case insensitive (just my choice)
//
//            //TODO: Your Folder handling which returns classfile
//
//        require_once($loadFile); 
//    }
    
    /**************************************************************************/
    /*************************** GETTERS AND SETTERS **************************/
    /**************************************************************************/
    public static function root(){return self::$root;}
    public static function application(){return self::$application;}
    
    
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



    private function detectServer(){
        /*LOCAL DEVELOPMENT*/
//        if(in_array($serverName,Config::$servers["development"])){
//            self::$serverType="development";
//            self::$application=Config::$application;
//            Config::$dataSource="file";
//            //Toma como dataRead el primer servidor de la lista de servidores para cada tipo de servidor
//            self::$serverUrl=Config::$protocol."://".Config::$servers["development"][0];
//        /*DEBIAN TESTING VERSION */
//        }elseif(in_array($serverName,Config::$servers["testing"])){
//            self::$serverType="testing";
//            self::$application=Config::$application;
//            Config::$dataSource="file";
//            //Toma como dataRead el primer servidor de la lista de servidores para cada tipo de servidor
//            self::$serverUrl=Config::$protocol."://".Config::$servers["testing"][0];
//        /*AMAZON RELEASE CANDIDATE*/
//        }elseif(in_array($serverName,Config::$servers["release"])){
//            self::$serverType="release";
//            self::$application="";
//            Config::$dataSource="rest";
//            Config::$daemonsInterval = 10000;
//            Config::$awsBucket = "maqinatorc";
//            //Toma como dataRead el primer servidor de la lista de servidores para cada tipo de servidor
//            self::$serverUrl=Config::$protocol."://".Config::$servers["release"][0];
//        /*AMAZON PRODUCTION*/
//        }elseif(in_array($serverName,Config::$servers["production"])){
//            self::$serverType="production";
//            self::$application="";
//            Config::$dataSource="rest";
//            Config::$daemonsInterval = 10000;
//            Config::$awsBucket = "maqinato";
//            Config::$protocol="https";
//            //Toma como dataRead el primer servidor de la lista de servidores para cada tipo de servidor
//            self::$serverUrl=Config::$protocol."://".Config::$servers["production"][0];
//        }
    }

    










    
    
    
    
    
    /**
     * Agrega un mensaje de error al array de debug
     */
    public static function debug($message,$file,$line){
        self::$debug[]="[".date("Y-m-d H:i:s")."] - - - [".$file."] - - - [line: ".$line."] - - - ".$message;
    }
    
    /**
     * Print the Maqinato information
     */
    public static function info(){
        if(self::$debugLevel>0){
            if(self::$debugLevel>=1){
                $info='<div class="section">';
                    $info.='<div class="title">INFO</div>';
                    $info.='<ul>';
                        $info.='<li>root: '.self::$root.'</li>';
                        $info.='<li>application: '.self::$application.'</li>';
                        $info.='<li>request:</li>';
                            $info.='<ul>';
                                $info.='<li>uri: '.self::$requestUri.'</li>';
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
    private static function makeList($array) { 

        //Base case: an empty array produces no list 
        if (empty($array)) return ''; 

        //Recursive Step: make a list with child lists 
        $output = '<ul>'; 
        foreach ($array as $key => $subArray) { 
            $output .= '<li>' . $key . self::makeList($subArray) . '</li>'; 
        } 
        $output .= '</ul>'; 

        return $output; 
    }
}
?>

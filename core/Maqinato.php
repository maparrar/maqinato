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

    
    /**************************** DEBUG VARIABLES ****************************/
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
    function __construct($root,$application){
        self::$root=$root;
        self::$application=$application;
        self::$requestUri=str_replace(self::$application."/","",$_SERVER['REQUEST_URI']);
        
        
        
        
        //Registra la función que carga las clases cuando no están include o require
        $this->autoload();
        
        
        
        
        
        
        //Creates the router object
        self::$router=new Router();
        
        
        
        
        
        
        
        
        require_once 'core/detectors/clientDetector.php';
        
        
        //Detecta las características de la ventana de la aplicación
        
        
        //Detecta el tipo de plataforma
        
        
        
        self::info();
//        
//        require_once 'engine/views/landing/index.php';
        
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
    private function autoload(){
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

    











    public static function route($get,$post){
        print_r("<br/>GET<br/>");
        print_r($get);
        print_r("<br/>POST<br/>");
        print_r($post);

    }
    
    
    
    
    
    /**
     * Print the Maqinato information
     */
    public static function info(){
        print_r("<br/>");
        print_r('=======================================================<br/>');
        print_r('Maqinato info{<br/>');
        print_r("|___ root: ".self::$root."<br/>");
        print_r("|___ application: ".self::$application."<br/>");
        print_r("|___ request uri: ".self::$requestUri."<br/>");
        print_r("|______ Get parameters:<br/>");
        print_r("|_________ ");
        print_r($_GET);
        print_r("<br/>");
        print_r("|______ Post parameters:<br/>");
        print_r("|_________ ");
        print_r($_POST);
        print_r("<br/>");
        print_r("|___ procTimers: <br/>");
        foreach (self::$procTimers as $timer){
            print_r("|______ ".$timer["name"]." = ".sprintf('%f',$timer["end"]-$timer["ini"])." ms<br/>");
        }
        print_r('}<br/>');
        print_r('=======================================================<br/>');
    }
}
?>

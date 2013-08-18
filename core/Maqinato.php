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
    /** Variable de locale procesada por la aplicación
     * @var string
     */
    private static $locale="";
    /** Datos del servidor detectado por maqinato
     * @var string
     */
    private static $environment="";
    /** 
     * Objeto de tipo Request que almacena una estructura basada en la URL
     * @var Request Objeto de tipo Request
     */
    private static $request;
    
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
     * Ejecuta todos los procesos necesarios para cada request hecho por el cliente
     * @param string $root Root path in the system. Ie. /var/www/maqinato
     * @param string $application Name of the application folder. Ie. maqinato
     */
    public static function exec($root,$application){
        $ini=microtime(true);
        //Registra la función que carga las clases cuando no están include o require
        self::autoload();
        self::$root=$root;
        self::$application=$application;
        self::$request=new Request(str_replace(self::$application."/","",filter_input(INPUT_SERVER,'REQUEST_URI',FILTER_SANITIZE_URL)));
        
        //Incluye los archivos de configuración
        self::$config=Router::loadConfig();
        
        //Procesa la configuración de i18n y l10n
        self::$locale=self::i18n();
        
        //Detecta el nombre del servidor y selecciona el ambiente
        self::$environment=self::loadEnvironment();
        
        //Incluye los estilos básicos de maqinato
        Router::css("template");
                
        //Pruebas con los controladores
        $controller=new TempController();
        $controller->probando(serialize(self::$request->getParameters()));
                
        //Hace el routing del Request capturado
        Router::route(self::$request);
        
        //Calcula el tiempo que toma procesar el request y muestra el debug
        $end=microtime(true);
        array_push(self::$procTimers,array("name"=>"maqinato","ini"=>$ini,"end"=>$end));
        self::info();
    }
    
    /**
     * Procesa la configuración de la internacionalización y localización
     */
    private static function i18n(){
        if(!self::$config["app"]["locale"]){
            $lang=filter_input(INPUT_SERVER,'HTTP_ACCEPT_LANGUAGE',FILTER_SANITIZE_STRING);
            $lang=substr($lang,0,strpos($lang,','));
            $lang=str_replace("-","_",$lang);
            $lang=reset(explode("_",$lang))."_".strtoupper(end(explode("_",$lang)));
        }else{
            $lang=self::$config["app"]["locale"];
        }
        //Verifica si el directorio con utf8 existe, sino busca el directorio estándar
        //sino busca el primero que contenga el idioma sin localización, por ejemplo
        //en "es_ES", si no lo encuentra, busca el primero que empiece con "es"
        $language=$lang;
        if(file_exists(self::$root."/locale/".$lang.".utf8")){
            $language=$lang.".utf8";
        }elseif(file_exists($lang)){
            $language=$lang;
        }else{
            $directory=self::$root."/locale";
            //Verifica los que empiecen con el idioma y toma el primero
            $list=glob($directory."/".reset(explode("_",$lang))."*");
            if(count($list)>0){
                if(file_exists($list[0].".utf8")){
                    $language=end(explode("/",$list[0])).".utf8";
                }else{
                    $language=end(explode("/",$list[0]));
                }
            }
        }
        //Configura las variables de i18n y l10n
        putenv("LC_ALL=$language");
        setlocale(LC_ALL,$language);
        // Set the text domain as 'messages'
        $domain='messages';
        bindtextdomain($domain,"./locale");
        bind_textdomain_codeset('default', 'UTF-8');
        textdomain($domain);
        return $language;
    }
    /**
     * Carga el environment a partir de la variable $_SERVER["SERVER_NAME"]
     * @return Environment El environment cargado
     */
    private static function loadEnvironment(){
        $environment=false;
        $serverName=filter_input(INPUT_SERVER,'SERVER_NAME',FILTER_SANITIZE_STRING);
        foreach(self::$config["environments"] as $envArray){
            $environment=new Environment();
            $environment->readEnvironment($envArray);
            if($environment->checkUrl($serverName)){
                break;
            }
        }
        return $environment;
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
                        $info.='<li>environment:</li>';
                            $info.='<ul>';
                                $info.='<li>name: '.self::$environment->getName().'</li>';
                                $info.='<li>urls:</li>';
                                $info.=self::makeList(self::$environment->getUrls());
                                $info.='<li>database:</li>';
                                    $info.='<ul>';
                                        $info.='<li>name: '.self::$environment->getDatabase()->getName().'</li>';
                                        $info.='<li>driver: '.self::$environment->getDatabase()->getDriver().'</li>';
                                        $info.='<li>persistent: '.self::$environment->getDatabase()->getPersistent().'</li>';
                                        $info.='<li>host: '.self::$environment->getDatabase()->getHost().'</li>';
                                        $info.='<li>connections:</li>';
                                            $info.='<ul>';
                                                foreach(self::$environment->getDatabase()->getConnections() as $dbConnection){
                                                    $info.='<li>connection:</li>';
                                                    $info.='<ul>';
                                                        $info.='<li>name: '.$dbConnection->getName().'</li>';
                                                        $info.='<li>login: '.$dbConnection->getLogin().'</li>';
                                                        $info.='<li>password: '.$dbConnection->getPassword().'</li>';
                                                    $info.='</ul>';
                                                }
                                            $info.='</ul>';
                                    $info.='</ul>';
                            $info.='</ul>';
                        $info.='<li>locale: '.self::$locale.'</li>';
                        $info.='<li>request:</li>';
                            $info.='<ul>';
                                $info.='<li>uri: '.self::$request->getUri().'</li>';
                                $info.='<li>controller: '.self::$request->getController().'</li>';
                                $info.='<li>function: '.self::$request->getFunction().'</li>';
                                $info.='<li>params:</li>';
                                    $info.='<ul>';
                                        foreach(self::$request->getParameters() as $key => $parameter){
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
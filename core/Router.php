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
    /**
     * Retorna la ruta a partir de su nombre y del directorio de rutas definido
     * en config. Esta ruta funciona respecto a la pagina principal de la aplicación
     * para escribir en los enlaces de HTML use la función link();
     */
    public static function path($folder){
        return "/".Maqinato::application()."/".Maqinato::$config["paths"]["app"][$folder];
    }
    /**
     * Procesa un Request y usa el controlador, la función y los parámetros para
     * redirigir a la página indicada.
     * @param Request $request Objeto de tipo Request que se routeará
     */
    public static function route(Request $request){
        switch ($request->getController()) {
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
    /**
     * Redirecciona a una URL dentro de la aplicación. La url debe ser del tipo
     *      controller/function/parameter1/parameter2/...
     * @param string $url La url a la que se quiere redireccionar
     */
    public static function redirect($url){
        header( 'Location: /'.Maqinato::application().'/'.filter_var($url,FILTER_SANITIZE_URL));
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
                $string.='<script class="component" type="text/javascript" src="'.Maqinato::$config["paths"]["js"][$value].'"></script>';
            }elseif(array_key_exists($value,Maqinato::$config["paths"]["js"])){
                $string.='<script class="component" type="text/javascript" src="/'.Maqinato::application()."/".Maqinato::$config["paths"]["js"][$value].'"></script>';
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
            if(array_key_exists($value,Maqinato::$config["paths"]["css"])){
                $string.='<link rel="stylesheet" type="text/css" href="/'.Maqinato::application()."/".Maqinato::$config["paths"]["css"][$value].'">';
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
    /**
     * Carga los archivos de configuración en las variables de configuraión
     * @return void
     */
    public static function loadConfig(){
        return array(
            "app"           =>  require_once 'engine/config/app.php',
            "environment"   =>  require_once 'engine/config/environment.php',
            "client"        =>  require_once 'engine/config/client.php',
            "database"      =>  require_once 'engine/config/database.php',
            "paths"         =>  require_once 'engine/config/paths.php'
        );
    }
}
?>

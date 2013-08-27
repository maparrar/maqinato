<?php
/** View File
 * @package config */
//namespace maqinato\core;
/**
 * View Class
 * Clase que se aplica a las Vistas de las aplicación
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package config
 */
class View{
    public static function render($view){
        //Incluye los estilos básicos de maqinato
        Router::css("maqinato");
        require_once "engine/views/".$view.".php";
    }
    public static function error(){
        require_once "engine/views/error.php";
    }
}
?>

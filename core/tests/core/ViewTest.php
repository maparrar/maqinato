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
    public static function load($view){
        require_once "engine/views/templates/header.php";
        require_once "engine/views/".$view.".php";
        require_once "engine/views/templates/footer.php";
    }
    public static function error(){
        require_once "engine/views/templates/header.php";
        require_once "engine/views/error.php";
        require_once "engine/views/templates/footer.php";
    }
}
?>

<?php
/** TempController File
 * @package controllers @subpackage temp */
/**
 * TempController Class
 *
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package controllers
 * @subpackage social
 */
class TempController extends BasicController{
    
    function __construct() {
        parent::__construct();
        Maqinato::debug("Starting TempController");
    }


    public function probando($param){
        Maqinato::debug("TempController::probando(".$param.")");
    }
    
    
}
?>
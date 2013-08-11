<?php
/** Request File
 * @package config */
//namespace maqinato\core;
/**
 * Request Class
 * Clase que representa una solicitud por URL
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package config
 */
class Request{
    /**
     * Controlador del request
     * 
     * @var string
     */
    protected $controller;
    /**
     * Función del request
     * 
     * @var string
     */
    protected $function;
    /**
     * Parámetros pasados al request
     * 
     * @var array
     */
    protected $parameters;
    
    /**
     * Constructor de la clase
     * @param string $url Url del request, debe ser de la forma:
     *      /url_controller/url_function/url_paramater_1/.../url_parameter_n
     * @return void
     */
    function __construct($url=""){
        $this->controller=false;
        $this->function=false;
        $this->parameters=array();
        //Si la url no es vacía, se procesa
        if(trim($url)!=""){
            $requestArray=explode("/",filter_var($url,FILTER_SANITIZE_URL));
            $i=0;
            foreach ($requestArray as $value){
                if(trim($value)!=""){
                    if($i===0){
                        $this->controller=$value;
                    }elseif($i===1){
                        $this->function=$value;
                    }else{
                        $this->parameters[]=$value;
                    }
                    $i++;
                }
            }
        }
    }
    /**
     * Get del controller
     * @return string Nombre del controller
     */
    public function getController(){
        return $this->controller;
    }
}

<?php
/** Object File
 * @package models @subpackage core */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'core/Media.php';
include_once Router::rel('models').'core/MediaType.php';
/**
 * Object Class
 * Provides standard methods to Class, like JSON transformations
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage core
 */
class Object{
    /**
     * Convert an object in JSON format
     */
    public function jsonEncode(){
        $json='{';
        foreach (get_object_vars($this) as $key => $value) {
            $json.='"'.$key.'":'.$this->jsonEncodeVariable($value).",";
        }
        //Remove the last comma
        $json=substr($json,0,-1);
        $json.='}';
        return $json;
    }
    /**
     * Encode an variable passed in JSON format
     * @param mixed Mixed variable to encode
     */
    private function jsonEncodeVariable($variable){
        $output='';
        if(gettype($variable)=="array"){
            $output='[';
            if(count($variable)>0){
                foreach ($variable as $value) {
                    $output.=$this->jsonEncodeVariable($value).",";
                }
                //Remove the last comma
                $output=substr($output,0,-1);
            }
            $output.=']';
        }elseif(gettype($variable)=="object"){
            try{
                $output=$variable->jsonEncode();
            }catch(Exception $exc) {
                $output='"'.$exc->getTraceAsString().'"';
            }
        }else{
            $output='"'.$variable.'"';
        }
        return $output;
    }
    
}
?>
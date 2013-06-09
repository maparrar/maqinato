<?php
/** SecurityController File
 * @package controllers @subpackage security */
/**
 * SecurityController Class
 *
 * Class that verifies, validates and sanitizes the input and output data 
 * from the Controllers
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package controllers
 * @subpackage security
 */
class SecurityController {
    /**
     * Check if is a valid email
     * @param string email to verify
     * @return true if is a valid email
     * @return false otherwise
     */
    public static function isemail($string){
        if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
            return true;
        }else{
            return false;
        }
    }
    /**
     * Verifica si es una url
     * @param strings $url URL
     * @return true if is a valid URL
     * @return false otherwise
     */
    public static function isurl($url){
        $regex="/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/";
        if (preg_match($regex,$url)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Check if is a valid password
     * @param string password to verify
     * @return true if is a valid password
     * @return false otherwise
     */
    public static function ispassword($password){
        $regex="/^[a-zA-Z0-9@#$%._-]{6,30}$/";
        if (preg_match($regex,$password)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Check if is a valid integer
     * @param int value to verify
     * @return true if is a valid value
     * @return false otherwise
     * @todo implement
     */
    public static function isint($value){
        if(is_int($value)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Check if is a valid float
     * @param int value to verify
     * @return true if is a valid value
     * @return false otherwise
     * @todo implement
     */
    public static function isfloat($value){
        if(is_float($value)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Check if is a valid boolean
     * @param bool value to verify
     * @return true if is a valid value
     * @return false otherwise
     */
    public static function isbool($value){
        if(is_bool($value)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Check if is the specified class
     * @param Object value to verify
     * @param string Class to verify
     * @return true if is a valid value
     * @return false otherwise
     */
    public static function isclass($value,$class){
        if(gettype($value)==="object"&&get_class($value)===$class){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Sanitize strings
     * @param string string to verify
     * @return string string sanitized (without special characters)
     */
    public static function sanitizeString($string){
        $forbidden=array("<",">");
        $sanitized=str_replace($forbidden,"",$string);
        $sanitized=filter_var($sanitized, FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitized=filter_var($sanitized, FILTER_SANITIZE_MAGIC_QUOTES);
        return $sanitized;
    }
    /**
     * Sanitize to a valid SQL code
     * @param string string to verify
     * @return string string sanitized (without special characters)
     */
    public static function sanitizesql($string){
        $sanitized=filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);
        return $sanitized;
    }
    /**
     * Sanitize to a valid SQL code
     * @param string string to verify
     * @return string string sanitized (without special characters)
     */
    public static function isdate($string){
        try{
            $fecha = new DateTime($string);
            $checkFecha=$fecha->format('Y/m/d');    
        }catch (Exception $e){
            $checkFecha=false;
        }
        return $checkFecha;
    }
    /**
     * Verifica si es un número de tarjeta de crédito válido
     * @param int $number Credit card number
     * @return true if is a valid Credit card number
     * @return false otherwise
     */
    public static function iscreditcard($number){
        $regex="/^[0-9]{9,17}$/";
        if (preg_match($regex,$number)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Verifica si es un código de verificación de tarjeta de crédito válido
     * @param int $number Credit card validation number
     * @return true if is a valid Credit card validation number
     * @return false otherwise
     */
    public static function iscreditcardcode($number){
        $regex="/^[0-9]{2,5}$/";
        if (preg_match($regex,$number)){
            return true;
        }else{
            return false;
        }
    }
}
?>

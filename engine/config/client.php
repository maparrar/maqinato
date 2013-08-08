<?php
return array(
    /** Default lifetime for the session in minutes. If 0 the session will not 
     * end, else keep the specified time. If the keep option is set in login, 
     * the lifetime will be set on 0 and keeped.
     * @var int: Time (in minutes) to keep the session
     */
    "sessionLifeTime"       =>  30,
    
    /** Time to check the session in minutes. Each x minutes the system will check
     * if the session must killed or keep.
     * @var int: Time to check if the session must keep
     */
    "sessionCheckTime"      => 10,
    
    /** Intervalo (milisegundos) en que los demonios consultan el servidor */
    "daemonsInterval"       => 10000,
    
    /**
     * Matriz de formatos de imagen permitidos
     */
    "allowedImage"          =>  array("jpg","jpeg","gif","png","JPG","JPEG","GIF","PNG"),
    
    /**
     * Define el tamaño máximo de las fotos de perfil de los Usuarios en MB
     */
    "maxSizeProfileImage"   => 5
);    
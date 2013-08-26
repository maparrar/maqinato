<?php
/** jxRefreshSession File
 * @package controllers @subpackage core */
/**
 * jxRefreshSession
 *
 * Actualiza la sesión y genera un nuevo id en cada intento
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package engine
 * @subpackage ajax
 */
session_start();
//Regenerate the Session ID with 0.1 probability
if(rand(1,10)==1){
    session_regenerate_id();
}
?>
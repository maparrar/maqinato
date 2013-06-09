<?php
/**
 * jxKeepSession ajax File
 * Keep the session alive if the keep option was set true in login
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
//Regenerate the Session ID with 0.1 probability
if(rand(1,10)==1){
    session_regenerate_id();
}
?>
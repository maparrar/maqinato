<?php
/**
 * jxActivityGet ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('vendors').'linkparser/Parser.php';
$parser=new Parser();
$url=rawurldecode($_POST["url"]);
echo $parser->parse($url);
?>
<?php




// I18N support information here
$language='es_CO.utf8';

echo("language: ".$language."<br/>");
echo("putenv: ".putenv("LC_ALL=$language")."<br/>");
echo("setlocale: ".setlocale(LC_ALL,$language)."<br/>");

// Set the text domain as 'messages'
$domain='messages';
echo("bindtextdomain: ".bindtextdomain($domain,"./locale")."<br/>");

echo("LC_ALL: ".getenv("LC_ALL")."<br/>");

bind_textdomain_codeset('default', 'UTF-8');
echo("textdomain: ".textdomain($domain)."<br/>");


echo _("NEW text in English to translate to Spanish.");


phpinfo();


/**
 * Redirect all the requests to the Maqinato class
 * Se ejecuta cada que se hace una petición
 * 
 * @author https://github.com/maparrar/maqinato
 * @package views
 * @subpackage index
 */
//namespace maqinato\core;
/**
 * Get the application and the root folder
 */
//$root=dirname(__FILE__);
//$application=basename(dirname(__FILE__));
//
////Includes the Maqinato class
//include_once 'core/Maqinato.php';
//
////Inicializa maqinato con los datos de root y aplicación
//Maqinato::exec($root, $application);
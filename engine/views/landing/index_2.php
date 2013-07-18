<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>maqinato :: Landing</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <?php
            /**
             * Index Landing page file
             * @author https://github.com/maparrar/maqinato
             * @package views
             * @subpackage landing
             */
            session_start();
            
            
            
            Maqinato::info();
            
            $config="core/Config.php";
            
            if(!class_exists('Config')) include_once $config;
            
//            print_r("PHP_SELF: ".$_SERVER["PHP_SELF"]."<br/>");
//            print_r("SCRIPT_FILENAME: ".$_SERVER["SCRIPT_FILENAME"]."<br/>");
//            print_r("SCRIPT_NAME: ".$_SERVER["SCRIPT_NAME"]."<br/>");
//            print_r("REQUEST_URI: ".$_SERVER["REQUEST_URI"]."<br/>");
//            print_r("DOCUMENT_ROOT: ".$_SERVER["DOCUMENT_ROOT"]."<br/>");
//            print_r("DIR_BASE: ".dirname(dirname(__FILE__)) ."<br/>");
//            print_r("maqinato: ".$maqinato."<br/>");
//            print_r("<br/><br/>");
            
            
            
            
//            
//            
//            //INCLUDE CSS SCRIPTS
//            Router::css("reset", "jqueryui","skeleton","layout","landing","flexslider","structure","modals","bonslider","pages","search");
//            
//            //INCLUDE JS SCRIPTS
//            //Basic
//            Router::js("jquery","jqueryui","jqueryvalidate","modernizr","system","tools");
//            
//            
//            //Write the main configuration variables in HTML to be readed from JS
//            Router::configInHtml();
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                
            });
        </script>
    </head>
    <body>
        <?php // include_once Router::rel("templates").'header.php'; ?>
        <?php // include_once Router::rel("templates").'footer.php'; ?>
         <!-- LOAD THE HTML MODALS -->
        <section id="modals">
            <?php // include_once Router::rel("views").'modals/nonprofits.php'; ?>
        </section>
    </body>
</html>
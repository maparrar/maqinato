<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>maqinato</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <?php
            /**
            * Index Home page file
             * @author https://github.com/maparrar/maqinato
             * @package views
             * @subpackage home
            */
            session_start();
            if(!class_exists('Router')) require_once '../config/Router.php';
            include_once Router::rel('controllers').'AccessController.php';
            //Verifica la validez de la clave con el correo
            $validated=AccessController::verifyValidationKey($_GET["email"],$_GET["key"]);
            if($validated){
                $message='Your account was successfully validated. <a id="resend" href="'.Router::serverUrl().'/'.Router::application().'/">Click to login.</a> ';
                //Envía el email de esperar 24 horas
//                $user=SocialController::getUser($_GET["email"]);
//                CommunicationController::sendWaitingConsiderEmail($_GET["email"]);
                
                //Destruye las sesiones anteriores para que el usario deba registrarse de nuevo
                AccessController::destroy();
            }else{
                $message='Your account could not be validated. <a id="resend" href="'.Router::serverUrl().'/'.Router::application().'/">Try again.</a> ';
            }
            //INCLUDE CSS SCRIPTS
            Router::css("reset","skeleton","layout","structure");
        ?>
    </head>
    <body id="page" class="confirmAccount">
        <?php include_once Router::rel("templates").'header.php'; ?>
        <div class="main-content">
            <div id="message">
                <div id="text"><?php echo $message; ?></div>
            </div>
        </div> <!-- END MAIN CONTENT -->
        <!-- INCLUDE FOOTER   -->
        <?php include_once Router::rel("templates").'footer.php'; ?>
    </body>
</html>
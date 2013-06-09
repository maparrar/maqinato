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
            //If session wasn't started go to landing page and destroy the session   
            if (!AccessController::checkSession()){
                AccessController::destroy();
                header("Location: ".Router::rel('root'));
                exit();
            }else{
                //Load the logged User
                $user=AccessController::getSessionUser();
                //Si ya se ha validado, va al root de la aplicación
                if(AccessController::validatedAccount($user)){
                    header("Location: ".Router::rel('root'));
                }
            }
            //INCLUDE CSS SCRIPTS
            Router::css("reset", "jqueryui", "skeleton","layout","structure");
            //INCLUDE JS SCRIPTS
            //Basic
            Router::js("jquery","jqueryui","modernizr","system","tools");
            //Components
            Router::js("security","ajaxCore","ajaxSocial","access","daemons","notifications");
            
            //Write the main configuration variables in HTML to be readed from JS
            Router::configInHtml();
        ?>  
        <script type="text/javascript">
            $(document).ready(function(){
                window.system=new System();
                system.init({
                    access:true,
                    session:true,
                    validate:true
                });
            });
        </script>
    </head>
    <body id="page" class="validateAccount">
        <?php include_once Router::rel("templates").'header.php'; ?>
        <div class="main-content">
            <div id="message">
                <div id="text">Hi <?php echo $user->name(); ?>, thanks for joining maqinato.</div>
                <div id="email">Please click this link to verify your account: <span id="resend">Verify Account</span></div>
            </div>
            <div id="advise">
                <!--<div id="text">After clicking you should receive an email, please check your spam folder if the email has not arrived within a couple of minutes.</div>-->
            </div>
        </div> <!-- END MAIN CONTENT -->
        <!-- INCLUDE FOOTER   -->
        <?php include_once Router::rel("templates").'footer.php'; ?>
    </body>
</html>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>maqinato :: Restore Password</title>
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
                $message='<div class="page-content" id="restore-pasword">
                            <div class="center"> 
                            <h1 class="change-pwr">Change your password</h1>
                                <p>
                                    <ul class="faces">
                                    <li>
                                        <p>
                                            <input type="password" id="new-pwr" name="new-pwr" maxlength="30" minlength="6" placeholder="New password">
                                            <div id="location-error"><span for="new-pwr" class="pass-error" id="new-error"></span></div>
                                        </p>
                                        <span class="inputRepeatPass">
                                            <input type="password" id="repeat-new-pwr" name="repeat-new-pw" maxlength="30"  placeholder="Confirm new password">
                                            <input  class="btn-change-pwr" id="change" type="submit" value="Edit">
                                        </span>
                                    <li>
                                    <h3 class="change-pwr" id="answer-change">Your password has been changed</h3>
                                    <p><input  class="btn-change-pwr" id="succes" type="submit" value="Edit"></p>
                                    </li>
                                    </ul>
                                </p>
                                </div>
                            </div>';
                //Destruye las sesiones anteriores para que el usario deba registrarse de nuevo
                AccessController::destroy();
            }else{
                $message='Incorrect key. <a id="resend" href="'.Router::serverUrl().'/'.Router::application().'/">Try again.</a> ';
            }
            //INCLUDE CSS SCRIPTS
            Router::css("reset", "jqueryui", "skeleton","layout","structure","pages","settings");
            //INCLUDE JS SCRIPTS
            //Basic
            Router::js("jquery","jqueryui","modernizr","system","tools");
            //Components
            Router::js("security","ajaxCore","ajaxSocial","access","daemons","notifications","settings","bonflip");
            
            //Write the main configuration variables in HTML to be readed from JS
            Router::configInHtml();
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                window.system=new System();
                system.init({
                    access:true,
                    settings:true
                });
            });
        </script>
    </head>
    <body id="restorePage">
        <?php include_once Router::rel("templates").'header.php'; ?>
        <section id="page">
            <div class="wrapper"><?php echo $message; ?></div>
            
        </secction> <!-- END MAIN CONTENT -->
        <!-- INCLUDE FOOTER   -->
        <?php include_once Router::rel("templates").'footer.php'; ?>
    </body>
</html>
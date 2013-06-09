<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>maqinato :: Settings</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <?php
            /**
            * Privacy page file
             * @author https://github.com/maparrar/maqinato
             * @package views
             * @subpackage pages
            */
            session_start();
            if(!class_exists('Router')) require_once '../../config/Router.php';
            include_once Router::rel('controllers').'AccessController.php';
            //Revisa si viene de https://github.com/maparrar/maqinato y redirecciona a https://www.https://github.com/maparrar/maqinato
            if(($_SERVER['SERVER_NAME']=="https://github.com/maparrar/maqinato"||$_SERVER['SERVER_NAME']=="www.https://github.com/maparrar/maqinato")&&!$_SERVER['HTTPS']){
                header("Location: " . "https://www.https://github.com/maparrar/maqinato/views/settings/");
                exit();
            }
            //If session wasn't started go to landing page and destroy the session
            $user=false;
            if (!AccessController::checkSession()){
                AccessController::destroy();
                header("Location: ".Router::rel('root'));
            }else{
                //Load the logged User
                $user=AccessController::getSessionUser();
                //Redirige a validar, si la cuenta no se ha validado
                if(!AccessController::validatedAccount($user)){
                    header("Location: ".Router::rel('transactions')."validateAccount.php");
                }
            }
            
            //INCLUDE CSS SCRIPTS
            Router::css("reset", "jqueryui", "skeleton","layout","structure","pages","settings");
            //INCLUDE JS SCRIPTS
            //Basic
            Router::js("jquery","jqueryui","modernizr","system","tools");
            //Components
            Router::js("security","ajaxCore","ajaxSocial","access","daemons","notifications","settings","bonflip","placeholder");
            
            //Write the main configuration variables in HTML to be readed from JS
            Router::configInHtml();
        ?>        
        <script type="text/javascript">
            $(document).ready(function(){
                window.system=new System();
                system.init({
                    access:true,
                    notifications:true,
                    session:true,
                    settings:true
                });
            });
        </script>
    </head>
    <body id="settingsPage">
        <?php
            //Load the user id
            if($user){
                echo '<input type="hidden" id="userId" value="user'.$user->getId().'" />';
            }
        ?>
        <!-- INCLUDE HEADER   -->
        <?php include Router::rel('web').'templates/header.php'; ?>
        <section id="page">
            <div class="wrapper">
                <aside>
                    <h1 class="change-pwr">Settings</h1>
                </aside>			
                <div class="page-content">
                    <ul class="faces">
                        <li>
                            <h3 class="change-pwr">Change your password</h3>
                            <p>
                                <input type="password" id="last-pwr" name="last-pwr" maxlength="30" minlength="6" placeholder="Old password">
                                
                            </p>
                            <p>
                                <input type="password" id="new-pwr" name="new-pwr" maxlength="30" minlength="6" placeholder="New password">
                                
                            </p>
                            <p>
                                <input type="password" id="repeat-new-pwr" name="repeat-new-pw" maxlength="30"  placeholder="Confirm new password">
                                <input  class="btn-change-pwr" id="change" type="submit" value="Edit">
                            </p>
                            
                        </li>
                        <li>
                            <h3 class="change-pwr" id="answer-change">Your password has been changed</h3>
                            <p><input  class="btn-change-pwr" id="succes" type="submit" value="Edit"></p>
                        </li>
                    </ul>
                    <span for="last-pwr" class="pass-error" id="last-error"></span>
                    <span for="new-pwr" class="pass-error" id="new-error"></span>
                </div>
            </div>
        </section>
        
        <!-- INCLUDE FOOTER   -->
        <?php include Router::rel('web').'templates/footer.php'; ?>
    </body>
</html>
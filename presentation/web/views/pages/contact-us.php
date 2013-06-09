<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>maqinato :: Contact us</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <?php
            /**
            * Contact us page file
             * @author https://github.com/maparrar/maqinato
             * @package views
             * @subpackage pages
            */
            session_start();
            if(!class_exists('Router')) require_once '../../config/Router.php';
            include_once Router::rel('controllers').'AccessController.php';
            include_once Router::rel('controllers').'ActivityController.php';
            include_once Router::rel('controllers').'SocialController.php';
            $user=false;
            //If session wasn't started go to landing page and destroy the session   
            if (!AccessController::checkSession()){
                AccessController::destroy();
            }else{
                //Load the logged User
                $user=AccessController::getSessionUser();
            }
            
            //INCLUDE CSS SCRIPTS
            Router::css("reset", "jqueryui", "skeleton","layout","structure","pages");
            //INCLUDE JS SCRIPTS
            //Basic
            Router::js("jquery","jqueryui","modernizr","system","tools");
            //Components
            Router::js("security","ajaxCore","ajaxSocial","access","daemons","notifications","placeholder");
            //Others
            Router::js();
            
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
                    contactus:true
                });  
            });
        </script>
    </head>
    <body id="contactPage">
        <!-- INCLUDE HEADER   -->
        <?php include Router::rel('web').'templates/header.php'; ?>
        <section id="page">
            <div class="wrapper">
                <aside>
                    <h1>Contact us</h1>
                </aside>			
                <div class="page-content">
                    <form class="contact-form">
                        <?php
                            if(!$user){
                                echo '<input type="text" id="email" placeholder="Email" />';
                            }
                        ?>
                        <p class="caption">Please feel free to reach out to us during the beta phase of our site. <br> We would love to know your thoughts and help you out in any way we can.</p>
                        <textarea id="message" placeholder="Leave your comment or feedback here"></textarea>
                        <input class="btn-enviar-feedback" type="submit" value="enviar">
                        <h3 class="response"></h3>
                    </form>
                   
                </div>
            </div>
        </section>
        <!-- INCLUDE FOOTER   -->
        <?php include Router::rel('web').'templates/footer.php'; ?>
    </body>
</html>
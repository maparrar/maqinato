<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width = device-width, initial-scale=1, maximum-scale=1"/>
        <?php
            //Incluye los CSS
            Router::css("general","jquery-ui","accessing");
            //Incluye los scripts de JS
            Router::js("basic","accessing");
            //Verifica si hay usuario válido registrado, si es así, redirige al main
            if(Maqinato::checkSession()){
                Router::redirect("main");
            }
            //Incluye los parámetros de la aplicación para ser leídos desde JS
            Maqinato::configInHtml();
        ?>
        <title><?php echo _("Accessing")." | ".ucfirst(Maqinato::application()); ?></title>
        <script type="text/javascript">
            $(document).ready(function(){
                //Script que controla la inicialización de todo el sistema
                window.maqinato=new Maqinato();
                maqinato.init();
                
                //Script propio de la página
                accessing=new Accessing();
                accessing.init({
                    signupForm:$("#signupForm"),
                    loginForm:$("#loginForm")
                });
              });
        </script>
    </head>
    <body>
        <section id="accessing" class="container">
            <?php Router::import("templates/accessHeader.php"); ?>
            <section class="content">
                <div id="signupForm">
                    <input id="sgn_name" type="text" placeholder="<?php echo _("Name"); ?>"/><br />
                    <input id="sgn_lastname" type="text" placeholder="<?php echo _("Lastname"); ?>"/><br />
                    <input id="sgn_email" type="text" placeholder="<?php echo _("Email"); ?>"/><br />
                    <input id="sgn_password" type="password" placeholder="<?php echo _("Password"); ?>"/><br />
                    <input id="sgn_confirm" type="password" placeholder="<?php echo _("Confirm password"); ?>"/><br />
                    <div id="signup" class="button"><?php echo _("Signup"); ?></div>
                </div>
            </section>
            <?php Router::import("templates/footer.php"); ?>
        </section>
    </body>
</html>
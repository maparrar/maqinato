<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width = device-width, initial-scale=1, maximum-scale=1"/>
        <?php
            //Incluye los CSS
            Router::css("general","accessing");
            //Incluye los scripts
            Router::js("basic");
        ?>
        <title><?php echo _("Accessing")." | ".ucfirst(Maqinato::application()); ?></title>
        <script type="text/javascript">
            $(document).ready(function(){
                window.maqinato=new Maqinato();
                maqinato.init();
                
                $("#login").click(function(){
                    maqinato.debug('Document: ['+$(document).height()+','+$(document).width()+'] - Window: ['+$(window).height()+','+$(window).width()+']');
                });
              });
        </script>
    </head>
    <body>
        <section class="container">
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
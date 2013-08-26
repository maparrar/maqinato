<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
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
                $("#login").click(function(){
                    maqinato.debug("test");
                });
              });
        </script>
    </head>
    <body>
        <div class="container">
            <?php Router::import("templates/accessHeader.php"); ?>
            <div id="signupForm">
                <?php echo _("Name"); ?>: <input id="sgn_name" type="text"/><br />
                <?php echo _("Lastname"); ?>: <input id="sgn_lastname" type="text"/><br />
                <?php echo _("Email"); ?>: <input id="sgn_email" type="text"/><br />
                <?php echo _("Password"); ?>: <input id="sgn_name" type="password"/><br />
                <div id="signup"><?php echo _("Signup"); ?></div>
            </div>
            <?php Router::import("templates/footer.php"); ?>
        </div>
    </body>
</html>
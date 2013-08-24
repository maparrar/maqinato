<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <?php
            //Incluye los scripts
            Router::js("jquery");
        ?>
        <title><?php echo _("Accessing")." | ".ucfirst(Maqinato::application()); ?></title>
        <script type="text/javascript">
            $(document).ready(function(){
                $("body").click(function(){
                    console.debug("click");
                });
              });
        </script>
    </head>
    <body>
        <div id="container">
            <?php 
                echo getcwd()."<br>";
                echo Router::path("root");
                require_once "engine/views/templates/header.php";
            ?>
            
            
            <div id="login">
                <?php echo _("Email"); ?>: <input type="text" name="email"/><br />
                <?php echo _("Password"); ?>: <input type="password" name="password"/><br />
                <div><?php echo _("Login"); ?></div>
            </div>
            <div id="signup">
                <?php echo _("Email"); ?>: <input type="text" name="email"/><br />
                <?php echo _("Password"); ?>: <input type="password" name="password"/><br />
                <div><?php echo _("Login"); ?></div>
            </div>
        </div>
    </body>
</html>
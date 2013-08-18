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
                  $(this).css("background","red");
                });
              });
        </script>
    </head>
    <body>
        <h1><?php echo _("Login in ").ucfirst(Maqinato::application()); ?></h1>
        <?php echo _("Username"); ?>: <input type="text" name="username"/><br />
        <?php echo _("Password"); ?>: <input type="password" name="password"/><br />
        <span><?php echo _("Login"); ?></span>
    </body>
</html>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <?php
            //Incluye los scripts
            Router::js("jquery");
            
            
        ?>
        <title>Landing | Maqinato</title>
        <script  type="text/javascript">
            $(document).ready(function(){
                $("body").click(function(){
                  $(this).css("background","red");
                });
              });
        </script>
    </head>
    <body>
        <h1>PÁGINA DE LANDING</h1>
        <hr/>
        <h2><?php echo _("Testing i18n"); ?></h2>
        <?php
            echo _("NEW text in English to translate to another language.")."<br/>";
            echo _("Maqinato is a non-framework tool.")."<br/>";
        ?>
        <hr/>
    </body>
</html>
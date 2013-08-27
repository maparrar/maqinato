<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width = device-width, initial-scale=1, maximum-scale=1"/>
        <?php
            //Incluye los CSS
            Router::css("general","jquery-ui");
            //Incluye los scripts de JS
            Router::js("basic");
            //Incluye los parámetros de la aplicación para ser leídos desde JS
            Maqinato::configInHtml();
        ?>
        <title><?php echo _("Main")." | ".ucfirst(Maqinato::application()); ?></title>
        <script type="text/javascript">
            $(document).ready(function(){
                //Script que controla la inicialización de todo el sistema
                window.maqinato=new Maqinato();
                maqinato.init();
              });
        </script>
    </head>
    <body>
        <section class="container">
            <?php Router::import("templates/header.php"); ?>
            <section class="content">
                MAIN PAGE
            </section>
            <?php Router::import("templates/footer.php"); ?>
        </section>
    </body>
</html>
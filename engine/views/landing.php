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
        
        <p>
            A good day, World!<br>
            Schönen Tag, Welt!<br>
            Une bonne journée, tout le monde!<br>
            يوم جيد، العالم<br>
            좋은 일, 세계!<br>
            Một ngày tốt lành, thế giới!<br>
            こんにちは、世界！<br>
        </p>
        <hr/>
        <h2>Pruebas de i18n</h2>
        <?php
            // Set language to German
            $language='en_GB';
            putenv('LC_ALL='.$language);
            setlocale(LC_ALL,$language);
            
            Maqinato::debug(getenv("LC_ALL"));

            // Specify location of translation tables
            $domain='messages';
            bindtextdomain($domain,"/var/www/maqinato/public/locale");
            // Choose domain
            textdomain($domain);
            
            
            echo gettext("Texto de prueba")."<br/>";
            echo _("Otra prueba")."<br/>";
            echo _("La ultima prueba")."<br/>";
        ?>
        <hr/>
    </body>
</html>
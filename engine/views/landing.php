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
            // Set language
            $language='es';
            putenv('LC_ALL='.$language);
            setlocale(LC_ALL,$language);

            // Specify location of translation tables
            $domain='messages';
            bindtextdomain($domain,"./locale");
            // El codeset del textdomain
            bind_textdomain_codeset($domain,'UTF-8');
            // Choose domain
            textdomain($domain);
            
            $cant=43;
            echo "<h4>"._("Hello world")."</h4><br/>";
            echo "<h4>"._("You have ").$cant._(" messages")."</h4><br/>";


            
        ?>
        <hr/>
    </body>
</html>
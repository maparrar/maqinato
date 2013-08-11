<!DOCTYPE HTML>
<html>
    <head>
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
    </body>
</html>
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
                  $(this).css("background","green");
                });
              });
        </script>
    </head>
    <body>
        <h1>PÁGINA DE HOME DEL USUARIO <?php 
                $request=Maqinato::request();
                echo $request["parameters"][0]; 
            ?></h1>
    </body>
</html>
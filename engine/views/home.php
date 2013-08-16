<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <?php
            //Incluye los scripts
            Router::js("jquery");
            
        ?>
        <title>Home | Maqinato</title>
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
//                echo $request["parameters"][0]; 
                
                
//                echo "<br/><h4>"._("Test in home page")."</h4><br/>";
            ?></h1>
    </body>
</html>
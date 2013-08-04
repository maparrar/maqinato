<!DOCTYPE HTML>
<html>
    <head>
        <?php
            //Incluye los scripts
            Router::js("jquery");
        ?>
        <script  type="text/javascript">
            $(document).ready(function(){
                $("body").click(function(){
                  $(this).css("background","red");
                });
              });
        </script>
    </head>
    <body>
        
    </body>
</html>
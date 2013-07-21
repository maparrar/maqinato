<!DOCTYPE html>
<html>
    <head>
        <script src="public/js/jquery/jquery-2.0.3.min.js" type="text/javascript" ></script>
        <script>
            $(document).ready(function(){
                $("#test").click(function(){
                    $("body").css("background","red");
                    $(this).hide();
                });
                
                console.debug(navigator);
                console.debug(JSON.stringify(navigator));
                
                
//                jQuery.each(jQuery.browser, function(i, val) {
//                    console.debug(i+": "+val);
//                });
            });
        </script>
    </head>
    <body>
        <div id="test">PRUEBA</div>
    </body>
</html>
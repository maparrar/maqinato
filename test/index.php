<!DOCTYPE html>
<html>
    <head>
        <title>Maqinato - test</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--PLUGINS-->
        <script type="text/javascript" src="plugins/jquery-1.7.1.min.js"></script>
        
        <!--SCRIPTS-->
        <script type="text/javascript" src="scripts/Foo.js"></script>
        

        <!--STYLES-->
<!--        <link rel="stylesheet" type="text/css" href="../vista/plugins/custom-theme/jquery-ui-1.8.16.custom.css"/> Versión 1.8.10 Estilo:start-->
    </head>
    <body>
        <div id="act1">Increment</div>
        <div id="act2">Decrement</div>
        <div id="act3">Use the static Method</div>
        <div id="act4">Testing the constructor</div>
        <div id="act5">Use the private method called by another</div>
                
        <script type="text/javascript">
            //Execution
            $(document).ready(function() {
                var foo=new Foo("parameterValue","another");
                
                $("#act1").click(function(){
                    foo.increment();
                });
                $("#act2").click(function(){
                    foo.decrement();
                });
                $("#act3").click(function(){
                    Foo.staticMethod();
                });
                $("#act4").click(function(){
                    foo.getParam();
                });
                $("#act5").click(function(){
                    foo.usePrivateMethod();
                });
                
            });
        </script>
    </body>
</html>

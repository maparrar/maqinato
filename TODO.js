

//Consider Require.js to load files and Classes






//DIRECTIVES (OOP):
/*  NAMESPACES  */
    //Namespace declaration
    var SPACE={}
    //Namespace function
    SPACE.foo=function(param){
        alert(param);
    }
    //Sub-namespace declaration and function
    SPACE.Util={};
    SPACE.Util.bar=function(param){
        alert(param);
    }

/*  CLASSES  */
//    //Definition:
//        Foo=function(param){
//            //Private variables
//            var priVar=0;
//            var parameter=param;
//            //Public methods
//            return{
//                Foo:function(){
//                    console.debug("Parameter value: "+parameter);
//                },
//                decrement:function(){
//                    console.debug(--priVar);
//                },
//                increment:function(){
//                    console.debug(++priVar);
//                }
//            }
//        };
//
//    //Use:
//        var foo=new Foo();
//        foo.decrement();
//        
//    //Static Methods
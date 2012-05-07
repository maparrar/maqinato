/**
 *CLASS IN JAVASCRIPT
 *  Constructor emulated passed params in class declaration: i.e. param
 *  Proxy Pattern + obj
 **/
var Foo=function(param1,param2){
    "use strict";
    //Create an obj variable to manage this without conflicts with another this uses
    var obj=this;
    //Private variables
    var priVar=0;
    var parameter1=param1;
    var parameter2=param2;
    
    //Private Methods
    var privateMethod=function(){
        console.debug("I'm a private method, only accesible from the Object");
    };
    
    //Public Methods
    obj.decrement=function(){
        return --priVar;
    };
    obj.increment=function(){
        console.debug(++priVar);
    };
    obj.getParam=function(){
        console.debug("The parameter1 passsed in constructor is: "+parameter1+" - parameter 2: "+parameter2);
    };
    obj.usePrivateMethod=function(){
        privateMethod();
    };
};

/**
 *STATIC METHOD
 *  - Only works with the class
 *  - Called by object return error
 *  - Use:
 *      Foo.staticMethod();
 **/
Foo.staticMethod=function(){
    "use strict";
    console.debug("I'm a static method of Foo class");
};

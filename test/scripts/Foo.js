/**
 *CLASS IN JAVASCRIPT
 *  Constructor emulated passed params in class declaration: i.e. param
 **/

Foo=function(param1,param2){
    //Private variables
    var priVar=0;
    var parameter1=param1;
    var parameter2=param2;
    
      
    //Private Methods
    var privateMethod=function(){
        console.debug("I'm a private method, only accesible from the Object");
    };
    
    //Test if 'arguments' variable can perform the override property
    console.warn("My arguments are: ");
    console.debug(arguments);
    
    //Public Methods
    return{
        decrement:function(){
            console.debug(--priVar);
        },
        increment:function(){
            console.debug(++priVar);
        },
        getParam:function(){
            console.debug("The parameter1 passsed in constructor is: "+parameter1+" - parameter 2: "+parameter2);
        },
        usePrivateMethod:function(){
            privateMethod();
        }
    }
    
    
};

/**
 *STATIC METHOD
 *  - Only works with the class
 *  - Called by object return error
 *  - Use:
 *      Foo.staticMethod();
 **/
Foo.staticMethod=function(){
    console.debug("I'm a static method of Foo class");
};
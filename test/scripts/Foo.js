/**
 *CLASS IN JAVASCRIPT
 **/

Foo=function(param){
    //Private variables
    var priVar=0;
    var parameter1=param;
    
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
            console.debug("The parameter passsed in constructor is: "+parameter1);
        },
        usePrivateMethod:function(){
            privateMethod();
        }
    }
    
    
};

/**
 *STATIC METHOD
 *  - Only works with class
 *  - Called by object return error
 *  - Use:
 *      Foo.staticMethod();
 **/
Foo.staticMethod=function(){
    console.debug("I'm a static method of Foo class");
};
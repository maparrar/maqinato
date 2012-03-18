/**
 *CLASS IN JAVASCRIPT
 **/

Foo=function(param){
    //Private variables
    var priVar=0;
    var parameter1=param;
    
    //Public Methods
    return{
        //Methods
        decrement:function(){
            console.debug(--priVar);
        },
        increment:function(){
            console.debug(++priVar);
        },
        getParam:function(){
            console.debug("The parameter passsed in constructor is: "+parameter1);
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
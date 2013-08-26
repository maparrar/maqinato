/**
 * Pseudoclass to manage the Accessing page
 **/
function Accessing(){
    "use strict";
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the systems Scripts 
     **/
    obj.init=function(opts){
        //Genera los eventos para los objetos del home
        obj.events();
    };
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(){
        maqinato.dialog({
            title:"título de testing",
            html:"testing"
        });
    };
    
    
    
    
    
    
    /**
     *  Main method to signup, executed when the signup event is executed
     **/
    obj.signup=function(){
        var signupForm=opts.signupForm;
        var name=signupForm.find("#name").val();
        var lastname=signupForm.find("#lastname").val();
        var sex="I";
        var country=signupForm.find("#country").val();
        var city=signupForm.find("#cityinput").val();
        var idCity=signupForm.find("#cityinput").attr("idCity");
        var email=signupForm.find("#email").val();
        var password=$.trim(signupForm.find("#password").val());
        var confirm=signupForm.find("#confirm").val();
        var terms=signupForm.find("#terms-link").attr("acceptedTerms");
        if(name.length>2&&$.trim(name)!=""){
            if(lastname.length>2&&$.trim(lastname)!=""){
//                if(system.security.secureString(sex)!=""){ 
                    if(system.security.isemail(email)){
//                        if(system.security.secureString(country)!=""){
//                            if(system.security.secureString(city)!=""){
                                if(system.security.ispassword(password)){
                                    if(password===confirm){
                                        if(terms=="true"){
                                            country="USA";
                                            idCity=3805;
                                            opts.signupCallback(name,lastname,sex,email,idCity,city,country,password,function(joined){
                                                if(!joined){
                                                    signupForm.find("#new-password, #confirm").val("").addClass("errorSignup").attr("Placeholder","Or password.");
                                                    signupForm.find("#email").val("").addClass("errorSignup").attr("Placeholder","Invalid email.");
                                                }else if(joined==="exist"){
                                                    signupForm.find("#email").val("").addClass("errorSignup").attr("Placeholder","The email is already registered");
                                                }else if(joined==="registered"){
                                                    var html='<div id="coming_modal">'+
                                                                '<img src="'+system.rel("img")+'coming_modal.png" />'+
                                                                '<div id="coming_modal_text">Welcome, you are now one of our first users.</div>'+
                                                                '<div id="coming_modal_text">For now, follow us on <a id="coming_facebook" href="https://www.facebook.com/bonfolio" target="_blank">Facebook</a> and <a id="coming_twitter" href="https://twitter.com/bonfolio" target="_blank">Twitter</a> until you can start doing good.</div>'+
                                                            '</div>';
                                                    system.dialog({
                                                        html:html,
                                                        height:218,
                                                        width:630,
                                                        position:{
                                                            my: "center top",
                                                            at: "center top",
                                                            of: $(".highlights").find("img")
                                                        }
                                                    });
                                                    obj.signupReset();
                                                }
                                            });
                                        }else{
                                            obj.terms();
                                            obj.enterIn="signup"; 
                                        }
                                    }else{
                                        signupForm.find("#password, #confirm").val("").addClass("errorSignup").attr("Placeholder","The password and confirmation does not match.");
                                        obj.enterIn="signup";
                                    }
                                }else{
                                    $("#errorData").show();
                                    signupForm.find("#password").val("").addClass("errorSignup").attr("Placeholder","Password must be between 6 and 18 characters. Only accept special characters: @#$%_-.");
                                    obj.enterIn="signup";
                                }
//                            }else{
//                                signupForm.find("#city").val("").addClass("errorSignup");
//                                obj.enterIn="signup";         
//                            }
//                        }else{
//                            signupForm.find("#country").val("").addClass("errorSignup");
//                            obj.enterIn="signup";         
//                        }
                    }else{
                        $("#errorData").show();
                        signupForm.find("#email").val("").addClass("errorSignup").attr("Placeholder","Type: something@example.com.");
                        obj.enterIn="signup"; 
                    }
//                }else{
//                    signupForm.find("#sex").val("").addClass("errorSignup");
//                    obj.enterIn="signup";
//                }
            }else{
                $("#errorData").show();
                signupForm.find("#lastname").val("").addClass("errorSignup").attr("Placeholder","The lastname can not be empty.");
                obj.enterIn="signup";
            }
        }else{
            $("#errorData").show();
            signupForm.find("#name").val("").addClass("errorSignup").attr("Placeholder","The name can not be empty.");
            obj.enterIn="signup";
        }
    };
    /**
     * Clear the login fields
     **/
    obj.signupReset=function(){
        var signupForm=opts.signupForm;
        signupForm.find("#country").val("");
        signupForm.find("#new-password").val("");
        signupForm.find("#confirm").val("");
        signupForm.find("#email").val("");
        signupForm.find("#sex").val("");
        signupForm.find("#lastname").val("");
        signupForm.find("#name").val("").focus();
        $(document).scrollTop(0);
    };
    /**
     *  Main method to login, executed when the login event is executed
     **/
    obj.login=function(){
        var loginForm=opts.loginForm;
        var email=loginForm.find("#email").val();
        var password=loginForm.find("#password").val();
        var keep=loginForm.find("#keep").is(':checked');
        if(system.security.isemail(email)){
            if(system.security.ispassword(password)){
                opts.loginCallback(email,password,keep,function(access){
                    obj.loginReset();
                    if(access=="fails"){
                        system.message("Many failed attempts. The system will be locked to that IP for a few minutes.","",false);
                    }else if(access=="error"){
                        obj.loginReset();
                        loginForm.find("#email").addClass("errorForm").attr("Placeholder","Invalid email or password. Try again.");
                        loginForm.find("#password").addClass("errorForm");
                    }
                });
            }else{
                loginForm.find("#password").val("").addClass("errorForm").attr("Placeholder","Must be between 6 and 18 characters.");
            }
        }else{
            loginForm.find("#email").val("").addClass("errorForm").attr("Placeholder","Should be formatted: something@example.com.");
        }
    };
    /**
     * Clear the login fields
     **/
    obj.loginReset=function(){
        var loginForm=opts.loginForm;
        loginForm.find("#password").val("");
        loginForm.find("#email").val("").focus();
    };
}
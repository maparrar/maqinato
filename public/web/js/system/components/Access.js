/**
 * Pseudoclass to manage the Access functions
 * The opts variable must contains:
 * 
 *  opts.loginForm: Selector with the elements:
 *      "#email"        Email input
 *      "#password"     Password input
 *      "#keep":        Keep conected checkbox
 *      "#forgot":      Forgot your password link
 *      "#login":       Login button
 *  opts.loginCallback: Callback funtion when try login
 *  
 *  opts.logoutButton: Selector to exit
 *  opts.logoutCallback: Callback funtion when logout
 *  
 *  opts.signupForm: Selector with elements
 *      "#name"         Name input
 *      "#lastname"     Lastname input
 *      "#email"        Email input
 *      "#password"     Password input
 *      "#confirm":     Confirm password input
 *      "#signup":      Signup button
 *  opts.signupCallback: Callback funtion when try signup
 *
 **/
function Access(opts){
    "use strict";
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.enterIn="login"
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the systems Scripts 
     **/
    obj.init=function(){
        obj.events();
        //Objeto newsfeed
    }
    
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(){
        obj.signupForm=opts.signupForm;
        obj.loginForm=opts.loginForm;
        //Switch between login and signup
        obj.loginForm.find("#gotoSignup").click(function(e){
            e.preventDefault();
            obj.showSignup();
        });
        obj.signupForm.find("#login-show").click(function(e){
            e.preventDefault();
            obj.showLogin();
        });
        
        //Manage the enter event
        obj.loginForm.find("#email,#password,#keep").click(function(){
            obj.enterIn="login";
        });
        obj.signupForm.find("#name,#lastname,#email,#password,#confirm").click(function(){
            obj.enterIn="signup";
        });
        //When hit the enter key
        $(document).keypress(function(event) {
            if (event.which==13) {
                if(obj.enterIn=="login"){
                    obj.loginForm.find("#login").click();
                }else{
//                    signupForm.find("#signup").click();
                }
            }
        });
                
        //Signup Events
        obj.signupForm.find("#signup").click(function(e){
            e.preventDefault();
            obj.signup();
        });
        obj.signupForm.find("input, select").focus(function(){
            obj.signupForm.find("input, select").removeClass("errorSignup");
        });
        
        //Login Events
        obj.loginForm.find("#login").click(function(e){
            e.preventDefault();
            obj.login();
        });
        
        //remove error class
        obj.loginForm.find("#email").keyup(function(){
        $(this).removeClass('errorForm');
        });
        //remove error class
        obj.loginForm.find("#password").keyup(function(){
        $(this).removeClass('errorForm');
        });
        
        //Logout Event
        opts.logoutButton.click(function(e){
            e.preventDefault();
            system.isLogout=true;
            opts.logoutCallback();
        });
        //abre los terminos
        obj.signupForm.find("#terms-link").click(function(e){
            e.preventDefault();
            obj.terms();
        });
    }
    /**
     * Muestra el form de registro
     * */
    obj.showSignup=function(){
        obj.loginForm.hide();
        obj.signupForm.show();
        obj.enterIn="signup";
    }
    /**
     * Muestra el form de login
     * */
    obj.showLogin=function(){
        obj.signupForm.hide();
        obj.loginForm.show();
        obj.enterIn="login";
    }
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
        var password=signupForm.find("#new-password").val();
        var confirm=signupForm.find("#confirm").val();
        var terms=signupForm.find("#terms-link").attr("acceptedTerms");
        if(name.length>2&&$.trim(name)!=""){
            if(lastname.length>2&&$.trim(lastname)!=""){
//                if(system.security.secureString(sex)!=""){ 
                    if(system.security.isemail(email)){
                        if(system.security.secureString(country)!=""){
                            if(system.security.secureString(city)!=""){
                                if(system.security.ispassword(password)){
                                    if(password===confirm){
                                        if(terms=="true"){
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
                                                                '<div id="coming_modal_text">For now, follow us on <a id="coming_facebook" href="https://www.facebook.com/maqinato" target="_blank">Facebook</a> and <a id="coming_twitter" href="https://twitter.com/maqinato" target="_blank">Twitter</a> until you can start doing good.</div>'+
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
                                        signupForm.find("#new-password, #confirm").val("").addClass("errorSignup").attr("Placeholder","The password and confirmation does not match.");
                                        obj.enterIn="signup";
                                    }
                                }else{
                                    signupForm.find("#password").val("").addClass("errorSignup").attr("Placeholder","Password must be between 6 and 18 characters. Only accept special characters: @#$%_-.");
                                    obj.enterIn="signup";
                                }
                            }else{
                                signupForm.find("#city").val("").addClass("errorSignup");
                                obj.enterIn="signup";         
                            }
                        }else{
                            signupForm.find("#country").val("").addClass("errorSignup");
                            obj.enterIn="signup";         
                        }
                    }else{
                        signupForm.find("#email").val("").addClass("errorSignup").attr("Placeholder","Type: something@example.com.");
                        obj.enterIn="signup"; 
                    }
//                }else{
//                    signupForm.find("#sex").val("").addClass("errorSignup");
//                    obj.enterIn="signup";
//                }
            }else{
                 signupForm.find("#lastname").val("").addClass("errorSignup").attr("Placeholder","The lastname can not be empty.");
                obj.enterIn="signup";
            }
        }else{
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
    // modales terminos y privacidad
    obj.terms=function(){
        var html=$("#modals").find("#modal-terms").html();
        var dialogTerms=system.dialog({
            html:html,
            height:580,
            width:620
        });
        dialogTerms.find("#btn-terms").click(function(e){
            e.preventDefault();
            var termsCheck=dialogTerms.find("#terms");
            if(termsCheck.is(':checked')){
                $("#terms-link").attr("acceptedTerms","true");
                $("#terms-link").removeClass("errorSignup");
                obj.signup();
            }else{
                $("#terms-link").addClass("errorSignup");
            }  
            system.dialogClose(dialogTerms);
        });
    };
                
        $("#privacy-link").click(function(e){
            e.preventDefault();
            //Crea el modal de Good deeds
            var html=$("#modals").find("#modal-privacy").html();
            var dialogPrivacy=system.dialog({
                html:html,
                height:580,
                width:620
            });
            dialogPrivacy.find("#btn-privacy").click(function(e){
            e.preventDefault();
            system.dialogClose(dialogPrivacy);
            });
            
        });
        
};
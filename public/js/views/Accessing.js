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
    obj.init=function(parameters){
        //Genera los eventos para los objetos del home
        obj.events(parameters);
    };
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(parameters){
        parameters.signupForm.find(".button").click(function(){
            obj.signup(parameters.signupForm);
        });
        parameters.loginForm.find(".button").click(function(){
            obj.login(parameters.loginForm);
        });
        parameters.signupForm.find("input").click(function(){
            $(this).removeClass("errorPlaceholder")
        });
        parameters.loginForm.find("input").click(function(){
            $(this).removeClass("errorPlaceholder")
        });
    };
    
    
    /**
     * Clear the fields of a form
     * @param {Object} form Formulario para limpiar
     **/
    obj.formReset=function(form){
        form.find("input").val("");
        form.find("input").first().val("").focus();
    };
    
    
    
    /**
     *  Main method to signup, executed when the signup event is executed
     **/
    obj.signup=function(form){
        var fields=obj.signupValidate(form);
        if(fields){
            
            maqinato.debug(fields);
            
//            maqinato.ajax.signup(email,password,name,lastname,function(joined){
//                if(!joined){
//                    signupForm.find("#new-password, #confirm").val("").addClass("errorSignup").attr("Placeholder","Or password.");
//                    signupForm.find("#email").val("").addClass("errorSignup").attr("Placeholder","Invalid email.");
//                }else if(joined==="exist"){
//                    signupForm.find("#email").val("").addClass("errorSignup").attr("Placeholder","The email is already registered");
//                }else if(joined==="registered"){
//                    var html='<div id="coming_modal">'+
//                                '<img src="'+system.rel("img")+'coming_modal.png" />'+
//                                '<div id="coming_modal_text">Welcome, you are now one of our first users.</div>'+
//                                '<div id="coming_modal_text">For now, follow us on <a id="coming_facebook" href="https://www.facebook.com/bonfolio" target="_blank">Facebook</a> and <a id="coming_twitter" href="https://twitter.com/bonfolio" target="_blank">Twitter</a> until you can start doing good.</div>'+
//                            '</div>';
//                    system.dialog({
//                        html:html,
//                        height:218,
//                        width:630,
//                        position:{
//                            my: "center top",
//                            at: "center top",
//                            of: $(".highlights").find("img")
//                        }
//                    });
//                    obj.signupReset();
//                }
//            });
//            
//            obj.formReset(form);
        }
    };
    /**
     * Valida los campos del formulario de registro
     * @param {Object} form Formulario a verificar
     * @return {mixed} false si los datos no son válidos, un obejto con los valores
     *      si los datos son válidos
     * */
    obj.signupValidate=function(form){
        var email=form.find("#sgn_email");
        var password=form.find("#sgn_password");
        var confirm=form.find("#sgn_confirm");
        var name=form.find("#sgn_name");
        var lastname=form.find("#sgn_lastname");
        var fields={};
        if($.trim(name.val()).length>2){
            fields.name=$.trim(name.val());
            if($.trim(lastname.val()).length>2){
                fields.lastname=$.trim(lastname.val());
                if(Security.isEmail($.trim(email.val()))){
                    fields.email=$.trim(email.val());
                    if(Security.isPassword($.trim(password.val()))){
                        if($.trim(password.val())===$.trim(confirm.val())){
                            fields.password=$.trim(password.val());
                        }else{
                            confirm.val("").addClass("errorPlaceholder").attr("Placeholder","The password and confirmation does not match.");
                            fields=false;
                        }
                    }else{
                        password.val("").addClass("errorPlaceholder").attr("Placeholder","Password must be between 6 and 18 characters. Only accept special characters: @#$%_-.");
                        fields=false;
                    }
                }else{
                    email.val("").addClass("errorPlaceholder").attr("Placeholder","Type: something@example.com.");
                    fields=false;
                }
            }else{
                lastname.val("").addClass("errorPlaceholder").attr("Placeholder","The lastname can not be empty.");
                fields=false;
            }
        }else{
            name.val("").addClass("errorPlaceholder").attr("Placeholder","The name can not be empty.");
            fields=false;
        }
        return fields;
    };
    
    /**
     *  Main method to login, executed when the login event is executed
     **/
    obj.login=function(form){
        var fields=obj.validateLogin(form);
        if(fields){
            maqinato.debug(fields);
            obj.formReset(form);
        }
        
        
        
//        var loginForm=opts.loginForm;
//        var email=loginForm.find("#email").val();
//        var password=loginForm.find("#password").val();
//        var keep=loginForm.find("#keep").is(':checked');
//        if(system.security.isemail(email)){
//            if(system.security.ispassword(password)){
//                opts.loginCallback(email,password,keep,function(access){
//                    obj.loginReset();
//                    if(access=="fails"){
//                        system.message("Many failed attempts. The system will be locked to that IP for a few minutes.","",false);
//                    }else if(access=="error"){
//                        obj.loginReset();
//                        loginForm.find("#email").addClass("errorForm").attr("Placeholder","Invalid email or password. Try again.");
//                        loginForm.find("#password").addClass("errorForm");
//                    }
//                });
//            }else{
//                loginForm.find("#password").val("").addClass("errorForm").attr("Placeholder","Must be between 6 and 18 characters.");
//            }
//        }else{
//            loginForm.find("#email").val("").addClass("errorForm").attr("Placeholder","Should be formatted: something@example.com.");
//        }
    };
    /**
     * Valida los campos del formulario de login
     * @param {Object} form Formulario a verificar
     * @return {mixed} false si los datos no son válidos, un obejto con los valores
     *      si los datos son válidos
     * */
    obj.validateLogin=function(form){
        var email=form.find("#email");
        var password=form.find("#password");
        var fields={};
        if(Security.isEmail($.trim(email.val()))){
            fields.email=$.trim(email.val());
            if(Security.isPassword($.trim(password.val()))){
                fields.password=$.trim(password.val());
            }else{
                password.val("").addClass("errorPlaceholder").attr("Placeholder","Password must be between 6 and 18 characters. Only accept special characters: @#$%_-.");
                fields=false;
            }
        }else{
            email.val("").addClass("errorPlaceholder").attr("Placeholder","Type: something@example.com.");
            fields=false;
        }
        return fields;
    };
}
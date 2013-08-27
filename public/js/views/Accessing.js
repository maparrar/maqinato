/**
 * Pseudoclass to manage the Accessing page
 **/
function Accessing(){
    "use strict";
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.currentForm="login";
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
        }).focus(function(){
            obj.currentForm="signup";
        });
        parameters.loginForm.find("input").click(function(){
            $(this).removeClass("errorPlaceholder")
        }).focus(function(){
            obj.currentForm="login";
        });
        parameters.loginForm.find("input").first().focus();
        //Cuando se hace click en enter
        $(document).keypress(function(e) {
            if(e.which===13) {
                if(obj.currentForm==="login"){
                    obj.login(parameters.loginForm);
                }else{
                    obj.signup(parameters.signupForm);
                }
            }
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
     *  @param {Object} form Formulario de registro
     **/
    obj.signup=function(form){
        var fields=obj.signupValidate(form);
        if(fields){
            maqinato.ajax.signup(fields.email,fields.password,fields.name,fields.lastname,function(response){
                if(response==="exist"){
                    maqinato.dialog({
                        title:_("Already registered"),
                        html:_("The email is already registered, please try again.")
                    });
                }else if(response==="success"){
                    maqinato.redirect("home");
                }else{
                    maqinato.dialog({
                        title:_("Invalid email or password"),
                        html:_("Verify the data and try again.")
                    });
                }
            });
            obj.formReset(form);
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
                            confirm.val("").addClass("errorPlaceholder").attr("Placeholder",_("No coincide con el password"));
                            fields=false;
                        }
                    }else{
                        password.val("").addClass("errorPlaceholder").attr("Placeholder",_("Entre 6 and 18 characters: @#$%_-."));
                        fields=false;
                    }
                }else{
                    email.val("").addClass("errorPlaceholder").attr("Placeholder",_("correo@ejemplo.com"));
                    fields=false;
                }
            }else{
                lastname.val("").addClass("errorPlaceholder").attr("Placeholder",_("El apellido no puede estar vacío"));
                fields=false;
            }
        }else{
            name.val("").addClass("errorPlaceholder").attr("Placeholder",_("El nombre no puede estar vacío"));
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
            maqinato.ajax.login(fields.email,fields.password,fields.keep,function(response){
                if(response==="success"){
                    maqinato.redirect("home");
                }else{
                    maqinato.dialog({
                        title:_("Invalid email or password"),
                        html:_("Verify the data and try again.")
                    });
                }
            });
            obj.formReset(form);
        }
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
        var keep=form.find("#keep").is(':checked');
        var fields={};
        if(Security.isEmail($.trim(email.val()))){
            fields.email=$.trim(email.val());
            if(Security.isPassword($.trim(password.val()))){
                fields.password=$.trim(password.val());
                fields.keep=true;
            }else{
                password.val("").addClass("errorPlaceholder").attr("Placeholder",_("Entre 6 and 18 characters: @#$%_-."));
                fields=false;
            }
        }else{
            email.val("").addClass("errorPlaceholder").attr("Placeholder",_("correo@ejemplo.com"));
            fields=false;
        }
        return fields;
    };
}
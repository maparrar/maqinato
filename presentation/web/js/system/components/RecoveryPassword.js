/**
 * Pseudoclass to manage the Good Deeds' Modal
 **/
function RecoveryPassword(){
    "use strict";
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


    /**
     * Set the login, logout and signup events
     **/
     obj.init=function(dialogo){
        obj.modal=dialogo;
        //Check if the Good Deeobj.RecoveryPassword=new Nonprofits();ds modal was loaded
        if(!obj.modal.exist()){
            system.debug("recovery-password.php was not included");
        }else{
            obj.events();
        }
    }
    obj.events=function(){  
        var send=obj.modal.find(".btn-recovery");
        //Send Button events
        send.click(function(e){
            e.preventDefault();
            var email=obj.modal.find("#email-recovery").val();
            if(system.security.isemail(email)){
                system.ajaxRecoveryPassword.sendMail({email:email},function(data){
                    if(data.recovery){
                        obj.modal.find(".pass-error").css("color","#0C2032").html("We have sent you an email to recover your password.");
                        setTimeout(function(){system.dialogClose(obj.modal)},3000);
                    }else{
                        obj.modal.find(".pass-error").html("The email does not exist");
                    }
                });                   
            }else{
                obj.modal.find(".pass-error").html("The email should be: something@example.com");
            }   
        });      
    };
}
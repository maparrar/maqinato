/**
 * Clase para invitar amigos
 **/
function Invite(){
    "use strict";
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the Script
     **/
    obj.init=function(){
        //Crea el modal de Good deeds
        obj.html=$("#modals").find("#modalInvite").html();//trae contenido html
    };
    /**
     * Muestra el cuadro de diálogo cargado den el html
     **/
    obj.show=function(predefined){
        obj.dialog=system.dialog({
            html:obj.html,
            height:"auto",
            width:380
        });
        obj.events();
        //Si viene un predefinido se inserta en el cuadro de búsqueda
        if(predefined){
            obj.dialog.find("#emails").val(predefined);
        }
    };
    /**
     * Ejecuta los eventos del modal
     * */
    obj.events=function(){
        var send=obj.dialog.find("#send");
        var failSection=obj.dialog.find("#failSection");
        var fails=obj.dialog.find("#fails");
        var failMessage=obj.dialog.find("#failMessage");
        var succesMessage=obj.dialog.find("#succesMessage");
        send.click(function(){
            if($.trim(obj.dialog.find("#emails").val())!==""){
                var emails=obj.dialog.find("#emails").val().split(/[\s;,]+/);
                var valid=new Array();
                var invalid=new Array();
                failSection.find("#fails").empty();
                for(var i in emails){
                    var email=system.security.secureString($.trim(emails[i]));
                    if(system.security.isemail(email)){
                        valid.push(email);
                    }else{
                        invalid.push(email);
                    }
                }
                if(valid.length+invalid.length>0){
                    system.ajaxSocial.inviteByMail(valid,invalid,function(data){
                        obj.dialog.find("#emails").val("");
                        var failEmails=data.invalid;
                        if(emails!=""){
                            if(failEmails&&failEmails.length>0){
                                failSection.show();
                                failMessage.html("The following email could not be sent or is already user: ");
                                for(var j in failEmails){


                                    fails.append('<div class="fail">'+failEmails[j]+'</div>');
                                }
                            }else{
                                failSection.show();
                                failMessage.hide();
                                succesMessage.html("Invitations were successfully sent.")
                                setTimeout(function() {
                                    system.dialogClose(obj.dialog); 
                                }, 3000);            
                            }
                        }else{
                            failSection.show();
                            failMessage.html("Type one email.");
                        }
                    });
                }
            }else{
                failSection.show();
                failMessage.html("Type one email.");
            }
        });
    };
}

/**
 * Pseudoclass to manage the Good Deeds' Modal
 **/
function Nonprofits(){
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
        //Check if the Good Deeds modal was loaded
        if(!obj.modal.exist()){
            system.debug("nonprofits.php was not included");
        }else{
            obj.events();
        }
    }
    obj.events=function(){
        var tags=obj.modal.find(".tag");
        var send=obj.modal.find(".btn-nonprofits");
        var checkers=obj.modal.find(".checkArea");
        
        //Tags events
        tags.click(function(e){
            e.preventDefault();
            var tag=$(this);
            if(tag.hasClass("active")){
                tag.removeClass("active");
            }else{
                tag.addClass("active");
            }
        });
        //Evento de los checkbox para seleccionar todos o ninguno dentro de los tags
        checkers.change(function(e){
            var area=$(this).closest(".area").attr("id");
            if($(this).is(':checked')){
                obj.modal.find("."+area).addClass("active");
            }else{
                obj.modal.find("."+area).removeClass("active");
            }
        });
        //form events
        obj.modal.find("input, select").focus(function(){
           obj.modal.find("input, select").removeClass("errorNonprofit");
        });
        
        send.click(function(e){
            e.preventDefault();
            var activeTags=obj.modal.find(".active");
            var checked=obj.checkForm();
            if(typeof(checked)==="object"){
                if(activeTags.length>0){
                    system.ajaxNonprofits.saveNonprofit(checked,function(data){
                        if(data.nonprofit=="false"){
                            system.message("The nonprofits exist","",false);
                        }else{    
                            system.message("The nonprofits has been create","",false);
                        }
                    });                   
                }else{
                    system.message("Select at least one Tag","",false);
                }
            }else{
                system.message(checked,"",false);
            }   
            });      
        //Eventos de bonslider
        var tags=obj.modal.find(".tags");
        tags.bonslider();
    };
    
    obj.checkForm=function(){
        var response=false;
        
        var orgName=obj.modal.find("#organization-name-nonprofit").val();
        var resOrgName=system.security.secureString(orgName);
        
        var mail=obj.modal.find("#mailing-address").val();
        var resMail=system.security.secureString(mail);
        
        var ein=obj.modal.find("#ein").val();
        var resEin=system.security.isInt(parseInt(ein));
        system.debug(typeof(resEin));
        
        var contact=obj.modal.find("#contact").val();
        var resContact=system.security.secureString(contact);
        
        var phone=obj.modal.find("#phone").val();
        var resPhone=system.security.secureString(phone);

        var contactEmail=obj.modal.find("#contac-email-address").val();
        var resContactEmail=system.security.isemail(contactEmail);
            
        var bankName=obj.modal.find("#bank-name").val();
        var resBankName=system.security.secureString(bankName);
        
        var routerSwitch=obj.modal.find("#router-switch-number").val();
        var resRouterSwitch=system.security.secureString(routerSwitch);
        
        var bankNumberAccount=obj.modal.find("#bank-account-number").val();
        var resBankNumberAccount=system.security.secureString(bankNumberAccount);
        
        var paypalEmail=obj.modal.find("#paypal-email-address").val();
        var resPaypalEmail=system.security.isemail(paypalEmail);
        
        var preferredMethod=parseInt(obj.modal.find("#preferred-method").val());
        var resPreferredMethod=system.security.isInt(preferredMethod);
     
        var tags=new Array();
        obj.modal.find(".active").each(function(){
            tags.push(parseInt($(this).attr("id").replace("tag","")));
        });
        if(resOrgName.length<1){
            obj.modal.find("#organization-name-nonprofit").val("").addClass("errorNonprofit").attr("Placeholder","The name can not be empty.");
        }if(resMail==false){
            resMail="";
        }if(resEin==false){
            obj.modal.find("#ein").val("").addClass("errorNonprofit").attr("Placeholder","The EIN can not be empty.");
        }if(resContact.length<1){
            obj.modal.find("#contact").addClass("errorNonprofit").val("").attr("Placeholder","The contact can not be empty.");
        }if(resPhone.length<5){
            resPhone="";
        }if(resContactEmail==false){
            obj.modal.find("#contac-email-address").val("").addClass("errorNonprofit").attr("Placeholder","Type: something@example.com.");
        }if(resBankName.length<1){
            resBankName="";
        }if(resRouterSwitch.length<1){
            resRouterSwitch="";
        }if(resBankNumberAccount.length<1){
            resBankNumberAccount="resBankNumberAccount";
        }if(resPaypalEmail==false){
            resPaypalEmail="";
        }if(resPreferredMethod==false||preferredMethod==0){
            preferredMethod="";
            
        }
        response={
            name:resOrgName,
            mail:resMail,
            ein:ein,
            contact:resContact,
            phone:resPhone,
            contactEmail:contactEmail,
            bankName:resBankName,
            routerSwitch:resRouterSwitch,
            bankNumberAccount:resBankNumberAccount,
            paypalEmail:paypalEmail,
            preferredMethod:preferredMethod,
            tags:tags
        };
        
        return response;
      //  var nlogo=obj.modal.find("#nonprofit-logo");
      //  var resnlogo=system.security.isemail(nlogo);
    }
}
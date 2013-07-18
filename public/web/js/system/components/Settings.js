/**
 * Pseudoclass to manage the Profile page
 **/
function Settings(opts){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;

    /**
     * Configure and init
     **/
    obj.init=function(){

        obj.changePwr();

    };
//cambiar password
    obj.changePwr=function(){ 
        var key=system.getURLParameter('key');
        var email=system.getURLParameter('email');
        var changePwr=$(".page-content");
        var resLastPwr=null;
        var divChange=$(".faces");
        divChange.bonflip();
        changePwr.find("input").focus(function(){
            $("#repeat-new-pwr, #new-pwr, #last-pwr").css("border-color","#D9D9D9");   
            $("#repeat-error, #new-error, #last-error").css("visibility","hidden");
            $("#answer-change").css("visibility","visible");
        });
        
        $("#change").click(function(){
            var newPwr=changePwr.find("#new-pwr").val();
            var repeatNewPwr=changePwr.find( "#repeat-new-pwr").val();
            if(key=="null"){
                var lastPwr=changePwr.find("#last-pwr").val();
                resLastPwr=system.security.ispassword(lastPwr);
                var lnError=$("#last-error");
                var inputError=$("#last-pwr");
            }else{
                resLastPwr=true;
                var lastPwr=key;
                var lnError=$("#new-error");
                var inputError=$("#new-pwr");
            }
            if(resLastPwr){
                if(system.security.ispassword(newPwr)){                    
                    if(newPwr===repeatNewPwr){
                        opts.passwordCallback(lastPwr,newPwr,email,function(response){
                            if(response.updated==="1"){
                                changePwr.find("input").val("");
                                $(".page-content .faces").bonflip("flip");
                            }else if(response.updated==="2"){
                                inputError.css("border-color","#c62e2e");
                                lnError.html("Same Password").css({visibility:"visible","padding-top":"9px"});
                            }else{
                                $("#last-pwr").css("border-color","#c62e2e");
                                $("#last-error").html("Invalid Password").css({visibility:"visible","padding-top":"9px"});
                            }
                        });
                    }else{
                    $("#repeat-new-pwr, #new-pwr").css("border-color","#cf0f0f");
                    $("#new-error").html("Password confirmation does not match.").css({visibility:"visible","padding-top":"10px"});
                    }
                }else{
                    $("#new-pwr").css("border-color","#c62e2e");
                    $("#new-error").html("Password must be between 6 - 30 characters.<br/> Please write letters, numbers and only these special characters: @#$%_-").css("visibility","visible");
                    $("#last-pwr").css("border-color","#D9D9D9");
                    $("#last-error").css("visibility","hidden");
                }
            }else{
                $("#last-pwr").css("border-color","#c62e2e");
                $("#last-error").html("Password must be between 6 and 18 characters.<br/> Please write letters, numbers and only these special characters: @#$%_-").css("visibility","visible");
            }
        });
        $("#succes").click(function(){
            window.location.replace(system.rel("views")+"home/");
        });
    };
}
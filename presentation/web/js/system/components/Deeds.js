/**
 * Pseudoclass to manage the Good Deeds' Modal
 **/
function Deeds(){
    "use strict";
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.folio=false;    //Si está dentro de un folio, este valor es diferente de 
                        //false y se envía con el deed para almacenarlo en el folio
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the Script
     **/
    obj.init=function(dialog,folio){
        obj.dialog=dialog;
        if(folio){obj.folio=folio;}
        obj.modal=$(".ui-dialog").find("#good-deed-generator");
        //Check if the Good Deeds modal was loaded
        if(!obj.modal.exist()){
            system.debug("deeds.php was not included");
        }else{
            obj.ajax=new AjaxDeeds();
            obj.events();
        }
        
        //Carga las sugerencias si existen
        var suggestions=$("#deedSuggestions").find(".suggestion");
        suggestions.suggestions({
            markReadFunction:system.ajaxSocial.suggestionMarkRead,
            dialog:dialog
        });
    };
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(){
        var triggerVideo=obj.modal.find(".btn-video");
        var tags=obj.modal.find(".tag");
        var text=obj.modal.find("#whyMatters");
        var send=obj.modal.find(".btn-good-deeds");
        var deedMedia=obj.modal.find("#deedMedia");
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
        //Send Button events
        send.click(function(e){
            e.preventDefault();
            var activeTags=obj.modal.find(".active");
            //Verify the input values
            if(activeTags.length>0){
                var string=system.security.secureString(text.val());
                if($.trim(string).length>0){
                    var media=false;
                    var type=false;
                    if(deedMedia.find("#croppedImage").exist()){
                        media=deedMedia.find("#croppedImage").attr("name");
                        type="image";
                    }
                    obj.uploadDeed(activeTags,string,media,type);
                }else{
                    system.message("Why this matter to you?","",false);
                }
            }else{
                system.message("Select at least one Tag","",false);
            }
        });
        //Video en construcción
        triggerVideo.click(function(e){
            e.preventDefault();
            system.message("Sorry, not available in Beta version!","",false);
        });
    };
    //Send the good deed to the server
    obj.uploadDeed=function(tagsElements,string,media,type){
        var tags=new Array();
        tagsElements.each(function(){
            tags.push(parseInt($(this).attr("id").replace("tag","")));
        });
        obj.ajax.saveDeed(tags,string,media,type,obj.folio,function(data){
            if(data.created=="true"){
                system.dialogClose(obj.dialog);
                system.forceDaemons();
            }
        });
    };
}
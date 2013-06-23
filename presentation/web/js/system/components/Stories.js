/**
 * Pseudoclass to manage the searches in the system
 **/
function Stories(){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init 
     **/
    obj.init=function(optsUser){
        //Options default variables
        var def = {
            folio:false,
            callback:false
        };
        obj.opts=$.extend(def,optsUser);
        obj.send=obj.opts.send;
        obj.textarea=obj.opts.textarea;
        obj.folio=obj.opts.folio;
        obj.callback=obj.opts.callback;
        obj.send.click(function(e){
            e.preventDefault();
            var text=$.trim(obj.textarea.val());
            var url=false;
            if(text!==""){
                url=system.getUrl(text);
                obj.showModal(text,url);
                
            }
        });
    };
    /**
     * Abre el modal de stories
     * */
    obj.showModal=function(text,url){
        var complete=false;
        var sended=false;
        //Crea el modal de stories
        var html=$("#modals").find("#modalStories").html();
        var dialog=system.dialog({
            html:html,
            height:"auto",
            width:500
        });
        var textElem=dialog.find("#text");
        var cancel=dialog.find("#cancelButton");
        var ok=dialog.find("#okButton");
        
        //Agrega el contenido del modal
        textElem.text(text);
        //Muestra los datos de la página
        if(url){
            dialog.find("#story").linkparser({
                fetchScript:system.rel("ajax")+"social/jxLinkparser.php",
                urlDefault:url,
                withInput: false
            },function(){
                obj.textEvents(dialog);
                complete=true;
            });
        }else{
            complete=true;
            $('<div id="imageOutsideLinkparser" class="lp_response_images"><a class="btn-photo-story" href="">photo</a></p></div><input type="text" id="titleOutsideLinkparser" class="lp_response_title" placeholder="Title">').insertBefore(dialog.find("#text"));
            obj.textEvents(dialog);
        }
        //Eventos del modal
        cancel.click(function(e){
            e.preventDefault();
            system.dialogClose(dialog);
        });
        ok.click(function(e){
            e.preventDefault();
            $('<img class="loading" src="'+system.rel("img")+'loading.gif">').insertBefore(dialog.find("#title"));
            if(complete&&!sended){
                sended=true;
                var title=dialog.find(".lp_response_title");
                var description=dialog.find(".lp_response_description");            
                //Captura los datos del modal
                var img=dialog.find(".lp_response_images").find("img").filter(function(index){
                    return $(this).css("display")==="inline";
                });
                //Verifica si se abrió un campo de texto para título o descripción
                var stringTitle="";
                if(title.find("input").exist()){
                    stringTitle=title.find("input").val();
                }else{
                    stringTitle=title.text();
                }
                var stringDescription="";
                if(description.find("textarea").exist()){
                    stringDescription=description.find("textarea").val();
                }else{
                    stringDescription=description.text();
                }
                var story={
                    title:stringTitle,
                    url:dialog.find(".lp_url").text(),
                    description:stringDescription,
                    image:img.attr("src"),
                    content:dialog.find("#text").text(),
                    folio:obj.folio     //Se agrega el folio si se crera desde un folio
                }
                system.ajaxSocial.saveStory(story,function(data){
                    if(data!=false){
                        system.dialogClose(dialog);
                        obj.textarea.val("");
                        if(obj.callback){
                            obj.callback();
                        }
                    }
                });
            }
        });
    }
    //Define los eventos de los datos después de cargados, el título, la descripción y la imagen
    obj.textEvents=function(dialog){
        var title=dialog.find(".lp_response_title");
        var description=dialog.find(".lp_response_description");
        var image=dialog.find(".lp_response_images");
        //Si se da click en el título o la descripción se vuelven editables
        title.click(function(e){
            if(!$(this).find("input").exist()){
                e.preventDefault();
                var text=$(this).text();
                $(this).empty().append('<input type="text" value="'+text+'"/>');
            }
        });
        description.click(function(e){
            if(!$(this).find("textarea").exist()){
                e.preventDefault();
                var text=$(this).text();
                $(this).empty().append('<textarea>'+text+'</textarea>');
            }
        });
        image.click(function(e){
            image.empty();
        });
        //Inicia el cargador de imágenes de stories
        obj.upStories=new Uploader();
        obj.upStories.init({
            trigger:image,
            frame:image,
            outputImageH:215,
            outputImageW:285
        });
    }
}
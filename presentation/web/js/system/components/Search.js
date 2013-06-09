/**
 * Pseudoclass to manage the searches in the system
 **/
function Search(){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.minLength=3;
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init 
     **/
    obj.init=function(optsUser){
        obj.input=optsUser.input;
        obj.button=optsUser.button;
        obj.content=optsUser.content;
        obj.list=optsUser.content.find("#list");
        obj.listFriends=optsUser.content.find("#list").find("#list-friends");
        obj.listTags=optsUser.content.find("#list").find("#list-tags");
        obj.listNonprofits=optsUser.content.find("#list").find("#list-nonprofits");
        obj.invitation=optsUser.content.find("#invite");
        obj.type=optsUser.type;
        //Crea el objeto invite, para invitar amigos
        if(obj.type==="all"){
            obj.invite=new Invite();
            obj.invite.init();
        }
        
        //Inicia los eventos
        obj.events();
    };
    
    obj.events=function(){
        //Eventos de la búsqueda
        obj.input.keyup(function(e){
            var keyword=system.security.secureString($.trim($(this).val()));
            if(
                keyword.length>2&&(
                    (e.keyCode>=48&&e.keyCode<=57)||
                    (e.keyCode>=96&&e.keyCode<=105)||
                    (e.keyCode>=65&&e.keyCode<=90)||
                    e.keyCode===8||
                    e.keyCode===46
                )
               ){
                if(obj.type==="friends"){
                    obj.friendsSearch(keyword);
                }else if(obj.type==="tags"){
                    obj.keywords=keyword;
                    obj.tagsSearch(keyword);
                }else if(obj.type==="all"){
                    obj.keywords=keyword;
                    obj.search(keyword);
                }
            }else{
                obj.listFriends.empty();
                obj.listTags.empty();
                obj.listNonprofits.empty();
                obj.content.hide();
            }
        }).blur(function(){
//            $(this).val('');
        }).click(function(){
            if(obj.type==="tags"){
                obj.tagsSearch(obj.input.val());
            }else{
//                obj.friendsSearch(obj.input.val());
            }
            obj.content.show();
        });
        //Botón de búsqueda
        obj.button.click(function(){
            var keyword=system.security.secureString($.trim(obj.input.val()));
            if(keyword.length>0){
                if(obj.type==="friends"){
                    obj.friendsSearch(keyword);
                }else if(obj.type==="tags"){
                    obj.keywords=keyword;
                    obj.tagsSearch(keyword);
                }else if(obj.type==="all"){
                    obj.keywords=keyword;
                    obj.search(keyword);
                }
            }
        });
        
        $('html').click(function(){
            obj.content.hide();
        });
        //Evento para invitar amigos por correo electrónico
        obj.invitation.click(function(){
            if(obj.type==="all"){
                obj.invite.show(obj.input.val());
            }
        });
    }
    
    /********************************** ALL ***********************************/
    /**
     * Retorna una lista de resultados a partir de los parámetros de la búsqueda
     * definidos en el servidor y de los keyword pasados.
     * */
    obj.search=function(keyword){
        system.ajax.search(keyword,function(data){
            obj.listFriends.empty();
            obj.listTags.empty();
            obj.listNonprofits.empty();
            if(data.users.length+data.tags.length+data.nonprofits.length>0){
                obj.content.show();
                for(var i in data.users){
                    var user=data.users[i];
                    if(i==0){obj.listFriends.append('<div class="titleSection" id="titlePeople"></div>');}
                    obj.listFriends.append(obj.htmlUser(user));
                }
                for(var j in data.tags){
                    var tag=data.tags[j];
                    if(j==0){obj.listTags.append('<div class="titleSection" id="titleTags"></div>');}
                    obj.listTags.append(obj.htmlTag(tag));
                }
                for(var k in data.nonprofits){
                    var nonprofit=data.nonprofits[k];
                    if(k==0){obj.listNonprofits.append('<div class="titleSection" id="titleNonprofits"></div>');}                    
                    obj.listNonprofits.append(obj.htmlNonprofit(nonprofit));
                }
                obj.eventsUsers();
                obj.eventsTags();
            }
            //Pone imágenes default a las imágenes que no pudieron ser cargadas
            obj.content.find('img').on('error',system.defaultImage);
        });
    };
    /**
     * Aplica los eventos de los usuarios de la lista luego de una búsqueda
     * */
    obj.eventsUsers=function(){
        obj.list.find(".friend").click(function(e){
            var name=$(this).siblings(".name").text();
            if($(this).attr("state")==="Friends"){
                system.message(name+" is already your friend","",false);
            }else{
                var idFriend=$(this).parent().attr("id").replace("user","");
                system.friendRequest(idFriend,name);
            }
            obj.content.hide();
        });
    };
    /**
     * Aplica los eventos de los tags (de giving y nonprofits)
     * */
    obj.eventsTags=function(){
        obj.list.find(".tag").click(function(){
            var id=$(this).attr("id").replace("tag","");
            system.gotoGivingTag(id);
        });
    };
    
    /******************************** FRIENDS *********************************/
    /**
     * Busca en los usuarios y retorna los amigos y no amigos en formato HTML
     * @param keyword términos a buscar
     **/
    obj.friendsSearch=function(keyword){
        system.ajaxSocial.friendsSearch(keyword,function(htmlUsers){
            obj.content.empty();
            if(htmlUsers.length>0){
                obj.content.show();
                obj.content.append(htmlUsers);
                obj.content.find(".friend").click(function(e){
                    e.stoppragation;
                    var name=$(this).siblings(".name").text();
                    if($(this).attr("state")==="Friends"){
                        system.message(name+" is already your friend","",false);
                    }else{
                        var idFriend=$(this).parent().attr("id").replace("user","");
                        system.friendRequest(idFriend,name);
                    }
                    obj.content.hide();
                });
            }
        });
    };
    
    
    /********************************** TAGS **********************************/
    /**
     * Busca una lista de tags con un contenido similar al keyword
     * @param keyword términos a buscar
     * */
    obj.tagsSearch=function(keyword){
        if(typeof keyword!=="undefined"&&keyword.length>=obj.minLength){
            system.ajaxDonation.searchTags(keyword,function(tags){
                obj.content.empty();
                if(tags.length>0){
                    obj.content.show();
                    for(var i in tags){
                        obj.content.append(obj.tagHtml(tags[i]));
                    }
                    obj.searchTagsEvents();
                }else{
                    obj.content.empty();
                }
            });
        }
    };
    obj.tagHtml=function(tag){
        //Verify if the tag exist in the circle, if is, does not show it
        if(!system.combination.circle.existTag(tag.id)){
            var html='<div id="tag'+tag.id+'" class="tag results area'+tag.area.id+'" area="'+tag.area.id+'" color="'+tag.area.color+'" type="'+tag.type+'">'+
                    '<div class="name">'+tag.name+'</div>'+
                    '<span class="btn-drag"></span>'+
                '</div>';
        }
        return html;
    };
    obj.searchTagsEvents=function(){
        obj.content.find(".tag").draggable({
            appendTo: 'body',
            scroll: false,
            helper: 'clone',
            zIndex:50,
            start:function(){
                obj.keywords=obj.input.val();
                obj.input.val("");
                obj.content.hide();
            }
        });
    };
    //Restore the search terms
    obj.restoreKeywords=function(){
        obj.input.val(obj.keywords);
    };
    
    /*
     * Retorna un usuario de la búsqueda en html
     * @param [user] Objeto de tipo User en json
     * @return string El html del usuario
     **/
    obj.htmlUser=function(user){
        var friend='Invite';
        var showName=user.name+" "+user.lastname;
        //Set the name or email
        if(showName===""){
            showName=user.email;
        }
        if(user.isFriend==="1"){
            friend='Friends';
        }else if(user.isFriend==="sent"){
            friend='Accept';
        }else if(user.isFriend==="received"){
            friend='Resend';
        }
        var html='<div id="user'+user.id+'" class="user">'+
                '<img src="'+user.image+'">'+
                '<div class="name">'+showName+'</div>'+
                '<div class="friend '+friend+'" state="'+friend+'">'+friend+'</div>'+
            '</div>';
        return html;
    };
    /*
     * Retorna un tag de la búsqueda en html
     * @param [tag] Objeto de tipo Tag en json
     * @return string El html del tag
     **/
    obj.htmlTag=function(tag){
        var html='<div id="tag'+tag.id+'" class="tag" type="'+tag.type+'" color="'+tag.area.color+'" area="'+tag.area.id+'">'+
                '<img src="'+system.rel('img')+'areas/area_'+tag.area.id+'.png" width="25">'+
                '<div class="name">'+tag.name+'</div>'+
                '<div class="give">Give</div>'+
            '</div>';
        return html;
    };
    /*
     * Retorna un tag de nonprofit de la búsqueda en html
     * @param [nonprofit] Objeto de tipo Nonprofit en json
     * @return string El html de la nonprofit
     **/
    obj.htmlNonprofit=function(nonprofit){
        system.debug(nonprofit);
        var html='<div id="tag'+nonprofit.id+'" class="tag" type="'+nonprofit.type+'" color="'+nonprofit.area.color+'" area="'+nonprofit.area.id+'">'+
                '<img id="imgnon" src="'+nonprofit.organization.logo+'">'+
                '<div class="name">'+nonprofit.name+'</div>'+
                '<div class="give">Give</div>'+
            '</div>';
        return html;
    };
}
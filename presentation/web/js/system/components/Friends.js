/**
 * Pseudoclass to manage the Profile page
 **/
function Friends(){
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
        obj.frienship=optsUser.frienship;
        //Se define la visualización si es amigo o no
        if(obj.frienship!==true&&obj.id!==system.config.user){
            
        }
        //Crea una instancia de la búsqueda de amigos
        obj.search=new Search();
        obj.search.init({
            input:$(".content-top").find("#inputSearch"),
            button:$(".content-top").find("#buttonSearch"),
            content:$(".content-top").find("#content"),
            type:"all"
        });
        //Crea el objeto invite, para invitar amigos
        obj.invite=new Invite();
        obj.invite.init();
        
        //Genera los eventos para los objetos del home
        obj.events();
        //incia el modal de invitar amigos facebook
        obj.InviteFriendsSocial();
        
        
    };
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(){
        var divFriends=$(".friendDiv");
        var friendship=divFriends.find(".btn-friendship");
        var invite=$(".invite-buttons").find("#invite-email, #text");
        //Agregar o eliminar amigos
        friendship.click(function(e){
            e.preventDefault();
            var status=$(this).attr("status");
            var id=$(this).attr("id");
            var name=$(this).attr("name");
            if(status==="add"||status==="resend"||status==="accept"){
                system.friendRequest(id,name,function(response){
                    if(response==="request"){
                        //Cambiar botón a resend
                        divFriends.find("#"+id).attr("status","resend").text("Resend");
                    }else if(response==="friend"){
                        //Cambiar botón a friend
                        divFriends.find("#"+id).attr("status","friends").text("Remove friend");
                    }
                });
            }else if(status==="friends"){
                system.friendDelete(id,name,function(){
                    divFriends.find("#"+id).attr("status","add").text("Add Friend");
                });
            }
        });
        //Invitar amigos por correo
        invite.click(function(e){
            e.preventDefault();
            obj.invite.show();
        });
    };
 //invitacion
    obj.InviteFriendsSocial=function(){
        $("#invite-facebook").click(function(e){
            e.preventDefault();
            FB.ui({method: 'apprequests',
            message: 'My Great Request',
            redirect_uri:"http://localhost/views/landing/index.php?id=96"},
            function(response) {
                if (response) {
                    system.debug(response);
                } else {
                    system.debug(response);
                }
            }
            );
         });
    };

}    

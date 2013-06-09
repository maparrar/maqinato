/**
 * Pseudoclass to manage the notification system
 **/
function Notifications(){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    //Indica si se está mostrando la lista de notificaciones
    obj.listVisible=false;
    //Indica la cantidad máxima de notificaciones antes de aplicar el scroll
    obj.maxVisibles=5;
    //Altura de la mínima notificación
    obj.notificationHeight=36;
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init 
     **/
    obj.init=function(optsUser){
        obj.indicator=optsUser.indicator;
        obj.content=optsUser.content;
        obj.activityViewer=optsUser.activityViewer;
        
        //Load the list of notifications
        obj.indicator.click(function(e){
            e.preventDefault();
            e.stopPropagation();
            if(!obj.listVisible){
                obj.showNotifications();
            }else{
                obj.hideNotifications();
            }
        });
        //Create the daemon to load the notifications
        system.daemons.add(new Daemon("notifications",obj.responseNotifications,1));
        //Scroller para las notificaciones
        $('.notifScroller').tinyscrollbar();
        //Evento para cerrar la lista de notificaciones cuando se da click en otro lado
        $('html').click(function(){
            obj.hideNotifications();
        });
    };
    //Aplica los eventos para cada notificación/notificaciones pasada/s
    obj.eventsNotifications=function(notifications){
        notifications.each(function(){
            var notification=$(this);
            //Elimina los anteriores eventos
            notification.off();
            notification.click(function(e){
                e.preventDefault();
                e.stopPropagation();
                var element=$(e.target);
                if(notification.attr("id")!==undefined){
                    var notificationId=notification.attr("id").replace("notification","");
                    if(element.hasClass("user")){
                        var userId=element.attr("id").replace("user","");
                        system.gotoProfile(userId);
                    }else if(element.hasClass("notification-button")){
                        if(notification.attr("notiftype")==="friendship"){
                            var user=notification.find(".user");
                            var action=element.attr("id");
                            obj.proccessFriendship(user,action,notificationId);
                        }
                    }else if(element.hasClass("deleteNotification")){
                        obj.deleteNotification(notificationId);
                    }else if(element.hasClass("commentActivity")){
                        var activityId=element.attr("id").replace("commentActivity","");
                        system.showActivity(activityId);
                    }
                }
            });
            
        });
    };
    
    //Response from the daemon "notifications"
    obj.responseNotifications=function(data){
        if(data===0){
            obj.indicator.addClass("notifications-empty");
        }else{
            obj.indicator.removeClass("notifications-empty");
            obj.notifications=data;
            obj.indicator.text(obj.notifications);
        }
    };
    /**
     * Muestra la lista de notificaciones
     * */
    obj.showNotifications=function(){
        system.ajaxSocial.loadNotifications(function(notifications){
            obj.content.find("ul").empty();
            if($.trim(notifications)!==""){
                obj.content.find("ul").prepend(notifications);
                obj.eventsNotifications(obj.content.find(".notification"));
                obj.content.show();
                obj.listVisible=true;
                obj.recalculateHeight();
                obj.readAllNotifications(function(){
                    obj.indicator.text(0);
                    obj.indicator.addClass("notifications-empty");
                });
            }else{
                obj.content.hide();
            }
        });
    };
    /**
     * Oculta la lista de notificaciones
     * */
    obj.hideNotifications=function(){
        obj.content.hide();
        obj.listVisible=false;
    };
    /**
     * Quita una notificación de la lista
     * */
    obj.removeNotification=function(notificationId){
        obj.content.find("#notification"+notificationId).closest("li").remove();
        if(!obj.content.find(".notification").exist()){
            obj.indicator.addClass("notifications-empty");
            obj.hideNotifications();
        }
        if(obj.notifications>0){
            obj.indicator.text(obj.notifications-1);
        }
        obj.recalculateHeight();
    };
    /*********** FUNCIONES DE LOS EVENTOS DE LA NOTIFICACIÓN ******************/
    /**
     * Eviata que se vuelva a mostrar una notificación
     * */
    obj.deleteNotification=function(idNotification,callback){
        obj.removeNotification(idNotification);
        system.ajaxSocial.deleteNotification(idNotification,callback);
    };
    obj.readNotification=function(idNotification,callback){
        system.ajaxSocial.readNotification(idNotification,callback);
    };
    obj.readAllNotifications=function(callback){
        system.ajaxSocial.readAllNotifications(callback);
    };
    /**
     * Procesa si es una notificación de amistad
     * */
    obj.proccessFriendship=function(user,action,notificationId){
        var friendId=user.attr("id").replace("user","");
        var friendName=user.text();
        if(action==="accept"){
            system.friendRequest(friendId,friendName);
            obj.deleteNotification(notificationId);
        }else if(action==="ignore"){
            system.message("You have ignored the friend request from "+friendName,"",false);
            obj.deleteNotification(notificationId);
        }
    };
    /**
     * Auto height para el viewport
     * */
    obj.recalculateHeight=function(){
        var cantNotifications=obj.content.find(".notification").length;
        if(cantNotifications<=obj.maxVisibles){
            var height=cantNotifications*obj.notificationHeight;
            obj.content.find(".viewport").height(height);
        }
        $('.notifScroller').tinyscrollbar_update();
    };
}
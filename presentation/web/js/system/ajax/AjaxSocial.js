function AjaxSocial(){
    var obj=this;
    obj.path=system.rel('ajax');
    obj.root=system.rel('root');
    //Required init function
    obj.init=function(){}
    
    /**
     * Busca en la lista de usuarios las keywords
     * */
    obj.friendsSearch=function(keyword,callback){
        $.post(
            obj.path+"social/jxFriendsSearch.php",{
                keyword:keyword
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    var users=data.users;
                    var organizations=data.organizations;
                    callback(users,organizations);
                }
            }
        );
    };
    obj.requestFriend=function(friendId,callback){
        $.post(
            obj.path+"social/jxRequestFriend.php",{
                friendId:friendId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    obj.deleteFriend=function(friendId,callback){
        $.post(
            obj.path+"social/jxDeleteFriend.php",{
                friendId:friendId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    /**
     * Invita amigos por correo electrónico 
     * */
    obj.inviteByMail=function(valid,invalid,callback){
        $.post(
            obj.path+"social/jxInviteByMail.php",{
                valid:valid,
                invalid:invalid
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    
    /******************************** USUARIOS ********************************/
    /**
     * Guarda la imagen del usuario actual
     **/
    obj.saveUserImage=function(media,callback){
       $.post(
            obj.path+"social/jxUserImageSave.php",{
                media:media
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    
    
    /******************************* ACTIVIDADES ******************************/
    
    /**
     * Retorna una lista de actividades del servidor, seleccionadas por los filtros
     * pasados. La cantidad de actividades que se deben cargar está determinada
     * en el archivo de configuración del sistema.
     * @param refActivity int Id de la actividad de referencia, si se va a hacer una
     *                      carga de actividades anteriores, refActivity es el
     *                      menor id cargado hasta ahora. Si es carga de las nuevas
     *                      actividades, refActivity es el mayor id cargado 
     *                      hasta el momento.
     * @param int direction: Indica si se cargan actividades hacia adelante o 
     *                      hacia atras del límite, si es 
     *                      -1 carga hacia atras
     *                      1 hacia adelante
     * @param filter: ver el encabezado del newsfeed
     * @param tagsFilter: ver el encabezado del newsfeed
     * @param folio: Identificador de folio si se deben cargar las actividades 
     *              en un folio en particular
     * @param int profileId Identificador del perfil si se deben cargar las actividades 
     *              de un perfil en particular. Son las actividades creadas por un usuario
     *              determinado.
     * @param callback string Función a la que se retorna la lista de actividades
     *                      en formato html y el id de la última actividad.
     **/
    obj.loadActivities=function(refActivity,direction,filter,tagsFilter,folio,profileId,callback){
        $.post(
            obj.path+"social/jxLoadActivities.php",{
                refActivity:refActivity,
                direction:direction,
                filter:filter,
                tagsFilter:tagsFilter,
                folio:folio,
                profileId:profileId
            },
            function(response){
                //decoded data
                var data=false;
                try{
                    data=JSON.parse(response);
                }catch(e){
                    system.debug("JSON parse error: "+e);
                }
                if(data.type==="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    
    /****************************** COMENTARIOS *******************************/
    /**
     * Save a Comment in an Activity, return the comment index
     **/
    obj.saveComment=function(comment,activityId,toUser,callback){
       $.post(
            obj.path+"social/jxCommentSave.php",{
                comment:comment,
                activityId:activityId,
                toUser:toUser
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data.id,data.html,activityId);
                }
            }
        );
    };
    /**
     * Delete a Comment in an Activity
     **/
    obj.deleteComment=function(commentId,callback){
       $.post(
            obj.path+"social/jxCommentDelete.php",{
                commentId:commentId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    /**
     * Load all comments of an Activity
     **/
    obj.loadAllComments=function(activity,toUser,callback){
        var activityId=activity.attr("id").replace("activity","");
        $.post(
            obj.path+"social/jxCommentsAll.php",{
                activityId:activityId,
                toUser:toUser
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data.comments,activity);
                }
            }
        );
    };
    /**
     * Load the last Activities
     **/
    obj.loadLastComments=function(activityId,callback){
       $.post(
            obj.path+"social/jxCommentsLasts.php",{
                activityId:activityId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data,data.lastActivity);
                }
            }
        );
    };
    /********************************* LIKES **********************************/
    /**
     * Hace el intercambio entre like/unlike de una actividad para el usuario registrado
     **/
    obj.toggleLikeActivity=function(activityId,callback){
       $.post(
            obj.path+"social/jxToggleLikeActivity.php",{
                activityId:activityId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data,activityId);
                }
            }
        );
    };
    /******************************** SHARES **********************************/
    /**
     * Asigna los puntos al usuario por compartir en una red social
     **/
    obj.shareActivity=function(activityId,callback){
       $.post(
            obj.path+"social/jxShareActivity.php",{
                activityId:activityId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    if(callback){
                        callback(data,activityId);
                    }
                }
            }
        );
    };
    
    /********************************* FOLIOS *********************************/
    obj.saveMatters=function(folioId,matters,callback){
        $.post(
            obj.path+"social/jxFolioSaveMatters.php",{
                folioId:folioId,
                matters:matters
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    if(callback){
                        callback(data);
                    }
                }
            }
        );
    };
    obj.updateFolio=function(folioId,callback){
        $.post(
            obj.path+"social/jxFolioUpdateData.php",{
                folioId:folioId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    if(callback){
                        callback(data);
                    }
                }
            }
        );
    };
    /**
     * Save a Comment in an Activity, return the comment index
     **/
    obj.toggleFollowFolio=function(folioId,callback){
       $.post(
            obj.path+"social/jxToggleFollowFolio.php",{
                folioId:folioId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    /**
     * Guarda la imagen de un folio
     **/
    obj.saveFolioImage=function(folioId,media,callback){
       $.post(
            obj.path+"social/jxFolioImageSave.php",{
                folioId:folioId,
                media:media
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    
    /**
     * Return an activity from the Id
     **/
    obj.loadActivity=function(activityId,callback){
       $.post(
            obj.path+"social/jxActivityGet.php",{
                activityId:activityId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data.activity);
                }
            }
        );
    }
    /***************************** NOTIFICACIONES *****************************/
    /**
     * Load the user notifications
     **/
    obj.loadNotifications=function(callback){
       $.post(
            obj.path+"social/jxNotificationsLoad.php",
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type==="Error"){
                    system.error(data);
                }else{
                    if(data.reloadNewsfeed==="true"){
//                        system.reloadNewsfeed();
                    }
                    callback(data.notifications);
                }
            }
        );
    };
    /**
     * Evita que se vuelva a cargar una notificación
     **/
    obj.deleteNotification=function(idNotification,callback){
       $.post(
            obj.path+"social/jxNotificationDelete.php",
            {idNotification:idNotification},
            function(){
                if(callback){
                    callback();
                }
            }
        );
    };
    /**
     * Mark as read one notification
     **/
    obj.readNotification=function(idNotification,callback){
       $.post(
            obj.path+"social/jxNotificationsMarkRead.php",
            {idNotification:idNotification},
            function(){
                if(callback){
                    callback();
                }
            }
        );
    };
    /**
     * Mark as read one notification
     **/
    obj.readAllNotifications=function(callback){
       $.post(
            obj.path+"social/jxNotificationsMarkAllRead.php",
            function(){
                if(callback){
                    callback();
                }
            }
        );
    };
    
    /***************                STORIES                    ****************/
    obj.saveStory=function(story,callback){
        $.post(
            obj.path+"social/jxStorySave.php",
            story,
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    var story=JSON.parse(data.story);
                    callback(story);
                }
            }
        );
    };
    
    /******************************* SUGERENCIAS ******************************/
    obj.suggestionMarkRead=function(suggestionId,callback){
        $.post(
            obj.path+"social/jxSuggestionMarkRead.php",
            {suggestionId:suggestionId},
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    if(callback){
                        callback(data);
                    }
                }
            }
        );
    };
}
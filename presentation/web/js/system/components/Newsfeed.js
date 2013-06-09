/**
 * Pseudoclass to manage the activities in the newsfeed
 * opts:{
 *      filter: Filtro usado para cargar nuevas actividades del servidor, solo un
 *              tipo de filtro puede estar activo al tiempo. Los filtros solo 
 *              retornan las actividades que le corresponden al usuario activo.
 *          - "all": (default) Carga todas las actividades
 *          - "giving": carga las actividades de giving
 *          - "deed": carga las actividades de Good Deeds
 *          - "news": carga las stories y demás noticias de maqinato
 *          
 *      tagsFilter: carga las actividades que contengan al menos uno de los 
 *                    tags de la lista de tags asociado a este filtro. p.e.: 
 *                    [1,23,43] cargará las actividades que contengan el 
 *                    tag 1, 23 o 45. Puede ser combinado con el filtro filter.
 *          - []: (default) No filtra por tags
 *          - [x,y,...]: Filtra por los tags x, y, ...
 **/
function Newsfeed(){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.opts=null;
    obj.newsfeed=null;
    //Determina si el newsfeed está en start o stop
    //  - status=true: funcionando
    //  - status=false: detenido
    obj.status=true;
    //Menor actividad mostrada en el newsfeed
    obj.minActivity=Infinity;
    //Mayor actividad mostrada en el newsfeed
    obj.maxActivity=0;
    //Filtro actual
    obj.filter="all";
    //Filtro de tags actual
    obj.tagsFilter=new Array();
    
    //Si está cargando actividades por medio del infinite scroll
    obj.infinLoadingActivities=false;
    //Tiempo que evita la repetición del scroll (milisegundos)
    obj.timeWaitingScroll=1000;
    //Si está esperando para evitar la repetición del scroll
    obj.waitingScroll=false;
    
    //Indica si isotope ya está cargado
    obj.isotopeLoaded=false;
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the newsfeed
     **/
    obj.init=function(optsUser){
        //Options default variables
        var def = {
            filter:"all",
            loadInit:10,
            loadScroll:10,
            tagsFilter:new Array(),
            folio:false,            //Si es el newsfeed de un folio
            profileId:false         //Si es el newsfeed de un perfil
        };
        obj.opts=$.extend(def,optsUser);
        obj.newsfeed=obj.opts.newsfeedElement;
        obj.filterBar=obj.opts.filterBar;
        obj.filter="all";
        obj.tagsFilter=obj.opts.tagsFilter;
        obj.folio=obj.opts.folio;
        obj.profileId=obj.opts.profileId;
        
        //Create the ISOTOPE instance
        obj.newsfeed.imagesLoaded(function(){
            obj.newsfeed.isotope({
                itemSelector : '.activity',
                masonry : {
                    columnWidth : 320,
                    isResizable: true
                }
            },function(){
                obj.isotopeLoaded=true;
            });
        });
        
        //Calcula la mayor y menor actividad al inicio del newsfeed
        obj.resetParameters();
        //Create the daemon to load the last activities
        system.daemons.add(new Daemon("activities",obj.loadedNewActivities,1));
        obj.resetParameters();

        //Inicia los eventos del newsfeed
        obj.events();
        
        //Inicializa twitter
        system.initTwitter();
    };
        
    /**
     * Ejecuta los eventos del newsfeed
     **/
    obj.events=function(){
        //Eventos para el scroll infinito
        obj.infinite();
         //Apply the events to the PHP-loaded activities
        obj.activitiesEvents(obj.newsfeed.find(".activity"));
        //Init the filter events
        obj.initFilters();
        //Cuando acaba de cargar las imáganes, recarga isotope
        $(window).load(function(){
            obj.reorder();
        });
    };
    /** Reorder the newsfeed **/
    obj.reorder=function(){
        if(obj.isotopeLoaded){
            obj.newsfeed.isotope('reLayout');
        }
    };
    /** Borra las actividades del newsfeed */
    obj.emptyNewsfeed=function(){
        obj.newsfeed.find(".activity").remove();
        obj.reorder();
    };
    /** Reanuda y muestra la ejecución del newsfeed */
    obj.start=function(){
        obj.status=true;
        obj.resetParameters();
        obj.newsfeed.show();
        obj.reorder();
    };
    /** Detiene y oculta la ejecución del newsfeed */
    obj.stop=function(){
        obj.status=false;
        obj.newsfeed.hide();
    };
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> PARAMETERS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    //>>>>>>>>>>>>>>>>>>>>>>>>>> INFINITE SCROLL <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  
    obj.infinite=function(){
        $(window).scroll(function () {
            if(obj.status){
                if(document.documentElement.clientHeight + $(document).scrollTop() >= document.body.offsetHeight ){
                    if(!obj.infinLoadingActivities&&!obj.waitingScroll){
                        obj.waitScroll();
                        obj.loadPreviousActivities();
                    }
                    $(document).scrollTop($(document).scrollTop()-1);
                }
            }
        });
    };
    obj.waitScroll=function(){
        obj.waitingScroll=true;
        setTimeout(function(){
            obj.waitingScroll=false;
        },obj.timeWaitingScroll);
    };
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ACTIVITIES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /* 
     * Retorna la id de un elemento de clase activity
     **/
    obj.getActivityId=function(element){
        return parseInt(element.attr("id").replace("activity",""));
    };
    /**
     * Reconfigura los parámetros del newsfeed, incluídos los de carga de actividades
     * del demonio.
     **/
    obj.resetParameters=function(){
        var activities=new Array();
        obj.minActivity=Infinity;
        obj.maxActivity=0;
        obj.newsfeed.find(".activity").each(function(){
            activities.push(obj.getActivityParameters($(this)));
        });
        //Si hay una actividad en el modal de actividades, se incluye dentro de los que se deben cargar
        if(system.activityDialog){
            var modalActivity=system.activityDialog.find(".activity");
            activities.push(obj.getActivityParameters(modalActivity));
        }
        var params={
            "refActivity":obj.maxActivity,
            "filter":obj.filter,
            "folio":obj.folio,
            "profileId":obj.profileId,
            "tagsFilter":obj.tagsFilter,
            "list":activities
        }
        //Envía los nuevos parámetros al demonio para cuando vueva a cargar actividades
        system.daemons.parameterizeDaemon("activities",params);
    };
    //Retorna los parametros de las actividades para recalcular los parámetros generales del newsfeed
    obj.getActivityParameters=function(activity){
        //Recalcula el máximo y el mínimo en los que no están ocultos
        if(!activity.hasClass("isotope-hidden")){
            var id=obj.getActivityId(activity);
            if(id<obj.minActivity){obj.minActivity=id;}
            if(id>obj.maxActivity){obj.maxActivity=id;}
        }
        //Cuenta el número de comentarios de la actividad
        return {activity:obj.getActivityId(activity),comments:parseInt(activity.find(".comments").attr("total"))};
    };
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> FILTERS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< 
    /* 
     * Retorna la id de un elemento de clase tag
     **/
    obj.getTagId=function(element){
        return parseInt(element.attr("id").replace("tag",""));
    };
    //Filters and sort Activities
    /**
     * Init the filters in the newsfeed
     **/
    obj.initFilters=function(){
        var impactButton=obj.filterBar.find("#impactButton");
        var impactMenu=obj.filterBar.find("#impactMenu");
        var impactSelector=obj.filterBar.find("#impactSelector");
        var impacts=impactMenu.find(".tags_area");
        var sortButton=obj.filterBar.find("#sortButton");
        var sortMenu=obj.filterBar.find("#sortMenu");
        var sortImpact=sortMenu.find("#impact");
        var sortPopular=sortMenu.find("#popular");
        var tags=impactMenu.find(".tag");
        var apply=impactMenu.find("#impactApply");
        var clear=impactMenu.find("#impactClear");
        
        //Basic Filters
        obj.filterBar.find(".btn-fltr").click(function(e){
            e.preventDefault();
            tags.removeClass("active");
            obj.tagsFilter=[];
            //Elimina las clases de filtros de tags anteriores
            obj.newsfeed.find('.activity').removeClass("tagFiltered");
            obj.filterBar.find(".btn-fltr").removeClass("active");
            $(this).addClass("active");
            obj.filterNewsfeed($(this).attr("data-filter"));
        });
        //Impact filter
        impactButton.click(function(e){
            e.preventDefault();
            e.stopPropagation();
            impactMenu.toggle();
        });
        //Eventos del menu
        $('html').click(function() {
            impactMenu.hide();
            sortMenu.hide();
        });
        impactMenu.click(function(e){e.stopPropagation();});
        
        //Filter Select
        impacts.hide();
        impacts.parent().find("#tags_area1").show();
        impactSelector.change(function(e){
            var areaId=e.target.value;
            impacts.hide();
            impacts.parent().find("#tags_area"+areaId).show();
        });
        tags.click(function(e){
            e.preventDefault();
            if($(this).hasClass("active")){
                $(this).removeClass("active");
            }else{
                $(this).addClass("active");
            }
        });
        apply.click(function(e){
            e.preventDefault();
            var tagsList=new Array();
            obj.tagsFilter=[];
            tags.each(function(){
                if($(this).hasClass("active")){
                    tagsList.push($(this));
                    obj.tagsFilter.push(obj.getTagId($(this)));
                }
            });
            var tagsString="#tag"+obj.tagsFilter.join(",#tag");
            //Elimina las clases de filtros de tags anteriores
            obj.newsfeed.find('.activity').removeClass("tagFiltered");
            //Agrega una clase para filtrar con isotope
            obj.newsfeed.find('.activity.combination:has('+tagsString+'),.activity.deed:has('+tagsString+')').addClass("tagFiltered");
            obj.filterNewsfeed(".tagFiltered");
            impactMenu.hide();
        });
        clear.click(function(e){
            e.preventDefault();
            tags.removeClass("active");
            obj.tagsFilter=[];
            //Elimina las clases de filtros de tags anteriores
            obj.newsfeed.find('.activity').removeClass("tagFiltered");
            obj.filterNewsfeed("*");
            impactMenu.hide();
        });
        
        
        // ************** Ordenar el newsfeed **************************
        //Impact filter
        sortButton.click(function(e){
            e.preventDefault();
            e.stopPropagation();
            sortMenu.toggle();
        });
        //Eventos del menu
        sortMenu.click(function(e){e.stopPropagation();});
        sortImpact.click(function(e){
            e.preventDefault();
            e.stopPropagation();
            sortImpact.addClass("active");
            sortPopular.removeClass("active");
            sortMenu.hide();
        });
        sortPopular.click(function(e){
            e.preventDefault();
            e.stopPropagation();
            sortPopular.addClass("active");
            sortImpact.removeClass("active");
            sortMenu.hide();
        });
    };
    //Filter the newsfeed by the selector passed
    obj.filterNewsfeed=function(filterName){
        var isoFilter=filterName;
        if(filterName!=".tagFiltered"){
            obj.filter=isoFilter.replace(".","");
            if(obj.filter=="*"){obj.filter="all";}
        }
        obj.newsfeed.isotope({filter:isoFilter},function(){
            obj.resetParameters();
            obj.reorder();
        });
    };
    //>>>>>>>>>>>>>>>>>>>>>>>> ACTIVITIES EVENTS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Asigna los eventos a un selector o conjunto de selectores de actividades
     * pasados como parámetro
     **/
    obj.activitiesEvents=function(activities){
        activities.each(function(){
            var activity=$(this);
            //Pone imágenes default a las imágenes del newsfeed que no pudieron ser cargadas
            activity.find('img').on('error',system.defaultImage);
            obj.activityFlipAndSlidersEvents(activity);
            obj.activityCommentEvents(activity);
            obj.activitySocialEvents(activity);
            
            //Ir a la combinación
            activity.find(".give").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                system.gotoCombination(activity.attr("combination"));
            });
            //Ir al folio
            activity.find(".gotoFolio").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                system.gotoFolio(parseInt($(this).attr("data-folio")));
            });
        });
    };
    
    //Eventos de los flips y sliders
    obj.activityFlipAndSlidersEvents=function(activity){
        var cantAreas=activity.find(".slides").children("li").length;
        //Crea el slider de tags (antes del flip para no ocultar los tamaños)
        var tagsAreas=activity.find(".tagsArea");
        var usersImages=activity.find(".usersImages");
        var organizationImages=activity.find(".organizationImages");
        //Bonslider en tags y usuarios
        tagsAreas.bonslider();
        usersImages.bonslider();
        organizationImages.bonslider();
        //Flip Activity
        activity.find(".faces").bonflip();
        //Rota si se hace click en la cara frontal
        activity.find(".front, .btn-flip").click(function(){
            setTimeout(function(){
                //Implementa el slider de las caras de áreas
                activity.find(".sliderAreas").flexslider({
                    animation: "slide",
                    animationLoop: false,
                    slideshow: false,
                    directionNav: false,
                    itemMargin: 5
                });
                activity.find(".flex-control-nav").children("li").click(function(e){
                    e.stopPropagation();
                    var index=parseInt($(this).index());
                    if(index===0){
                        if(cantAreas===2){
                            activity.find(".sliderAreas").flexslider('previous');
                        }else{
                            activity.find(".sliderAreas").flexslider(1);
                            activity.find(".sliderAreas").flexslider(0);
                        }
                    }else{
                        if(cantAreas===2){
                            activity.find(".sliderAreas").flexslider('next');
                        }else{
                            activity.find(".sliderAreas").flexslider(1);
                            activity.find(".sliderAreas").flexslider(2);
                            activity.find(".sliderAreas").flexslider(index);
                        }
                    }
                });
                setTimeout(obj.reorder,300);
            },420);
            activity.find(".faces").bonflip("flip");
        });
        //Determina a dónde va si se hace click en alguna parte de las caras
        activity.find(".sliderAreas").click(function(e){
            var current=$(this).find(".slides").children("li").index($(this).find(".flex-active-slide"))+1;
            //Va al siguiente slide cuando se hace click
            if(current===0){
                activity.find(".faces").bonflip("flip",function(){
                    obj.reorder();
                });
            }else{
                if(cantAreas===current){
                    activity.find(".faces").bonflip("flip",function(){
                        obj.reorder();
                    });
                    if(cantAreas===2){
                        activity.find(".sliderAreas").flexslider('previous');
                    }else{
                        activity.find(".sliderAreas").flexslider(2);
                        activity.find(".sliderAreas").flexslider(1);
                        activity.find(".sliderAreas").flexslider(0);
                    }
                }else{
                    activity.find(".sliderAreas").flexslider('next');
                }
            }
        });
        //Ordena las áreas en la cara principal de la actividad de giving
        if(activity.hasClass("combination")){
            var areas=activity.find(".area");
            switch(areas.length){
                case 1:areas.first().css("margin-top",85);break;
                case 2:areas.first().css("margin-top",70);break;
                case 3:areas.first().css("margin-top",53);break;
                case 4:areas.first().css("margin-top",35);break;
                case 5:areas.first().css("margin-top",10);break;
                case 6:areas.each(function(){$(this).css("margin-top",5);});break;
            }
        }
    };
        
    //Eventos de los comentarios en una actividad
    obj.activityCommentEvents=function(activity){
        //Apply the event to the comment box
        activity.find(".btn-publish").click(function(e){
            e.preventDefault();
            var newCommentDom=$(this).parent().find(".newComment");
            var comment=$.trim(system.security.secureString(newCommentDom.val()));
            if ($.trim(comment)!==""){
                var activity=newCommentDom.closest(".activity").attr("id").replace("activity","");
                newCommentDom.val("").height(21);
                system.ajaxSocial.saveComment(comment,activity,0,obj.responseNewComment);
            }
        });
        //Apply the events for each comment
        var comments=activity.find(".comment");
        comments.each(function(){
            obj.commentEvents($(this));
        });
        //View more comments
        activity.find(".totalComments").click(function(){
            if($(this).hasClass("view")){
                system.ajaxSocial.loadAllComments(activity,0,obj.responseAllComments);
                $(this).removeClass("view");
                $(this).text("Hide comments");
            }else{
                var comments=$(this).parent();
                var total=comments.attr("total");
                //Delete all minus 2
                comments.find(".comment:lt("+(total-2)+")").remove();
                $(this).text("View "+total+" comments");
                $(this).addClass("view");
                obj.reorder();
            }
        });
        //Resize to comments events
        activity.find(".newComment").focusin(function(){
            setTimeout(function(){
                obj.reorder();
            },100);
        }).focusout(function(){
            setTimeout(function(){
                obj.reorder();
            },100);
        });
        //Aumenta el tamaño del textarea cuando se agrega una línea de comentario
        activity.find(".newComment").autosize({
            callback:function(){
                obj.reorder();
            }
        });
    };
    
    //Eventos de los social en una actividad
    obj.activitySocialEvents=function(activity){
        var basicPoints=parseInt(activity.find(".basic_points span").text());
        var extraPoints=parseInt(activity.find(".extra_points span").text());
        var points=basicPoints+extraPoints;
        var type=activity.attr("activityType");
        var badge=parseInt(activity.attr("badge"));
        var badgename="";
        if(type==="badge"){
            badge=parseInt(activity.attr("badge"));
            switch(badge){
            case 1:
                badgename="Planet Earth";
                break;
            case 2:
                badgename="Humanity";
                break;
            case 3:
                badgename="Lifeforce";
                break;
            case 4:
                badgename="Development";
                break;
            case 5:
                badgename="Speak Out";
                break;
            case 6:
                badgename="Savvy";
                break;
            case 7:
                badgename="Impact";
                break;
            case 8:
                badgename="Groundswell";
                break;
            case 9:
                badgename="Pioneer";
                break;
            }
        }
        var name=activity.find(".head").find(".user").text().replace('\'s',"");
        
        //Likes
        activity.find(".likeButton").click(function(e){
            e.preventDefault();
            system.ajaxSocial.toggleLikeActivity(obj.getActivityId(activity),function(data){
                if(data.likeToggled==="like"){
                    activity.find(".likeButton").text("Unlike");
                }else{
                    activity.find(".likeButton").text("Like");
                }
            });
        });
        activity.find(".btn-sh-facebook").click(function(e){
            e.preventDefault();
            var imageName=false;
            var application="";
            if(system.config.application!=""){
                application=system.config.application+'/';
            }
            var activityId=obj.getActivityId(activity);
            var image=activity.find(".faces").find(".front").find("img").attr("src");
            if(image){
                var imageName=image.substring(image.indexOf("data/")+4);
                imageName=imageName.substring(1,imageName.indexOf(".")+4);
            }
            //Crea el caption para mostrar en facebook
            var caption="";
            if(type==="Cause Mix"){
                caption=name+" is making a difference and achieved "+points+" points.";
            }else if(type==="bondeed"){
                caption=name+" is making a difference by sharing a bondeed.";
            }else if(type==="story"){
                caption=name+" is making a difference by sharing a great story.";
            }else if(type==="badge"){
                caption=name+" earned the "+badgename+" badge.";
            }
            system.ajax.publishFile(imageName,function(data){
                FB.ui({
                    method:'feed',
                    name:'maqinato',
                    link:system.abs('home')+'index.php?activity='+activityId,
                    picture:data.published,
                    caption:caption,
                    description:'Connect with maqinato'
                },function(data){
                    if(data){
                        system.ajaxSocial.shareActivity(activityId);
                    }
                });
            });
        });
        var urlTwitter=system.abs('home')+obj.getActivityId(activity);
        var twUrl="";
        if(type==="Cause Mix"){
            twUrl=name+"'s giving achieved "+points+" points - ";
        }else if(type==="bondeed"){
            twUrl=name+" shared a bondeed - ";
        }else if(type==="story"){
            twUrl=name+" shared a great story - ";
        }else if(type==="badge"){
            twUrl=name+" earned the "+badgename+" badge -";
        }
        activity.find(".btn-sh-twitter").attr("href","https://twitter.com/intent/tweet?text="+twUrl+"&url="+urlTwitter+"&via=maqinato");
        activity.find(".btn-sh-twitter").click(function(e){
            e.preventDefault();
            var activityId=obj.getActivityId(activity);
            system.ajaxSocial.shareActivity(activityId);
        });
    };
    //Eventos para cada comentario
    obj.commentEvents=function(comment){
        //Event for delete comments
        comment.find(".btn-delete-comment").click(function(e){
            e.preventDefault();
            var comment=$(this).parent();
            var activityId=obj.getActivityId(comment.closest(".activity"));
            var commentId=$(this).parent().attr("id").replace("comment","");
            system.ajaxSocial.deleteComment(commentId,function(deleted){
                if(deleted.deleted=="success"){
                    comment.remove();
                    //En caso de que sea borrado desde el modal (sino, es redundante)
                    obj.newsfeed.find("#activity"+activityId).find("#comment"+commentId).remove();
                    obj.reorder();
                }
            });
        });
    };
    
    //>>>>>>>>>>>>>>>>>>>>>>>> ACTIVITIES LOAD <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Carga las últimas X actividades. X está definido en el servidor. Solo 
     * carga las que correspondan de acuerdo al filtro y a los tags. Borra todas
     * las actividades cargadas hasta el momento y las reemplaza por las recargadas
     **/
    obj.reloadActivities=function(){
        obj.emptyNewsfeed();
        var refActivity=0;
        system.ajaxSocial.loadActivities(refActivity,-1,obj.filter,obj.tagsFilter,obj.folio,obj.profileId,function(data){
            if(data.activities!==0){
                var activities=$(data.activities);
                $.each(activities,function(index, value){
                    if(value=="[object HTMLDivElement]"){
                        if(!obj.newsfeed.find("#"+$(this).attr("id")).exist()){
                            obj.newsfeed.append($(this));
                        }
                    }
                });
                var newElems=obj.newsfeed.find(".activity");
                obj.activitiesEvents(newElems);
                obj.newsfeed.isotope('appended',newElems,function(){
                    obj.resetParameters();
                    setTimeout(function(){
                        obj.reorder();
                    },500);
                });
            }
        });
    };
    /**
     * Carga las X actividades anteriores a las mínima (obj.minActivity) que esté
     * cargada en el momento. X está definido en el servidor. Solo carga las que
     * correspondan de acuerdo al filtro y a los tags.
     **/
    obj.loadPreviousActivities=function(){
        obj.infinLoadingActivities=true;
        var refActivity=0;
        if(obj.minActivity!=Infinity){
            refActivity=obj.minActivity;
        }
        system.ajaxSocial.loadActivities(refActivity,-1,obj.filter,obj.tagsFilter,obj.folio,obj.profileId,function(data){
            if(data.activities!==0){
                var activities=$(data.activities);
                $.each(activities,function(index, value){
                    if(value=="[object HTMLDivElement]"){
                        if(!obj.newsfeed.find("#"+$(this).attr("id")).exist()){
                            obj.newsfeed.append($(this));
                        }
                    }
                });
                var newElems=obj.newsfeed.find("#activity"+obj.minActivity).nextAll().not(".isotope-hidden");
                if(obj.minActivity==Infinity){
                    newElems=obj.newsfeed.find(".activity").not(".isotope-hidden");
                }
                obj.activitiesEvents(newElems);
                newElems.addClass("tagFiltered");
                obj.newsfeed.isotope('appended',newElems,function(){
                    obj.resetParameters();
                    obj.infinLoadingActivities=false;
                    setTimeout(function(){
                        obj.reorder();
                    },500);
                });
            }
            setTimeout(function(){
                obj.infinLoadingActivities=false;
            },500);
        });
    };
    //Response from the daemon "activities"
    obj.loadedNewActivities=function(data){
        if($.trim(data.activities)!==""){
            var activities=$(data.activities);
            $.each(activities,function(index, value){
                if(value=="[object HTMLDivElement]"){
                    if(!obj.newsfeed.find("#"+$(this).attr("id")).exist()){
                        try{
                            obj.newsfeed.prepend($(this));
                        }catch(e){
                            system.debug(e);
                        }
                    }
                }
            });
            var newElems=obj.newsfeed.find("#activity"+obj.maxActivity).prevAll();
            if(obj.maxActivity==0){
                newElems=obj.newsfeed.find(".activity").not(".isotope-hidden");
            }
            obj.activitiesEvents(newElems);
            newElems.addClass("tagFiltered");
            obj.newsfeed.isotope('reloadItems').isotope({sortBy:'original-order'},function(){
                obj.resetParameters();
                obj.infinLoadingActivities=false;
                obj.reorder();
            });
            setTimeout(function(){
                obj.resetParameters();
                obj.reorder();
            },500);
        }
        //If any category has new comments, get them from the database
        if(data.withComments.length>0){
            for(var i in data.withComments){
                var activityId=data.withComments[i];
                system.ajaxSocial.loadLastComments(activityId,function(data){
                    var lastComments=data.comments;
                    for(var j in lastComments){
                        obj.responseNewComment(lastComments[j].id,lastComments[j].html,activityId);
                    }
                });
            }
            obj.resetParameters();
        }
    };
    
    /******************************** COMMENTS ********************************/
    //Check if a Coment is already in the activity
    obj.existComment=function(idActivity,idComment){
        var exist=false;
        var activity=obj.newsfeed.find("#activity"+idActivity);
        var comment=activity.find("#comment"+idComment);
        if(comment.length>0){
            exist=true;
        }
        return exist;
    };
    //Respuesta a la carga de nuevos comentarios
    obj.responseNewComment=function(id,html,activityId){
        if(!obj.existComment(activityId,id)){
            var activity=obj.newsfeed.find("#activity"+activityId);
            var comments=activity.find(".comments");
            var newQuant=parseInt(comments.attr("total"))+1;
            var totalComments=comments.find(".totalComments");
            comments.attr("total",newQuant);
            if(totalComments.hasClass("view")){
                totalComments.text("View "+newQuant+" comments");
            }
            //Inserta el nuevo comentario antes del textarea
            comments.find(".newComment").before(html);
            //Assign the comment events and reorder the newsfeed
            obj.commentEvents(comments.find("#comment"+id));
            obj.reorder();
            //IF EXIST IN THE ACTIVITIES MODAL
            if(system.activityDialog){
                var modalActivity=system.activityDialog.find(".activity");
                if(obj.getActivityId(modalActivity)===parseInt(activityId)){
                    modalActivity.find(".comments").attr("total",newQuant);
                    if(modalActivity.find(".totalComments").hasClass("view")){
                        modalActivity.find(".totalComments").text("View "+newQuant+" comments");
                    }
                    modalActivity.find(".newComment").before(html);
                    obj.commentEvents(modalActivity.find("#comment"+id));
                }
            }
            obj.resetParameters();
            //Asigna el evento de verificar la carga de imágenes a todas las etiquetas img de los comentarios
            comments.find('img').on('error',system.defaultImage);
        }
    };
    //Response for all comments of an Activity
    obj.responseAllComments=function(comments,activity){
        var commentsCont=activity.find(".comments");
        commentsCont.find(".comment").remove();
        //Pone los comentarios antes del textarea
        for(var i in comments){
            commentsCont.find(".newComment").before(comments[i].html);
        }
        //Apply the events for each comment
        activity.find(".comment").each(function(){
            obj.commentEvents($(this));
        });
        obj.reorder();
    };
}

/**
 * Pseudoclass to manage the Landing page
 **/
function Landing(){
    "use strict";
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the systems Scripts 
     **/
    obj.init=function(opts){
        //Crea una instancia de los modales
        obj.nonprofits=new Nonprofits();
        obj.recoveryPassword=new RecoveryPassword();
        //Objeto newsfeed
        obj.newsfeed=$("#newsfeeds");
        //Create the ISOTOPE instance
        obj.newsfeed.imagesLoaded(function(){
            obj.newsfeed.isotope({
                itemSelector : '.activity',
                masonry : {
                    columnWidth : 320,
                    isResizable: true
                }
            });
        });
        //Genera los eventos para los objetos del home
        obj.events();
        //Eventos de bonslider
        var partners=$("#np_partners").find(".partners");
        partners.bonslider();
        
        //Si es una invitación, muestra el signup
        if(opts.isInvitation){
            obj.clickAction();
        }

    };
    
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(){
       // creacion nonprofits
        var nonprofits=$("#nonprofits-link");
        nonprofits.click(function(e){
            e.preventDefault();
            //Crea el modal de Good deeds
            var html=$("#modals").find("#modalNonprofits").html();
            var dialogNonprofits=system.dialog({
                html:html,
                height:630,
                width:650
            });
           obj.nonprofits.init(dialogNonprofits);
        });
        var forgot=$("#forgot");
        forgot.click(function(e){
            e.preventDefault();
            var html=$("#modals").find("#modalRecoveryPassword").html();
            var dialogRecoveryPasss=system.dialog({
                html:html,
                height:"auto",
                width:300
            });
            obj.recoveryPassword.init(dialogRecoveryPasss);
        });
        //Botón getStarted
        $("#getStarted").click(function(e){
            e.preventDefault();
            obj.clickAction();
        });  
        //Asigna los eventos a las actividades
        obj.activitiesEvents(obj.newsfeed.find(".activity"));
    };
    /**
     * Función usada cuando un usuario hace click en una función que requiere registro
     * */
    obj.clickAction=function(){
        $(document).scrollTop(0);
        system.access.showSignup();
    };
    
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
            obj.activityLikeEvents(activity);
            //Botones de share
            activity.find(".btn-sh-twitter,.btn-sh-facebook").click(function(e){
                e.preventDefault();
                obj.clickAction();
            });
            //Ir a la combinación
            activity.find(".give").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                obj.clickAction();
            });
            //Eventos de usuarios
            activity.find(".user").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                obj.clickAction();
            });
        });
    };
    
    //Eventos de los flips y sliders
    obj.activityFlipAndSlidersEvents=function(activity){
        var cantAreas=activity.find(".slides").children("li").length;
        //Crea el slider de tags (antes del flip para no ocultar los tamaños)
        var tagsAreas=activity.find(".tagsArea");
        var usersImages=activity.find(".usersImages");
        //Bonslider en tags y usuarios
        tagsAreas.bonslider();
        usersImages.bonslider();
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
            obj.clickAction();
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
        activity.find(".newComment").click(function(){
            obj.clickAction();
        });
    };
    //Eventos de los likes en una actividad
    obj.activityLikeEvents=function(activity){
        activity.find(".likeButton").click(function(e){
            e.preventDefault();
            obj.clickAction();
        });
    };
    //Response for all comments of an Activity
    obj.responseAllComments=function(comments,activity){
        var commentsCont=activity.find(".comments");
        commentsCont.find(".comment").remove();
        //Pone los comentarios antes del textarea
        for(var i in comments){
            commentsCont.find(".newComment").before(comments[i].html);
        }
        obj.reorder();
    };
    /** Reorder the newsfeed **/
    obj.reorder=function(){
        obj.newsfeed.isotope('reLayout');
    };
}
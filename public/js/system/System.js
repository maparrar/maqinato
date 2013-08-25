/**
 * Pseudoclass to manage the common functions in javascript
 **/
function System(){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.application="";             //Detect the application name
    obj.server="development";       //Detect the type of server
    obj.options=null;
    
    obj.ajax=null;                  //Ajax class to load data from server
    obj.ajaxSocial=null;            //Ajax class to load social data from server
    
    obj.daemonsInterval=1000;       //The interval for the daemons in milliseconds
    obj.daemons=null;               //Daemons object
    
    //Security
    obj.security=null;
    
    //User data
    obj.userId=false;
    
    //Indica al evento onbeforeunload que se trata del logout para permitir cerrar la sesión
    obj.isLogout=false;
    
    //Third party
    //Indica si se cargó el API de Google, si es true, se inicia el loop 
    //para verificar que se cargaron los componentes especificados
    obj.googleApi=false;
    
    /************** Variables de asistencia para los componentes **************/
    //Indica si hay algún newsfeed cargado en la página actual, si lo hay, está 
    //cargado en esta variable.
    obj.newsfeed=false;
    
    //Indica si se está visualizando alguna actividad en el modal de actividades
    //  - false: no se está visualizando
    //  - otro caso: debe contener on elemento de clase .activity
    obj.activityDialog=false;
    
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the systems Scripts 
     **/
    obj.init=function(options){
        //Create the exist() function for any selector. I.E: $("selector").exist()
        $.fn.exist=function(){return this.length>0;}
        //Set the input options
        obj.options=options;        
        //Load the configuration information from the server
        obj.config=JSON.parse($("#config").val());
        if($("#userId").val()!=undefined){
            obj.userId=obj.config.user;
        }
        if(obj.config.server==="development"||obj.config.server==="testing"){
            system.debug(obj.config);
        }
        //Instanciate the pseudo-class objects
        obj.ajax=new AjaxCore();
        obj.ajaxSocial=new AjaxSocial();
        obj.security=new Security();
        
        obj.security.init();
        
        
        //Define the main events
        obj.events();
        
        
        //Start the daemons execution
        obj.daemonsInterval=obj.config.daemonsInterval;
        if(obj.config.user){
            obj.initDaemons();
        }
        
        //Configure the components defined in the user options
        if(obj.options.access){
            var signupFunction=obj.ajax.signup;
            if(obj.options.coming){
                signupFunction=obj.ajax.signupReserve;
                $(".logo").click(function(e){
                    e.preventDefault();
                });
            }
            obj.access=new Access({
                signupForm:     $(".user-registry"),
                signupCallback: signupFunction,
                loginForm:      $("#login-form"),
                loginCallback:  obj.ajax.login,
                logoutButton:   $("#logout"),
                logoutCallback: obj.ajax.logout
            });
            obj.access.init();
        }
        
        //Start the validate email events
        if(obj.options.validate&&obj.config.user){
            $("#resend").click(function(){
                obj.ajax.sendValidationEmail(function(data){
                    if(data.response==="success"){
                        system.message("The email was sent successfully.");
                    }
                });
            });
        }
        
        
        //Start the lifetime session manager function if the option is active
        if(obj.options.session&&obj.config.user){
            obj.lifetimeSession();
        }
        
        //Start the notifications functions
        if(obj.options.notifications&&obj.config.user){
            obj.notifications=new Notifications();
            obj.notifications.init({
                indicator:$("header").find(".notifications"),
                content:$("header").find(".user-notification-content"),
                activityViewer:$("#activitiesViewer")
            });
        }
        
        //Start the landing functions
        if(obj.options.landing){
            obj.ajaxRecoveryPassword= new AjaxRecoveryPassword();
            obj.ajaxNonprofits= new AjaxNonprofits();
            obj.landing=new Landing();
            obj.landing.init({
                isInvitation:obj.options.isInvitation
            });
            obj.initFacebook();
        }
                
        //Start the home functions
        if(obj.options.home&&obj.config.user){
            obj.home=new Home();
            obj.home.init();
            //Registra el newsfeed en el  sistema
            obj.newsfeed=obj.home.newsfeed;
            obj.initFacebook();
            //Si recibe el id de una actividad, la muestra
            if(obj.options.externalActivityId!==0){
                obj.showActivity(obj.options.externalActivityId);
            }
            //si recibe el id de un nuevo uesuario
            
            
        }
        //Start the friends functions
        if(obj.options.friends&&obj.config.user){
            obj.initFacebook();
            obj.friends=new Friends();
            obj.friends.init({
                profileId:obj.options.profileId,
                frienship:obj.options.frienship
            });
            //si recibe el id de un nuevo uesuario
        }
        
        //Start the giving functions
        if(obj.options.giving&&obj.config.user){
            obj.ajaxDonation=new AjaxDonation();
            obj.giving=new Giving();
            obj.giving.init({
                regive:obj.options.regive,
                folioIdInGiving:obj.options.folioIdInGiving,
                amountMin:obj.config.bonfolio.amountMin,
                amountDefault:obj.config.bonfolio.amountDefault,
                amountIncrement:obj.config.bonfolio.amountIncrement,
                lastPayment:obj.options.lastPayment,
                tagToLoad:obj.options.tagToLoad                         //Si existe un tag en la URL para cargar
            });
        }
        //Start the folio functions
        if(obj.options.folio&&obj.config.user){
            obj.ajaxDonation=new AjaxDonation();
            obj.folio=new Folio();
            obj.folio.init({
                folio:obj.options.idFolio,
                combination:obj.options.combination,
                amountMin:obj.config.bonfolio.amountMin,
                amountDefault:obj.config.bonfolio.amountDefault,
                amountIncrement:obj.config.bonfolio.amountIncrement,
                lastPayment:obj.options.lastPayment
            });
            //Registra el newsfeed en el  sistema
            obj.newsfeed=obj.folio.newsfeed;
            obj.initFacebook();
        }
        
        //Start the settings functions
        if(obj.options.profile&&obj.config.user){
            obj.initFacebook();
            obj.profile=new Profile();
            obj.profile.init({
                profileId:obj.options.profileId,
                frienship:obj.options.frienship
            });
            //Registra el newsfeed en el  sistema
            obj.newsfeed=obj.profile.newsfeed;
        }
        //Start the profile functions
        if(obj.options.settings){
            obj.settings=new Settings({
                passwordCallback:  obj.ajax.changePassword
            });
            obj.settings.init();
        }
        //Start the contact us email events
        if(obj.options.contactus){
            var contact=$(".contact-form");
            contact.find(".btn-enviar-feedback").click(function(e){
                e.preventDefault();
                var email=contact.find("#email").val();
                var message=contact.find("#message").val();
                if(message!==""){
                    obj.ajax.contactUs(email,message,function(data){
                        if(data.response==="success"){
                            contact.find(".response").removeClass("error").addClass("response").html("Thank you for your message, we received it and will get back to you as soon as possible.");
                            contact.find("#message").val("");
                        }
                    });
                }else{
                    contact.find(".response").removeClass("response").addClass("error").html("The message can not be empty.");
                } 
            });
        }
        
        
        //Inicia el loop para verificar si se cargaron los compnentes de Google
        //en caso de que obj.googleApi=true
        obj.googleApi=obj.options.googleApi;
        if(obj.googleApi&&obj.config.user){
            var interval=setInterval(function(){
                if(googleCharts){
                    obj.profile.startCharts();
                    window.clearInterval(interval);
                }
            },1000);
        }
        
        try{
            //Inserta el placeholder (porque en IE9 no funcionan!) cuando el navegador no lo soporta
            $('input, textarea').placeholder();
        }catch(e){}


        //Pone imágenes default a las imágenes que no pudieron ser cargadas
        $("img").on('error',system.defaultImage);
        //Repite la operación pasado un segundo, luego pasados tres y luego cinco segundos
        setTimeout(function(){
            $("img").on('error',system.defaultImage);
        },1000);
        setTimeout(function(){
            $("img").on('error',system.defaultImage);
        },3000);
        setTimeout(function(){
            $("img").on('error',system.defaultImage);
        },5000);
        setTimeout(function(){
            $("img").on('error',system.defaultImage);
        },10000);
    };
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> SYSTEM <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    obj.events=function(){
        /**
         * Solución temporal para ocultar las sugerencias
         * TODO: Arreglar todo el sistema de sugerencias
         * */
        $(document).click(function(e){
            var deed=$(e.target).closest("#good-deed-generator");
            if(deed.length>0){
                deed.find(".suggestion").each(function(){
                    $(this).suggestions("markRead");
                });
            }else{
                var buttonDeed=$(e.target).closest(".btn-good-deeds");
                if(buttonDeed.length===0){
                    $(".suggestion:visible").each(function(){
                        $(this).suggestions("markRead");
                    });
                }
            }
        });
        //Reset the limit time for the session when the user click or press any key
        $(document).click(function(){
            obj.resetTimeSession();
        }).keypress(function(){
            obj.resetTimeSession();
        });
        //Users click event
        $(document).on("click",".user",function(e){
            //Prevent the redirection when click on friend button
            e.stopPropagation();
            e.preventDefault();
            if($(e.target).attr("class")!="friend"){
                var id=parseInt($(this).attr("id").replace("user",""));
                obj.gotoProfile(id);
            }
        });
        
        //Evento de click para los folios
        $(document).on("click",".folio",function(e){
            e.stopPropagation();
            e.preventDefault();
            var id=parseInt($(this).attr("id").replace("folio",""));
            obj.gotoFolio(id);
        });
        
        //Set the goto giving
        $("#know").click(function(e){
            var box=$(this).closest(".box");
            e.preventDefault();
            e.stopPropagation();
            var combinationId=box.attr("combination");
            obj.gotoCombination(combinationId);
        });
                
        $(".btn_start_giving").click(function(e){
            e.preventDefault();
            obj.gotoCombination();
        });
        
        //Asigna el evento de verificar la carga de imágenes a todas las etiquetas img
        $('img').on('error',obj.defaultImage);
        
        //Evento para el click de "Invite Friends" del header
        $("#headerInviteFriends").click(function(e){
            e.preventDefault();
            var invite=new Invite();
            invite.init();
            invite.show('','home');
        });
    };
    
    obj.gotoProfile=function(userId){
        window.location=obj.rel('views')+"profile/index.php?userid="+userId;
    };
    //Go to the donation page
    obj.gotoCombination=function(combination,folio){
        var givingPage=obj.rel("views")+'giving/';
        if(combination){
            window.location.replace(givingPage+"index.php?combination="+combination);
        }else if(folio){
            window.location.replace(givingPage+"index.php?folio="+folio);
        }else{
            window.location.replace(givingPage);
        }
    };
    //Go to the donation page, con solo un tag
    obj.gotoGivingTag=function(tagId){
        var givingPage=obj.rel("views")+'giving/';
        if(tagId){
            window.location.replace(givingPage+"index.php?tag="+tagId);
        }else{
            window.location.replace(givingPage);
        }
    };
    obj.gotoFolio=function(folioId){
        window.location=obj.rel('views')+"folio/index.php?folio="+folioId;
    };
    /**
     * Redirige hacia una página enviando datos por post
     * @param {string} url de la página a la que se quieren enviar los datos
     * @param {object} data objeto con los datos en formato objeto de JS
     * */
    obj.gotoByPost=function(url,data){
        var form;
        form = $('<form />', {
            action: url,
            method: 'post',
            style: 'display: none;'
        });
        if(data!==undefined){
            $.each(data,function(name,value){
                $('<input />', {
                    type: 'hidden',
                    name: name,
                    value: value
                }).appendTo(form);
            });
        }
        form.appendTo('body').submit();
    };
    //Send a friend request
    obj.friendRequest=function(friendId,friendName,callback){
        obj.ajaxSocial.requestFriend(friendId,function(data){
            if(data.request==="request"){
                obj.message("Friend request sent to "+friendName);
            }else if(data.request==="resend"){
                obj.message("Friend request sent again to "+friendName);
            }else if(data.request==="friend"){
                obj.message(friendName+" and you are now friends");
                obj.reloadNewsfeed();
            }
            if(callback){callback(data.request);}
        });
    };
    //Elimina un amigo del usuario actual
    obj.friendDelete=function(friendId,friendName,callback){
        obj.ajaxSocial.deleteFriend(friendId,function(data){
            if(data.deleted){
                obj.message(friendName+" is no longer your friend");
                if(callback){callback(data.request);}
            }
        });
    };
    //Función para invitar a amigos fuera de bonfolio (que se haya verificado que no existen)
    obj.friendIvite=function(email){
        
    };
    //Función que llena con una imagen por default un elemento img  que no cargó 
    //correctamente su imagen.
    obj.defaultImage=function(){
        if(!$(this).attr("autoDefault")){
            var src=$(this).attr("src");
            var path=src.substring(src.indexOf("data/"),src.lastIndexOf("/")+1).replace("data/","");
            $(this).attr("src",obj.abs("img")+"default/"+path+"default.jpg");
            $(this).attr("autoDefault",true);
        }
    };
    
    //Actualiza todas las imágenes visibles de un usuario determinado
    obj.updateUserImage=function(userId,path){
        $(".user").each(function(){
            if($(this).attr("id")==="user"+userId&&$(this).find("img").length>0){
                $(this).find("img").attr("src",path);
            }
        });
    };
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> SESSIONS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    obj.lifetimeSession=function(){
        obj.resetTimeSession();
        var time = obj.config.sessionCheckTime*60*1000;
        setInterval(
            function(){
                //If the lifetime=0 keep the session alive
                if(obj.config.sessionLifetime==0){
                    obj.resetTimeSession(60);
                }
                if(new Date<obj.timeEndSession){
                    obj.ajax.refreshSession();
                }else{
                    obj.ajax.logout();
                }
            },
            time
        );
    };
    /**
     * Add the lifetimeSession (in minutes) defined in the configuration file to
     * the current time. Set the new time limit to the session.
     **/
    obj.resetTimeSession=function(addMinutes){
        if(!addMinutes){
            addMinutes=obj.config.sessionLifetime;
        }
        obj.timeEndSession=Tools.addMinutes(new Date(),addMinutes);
    };
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> DAEMONS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    obj.initDaemons=function(){
        //Set daemons object
        obj.daemons=new Daemons(obj.daemonsInterval);
        //Set the daemons Ajax function
        obj.daemons.ajaxFunction=obj.ajax.daemons;
    };
    /**
     *Force the daemons execution
     **/
    obj.forceDaemons=function(){
        obj.daemons.process();
    };
//>>>>>>>>>>>>>>>>>>> FUNCIONES ESPECIALES DEL NEWSFEED <<<<<<<<<<<<<<<<<<<<<<<<
    //Recarga las actividades del newsfeed si el newsfeed está cargado
    obj.reloadNewsfeed=function(){
        if(obj.newsfeed){
            obj.newsfeed.reloadActivities();
        }else{
            system.debug("Tratando de recargar un newsfeed que no está definido en esta página");
        }
    };
//>>>>>>>>>>>>>> FUNCIONES PARA VISUALIZACIÓN DE ACTIVIDADES <<<<<<<<<<<<<<<<<<<
    //Recarga las actividades del newsfeed si el newsfeed está cargado
    obj.showActivity=function(idActivity){
               
//        var activityContent=obj.activityViewer.find("#activityContent");
        obj.ajaxSocial.loadActivity(idActivity,function(htmlActivity){
            if(htmlActivity!='false'){ 
                  //Crea el modal del checkout
                var html=$("#commonModals").find("#modalActivity").html();
                obj.activityDialog=obj.dialog({
                    html:html,
                    height:'auto',
                    width:'auto',

                    onClose:function(){
                        obj.activityDialog=false;
                        obj.newsfeed.resetParameters();
                    },
                    onOpen:function(){
                        //borrar x del dialogo de actividad
                       $(".ui-dialog .ui-dialog-titlebar .ui-dialog-titlebar-close span").css("display","none");
                    }
                });
               
                var content=obj.activityDialog.find(".activityContainer");
                content.append(htmlActivity);
                obj.newsfeed.activitiesEvents(content.find(".activity"));
                obj.activityDialog.dialog({position:{ my: "center", at: "center", of: window }});                
                obj.newsfeed.resetParameters();
            }
        });
    };
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ERRORS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Recives an error from the server and redirect to an convenient use
     * @param error: Error Object
     **/
    obj.error=function(error){
        switch(error.name){
            case "noSession":
                //TODO: Delete the session and close the system
                break;
            case "formatImage":
                obj.message(error.description);
                break;
            case "maxSize":
                obj.message(error.description);
                break;
            case "daemonError":
                system.debug("Daemons errors");
                break;
            default:
                //
        }
    };

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> UTILS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Return the relative path for a folder from the caller file
     **/
    obj.rel=function(folder){
        var current=window.location.pathname;
        var outs=current.split("/").length-3;
        if(obj.config.server==="release"||obj.config.server==="production"){
            outs=current.split("/").length-2;
        }
        var output='';
        for(var i=0;i<outs;i++){
            output+='../';
        }
        for(var j in obj.config.paths){
            if(j==folder){
                output+=obj.config.paths[j];
            }
        }
        return output;
    };
    obj.abs=function(folder){
      var output=""; 
      var application="";
      if(system.config.application!=""){
         application=system.config.application+'/';
      }        
      for(var j in obj.config.paths){
        if(j==folder){
            output=system.config.protocol+'://'+obj.config.location+'/'+application+obj.config.paths[j];
        }
      }
      return output;
    };
    /**
     * Show an auxiliar message in the top of page
     * @param message to show 
     * @param elementToFocus to focus after close the dialog
     **/
    obj.message=function(message,elementToFocus,title){
        $("#dialogMessage").dialog( "close" );
        $("#dialogMessage").remove();
        if(title==true){
            var mtitle="";
        }else{
            mtitle="dialogNoTitle";
        };
        $("body").append('<div id="dialogMessage">'+message+'</div>');
        $("#dialogMessage").dialog({
            modal: true,
            resizable: false,
            closeOnEscape: true,
            draggable: false,
            zIndex: 9999,
            height: "auto",
            dialogClass:"messageFormat "+mtitle,
            close:function(){
                if(elementToFocus){
                    elementToFocus.focus();
                }
            }
        });
    };
    /**
     * Muestra un diálogo de JQuery-UI sin título
     * @param optsUser
     * @return elemento del DOM con el diálogo
     **/
    obj.dialog=function(optsUser){
        var def = {
            styles:"",
            height:200,
            html:"",
            modal:true,
            onClose:false,
            onOpen:false,
            position:null,
            resizable:false,
            width:200
        };
        var opts=$.extend(def,optsUser);
        var dialogName="dialog"+new Date().getTime();
        $("body").append('<div id="'+dialogName+'">'+opts.html+'</div>');
        var dialog=$("#"+dialogName);
        dialog.dialog({
            dialogClass:"dialogNoTitle "+opts.styles,
            modal:opts.modal,
            height:opts.height,
            resizable:opts.resizable,
            width:opts.width,
            position:opts.position,
            open:function(){
                if(opts.onOpen){
                    opts.onOpen();
                }
                $('.ui-widget-overlay').bind('click',function(){
                    obj.dialogClose(dialog,opts.onClose);
                });
            },
            close:function(){
                obj.dialogClose(dialog,opts.onClose);
            }
        });
        return dialog;
    };
    /**
     * Cierra un diálogo a partir del elemento generado por system.dialog
     **/
    obj.dialogClose=function(dialog,onClose){
        try{
            //Si el callback existe, lo ejecuta
            if(onClose){onClose();}
            dialog.dialog('destroy');
            dialog.remove();
        }catch(err){}
    };
    /**
     * Extrae una url de una cadena de texto, retorna la primera que encuentra
     * */
    obj.getUrl=function(text){
        var urlRegex=/(https?:\/\/[^\s]+)/g;
        var urls=text.match(urlRegex);
        var response=false;
        if(urls!==null){
            response=urls[0];
        }
        return response;
    };
    /**
     * Retorna uno de los parámetros de la URL
     * */
    obj.getURLParameter=function(name) {
        return decodeURI(
            (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
        );
    };
    /**
     * Handler for the console.debug function, avoiding the problem of IE
     **/
    obj.debug=function(message){
        console.debug(message);
        var n = new Date();
        var d = n.getDate();
        var m = n.getMonth()+1;
        var y = n.getFullYear();
        var h=n.getHours();
        var i=n.getMinutes();
        var s=n.getSeconds();
        var string='<div class="mq_debug_msg mq_js">'+
                '<div class="mq_title">'+
                    '<div>JS -></div>'+
                    '<div class="mq_file">file</div>'+
                    '<div class="mq_line">[line: 9]</div>'+
                    '<div class="mq_time">'+y+'-'+m+'-'+d+' '+h+':'+i+':'+s+'</div>'+
                '</div>'+
                '<div class="mq_content">'+
                    '<div class="mq_code"></div>'+
                    '<div class="mq_message">'+message+'</div>'+
                '</div>'+
            '</div>';
        $("#mq_debug_msgs").append(string);
    };
 //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> FACEBOOK <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
 
    obj.initFacebook=function(){
        system.facebookEmailExist=false;
        //Indica si se intenta un nuevo registro con el botón de login
        system.facebookLoginButton=false;
        var appId='644033145623801';
        if(system.config.server==="production"){
            appId='410665095707180';
        }else if(system.config.server==="release"){
            appId='171864399646299';
        }
        // Additional JS functions here
        window.fbAsyncInit = function(){
            FB.init({
                appId      : appId, // App ID
                channelUrl : '//www.bonfolio.co/vendors/facebook/channel.html', // Channel File
                status     : true, // check login status
                cookie     : true, // enable cookies to allow the server to access the session
                xfbml      : true,  // parse XFBML
                frictionlessRequests: true
            });
            //Se ejecuta el registro cuando se da click en el botón de Facebook
//            $("#facebookSignup").click(function(){
//                FB.login(function(){},{scope:'email'});
//            });
            //Se ejecuta el login cuando se da click en el botón de Facebook
            $("#facebookLogin,#facebookSignup").click(function(){
                system.facebookLoginButton=true;
                FB.getLoginStatus(function(response){
                    system.debug(response.status);
                    if (response.status === 'connected') {
                        var token=response.authResponse.accessToken;
                        loginWithFacebook(token);
                    }else{
                        FB.login(function(){},{scope:'email'});
                    }
                });
            });
            //Verifica cuando cambia el estado de la sesión en Facebook
            FB.Event.subscribe('auth.authResponseChange', function(response){
                if (response.status === 'connected'&&obj.options.landing){
                    //La persona está registrada en FB y agrega la app por primera vez
                    var token=response.authResponse.accessToken;
                    signinWithFacebook(token);
                }
            });
        };
        // Load the SDK Asynchronously
        (function(d){
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement('script'); js.id = id; js.async = true;
            js.src = "//connect.facebook.net/en_US/all.js";
            ref.parentNode.insertBefore(js, ref);
        }(document));
        //Función para registro en bonfolio
        function signinWithFacebook(token){
            if(obj.options.landing){
                var url = '/me';
                FB.api(url, function(data){
                    var email=data.email;
                    var name=data.name.split(" ")[0];
                    var lastname=data.name.split(" ")[1];
                    var sex="I";
                    var country="USA";
                    var idCity=3805;
                    var city="San Francisco";
                    var password=Tools.sha512(data.id+data.email);
                    obj.ajax.signup(name,lastname,sex,email,idCity,city,country,password,function(data){
                        if(data==="exist"){
                            system.facebookEmailExist=true;
                            system.message("The email is already registered");
                            //Si el usuario existe, borra la aplicación para que no aparezca como registrada
                            FB.api("/me/permissions","DELETE",function(){});
                        }
                    },"facebook",data.id,token);
                });
            }
        }
        function loginWithFacebook(token){
            if(obj.options.landing){
                var url = '/me';
                FB.api(url, function(data){
                    var email=data.email;
                    var password=Tools.sha512(data.id+data.email);
                    if(!obj.config.user){
                        obj.ajax.login(email,password,true,function(data){
                            if(data==="error"){
                                signinWithFacebook(token);
                            }
                        },"facebook",token);
                    }
                });
            }
        }
    };
    obj.initTwitter=function(){
        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
            if(!d.getElementById(id)){
                js=d.createElement(s);js.id=id;
                js.src="https://platform.twitter.com/widgets.js";
                fjs.parentNode.insertBefore(js,fjs);
            }
        }
        (document,"script","twitter-wjs");
    };
}


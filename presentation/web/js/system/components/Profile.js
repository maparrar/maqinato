/**
 * Pseudoclass to manage the Profile page
 **/
function Profile(){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    
    //Social resume Element
    obj.socialResumeElem=false;
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init
     **/
    obj.init=function(optsUser){
        //Obtiene los datos del usuario del perfil
        obj.id=parseInt($(".perfil-info").find("#idInfo").val());
        obj.name=$(".perfil-info").find("#nameInfo").text();
        obj.frienship=optsUser.frienship;
        //Se define la visualización si es amigo o no
        if(obj.frienship!==true&&obj.id!==system.config.user){
            $(".content-bottom .container").hide();
        }
        obj.socialResumeElem=$("#social-resume");
        //Instancia e inicia el newsfeed
        obj.newsfeed=new Newsfeed();
        obj.newsfeed.init({
            newsfeedElement:$('#newsfeeds'),
            filterBar:$(".content-bottom").find(".filter"),
            loadInit:system.config.activities.loadInit,
            loadScroll:system.config.activities.loadScroll,
            profileId:optsUser.profileId
        });
        //Crea una instancia de la búsqueda de amigos
        obj.search=new Search();
        obj.search.init({
            input:$(".friends-module").find("#inputSearch"),
            button:$(".friends-module").find("#buttonSearch"),
            content:$(".friends-module").find("#search").find("#content"),
            type:"all"
        });
        
        //Crea una instancia del modal de Good Deeds
        obj.deeds=new Deeds();
        
        //Crea la instancia del uploader de la imagen del usuario
        obj.upUser=new Uploader();
        obj.upUser.init({
            trigger:$(".img-btn-edit"),
            frame:$(".perfil-avatar"),
            outputImageH:182,
            outputImageW:242,
            callback:obj.uploadedImage
        });
        
        //Crea el objeto para subir las historias
        obj.stories=new Stories();
        obj.stories.init({
            send:$(".btn-upload-story"),
            textarea:$(".upload-stories").find("textarea"),
            callback:obj.storyUploaded
        });
        
        //Crea el objeto invite, para invitar amigos
        obj.invite=new Invite();
        obj.invite.init();
        
        //Genera los eventos para los objetos del home
        obj.events();
        
        //Inicia con el newsfeed detenido
        obj.newsfeed.stop();
        
        //Crea el scroller de la lista de tags del social resume
        $('.scroller').tinyscrollbar();
        //incia el modal de invitar amigos facebook
        obj.InviteFriendsSocial();
        //Inicia la obcion para editar perfil
        obj.editProfile();
        //carga folios
        obj.showFolios();
    };
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(){
        var badges=$("#social-resume #percentBadges");
        var buttons=$(".call-accion-cont");
        var giving=buttons.find(".btn_give");
        var deed=buttons.find(".btn-good-deeds");
        var filters=$("#fliter-module-perfil").find(".filterButton");
        var friendship=$(".perfil-info").find(".btn-friendship");
        var invite=$(".invite-buttons").find(".email, #text");
        deed.click(function(e){
            e.preventDefault();
            //Crea el modal de Good deeds
            var html=$("#modals").find("#modalDeeds").html();
            var dialogDeeds=system.dialog({
                html:html,
                height:"auto",
                width:750
            });
            obj.deeds.init(dialogDeeds,obj.folio);
            //Inicia el cargador de imágenes de Good Deeds
            obj.upDeeds=new Uploader();
            obj.upDeeds.init({
                trigger:obj.deeds.modal.find(".btn-photo"),
                frame:obj.deeds.modal.find("#deedMedia"),
                outputImageH:215,
                outputImageW:285
            });
        });
        //Filtros
        filters.click(function(e){
            e.preventDefault();
            filters.removeClass("active");
            if($(this).attr("id")==="socialResume"){
                obj.socialResumeElem.show();
                obj.newsfeed.stop();
            }else{
                obj.socialResumeElem.hide();
                obj.newsfeed.start();
            }
            $(this).addClass("active");
        });
        //Agregar o eliminar amigos
        friendship.click(function(e){
            e.preventDefault();
            var status=$(this).attr("status");
            if(status==="add"||status==="resend"||status==="accept"){
                system.friendRequest(obj.id,obj.name,function(response){
                    if(response==="request"){
                        //Cambiar botón a resend
                        friendship.attr("status","resend").text("Resend");
                    }else if(response==="friend"){
                        //Cambiar botón a friend
                        friendship.attr("status","friends").text("Remove friend");
                    }
                });
            }else if(status==="friends"){
                system.friendDelete(obj.id,obj.name,function(){
                    friendship.attr("status","add").text("Add Friend");
                });
            }
        });
        //Invitar amigos por correo
        invite.click(function(e){
            e.preventDefault();
            obj.invite.show();
        });
        //mostrar badges
        badges.click(function(e){
            e.preventDefault();
            //$('.scrollerbadge').tinyscrollbar()
            //Crea el modal de Good deeds
            var html=$("#modals").find("#modalbadges").html();
            var dialogBadges=system.dialog({
                html:html,
                height:450,
                width:450
            });
           $('.scroller').tinyscrollbar() 
        });
    };
    //Luego de subir la imagen del uploader
    obj.uploadedImage=function(data){
        system.ajaxSocial.saveUserImage(data.name,function(response){
            system.updateUserImage(system.config.user,response.thumbnail);
            $("#avatarImage").attr("src",response.image);
            //Pone imágenes default a las imágenes que no pudieron ser cargadas
            $("#avatarImage").on('error',system.defaultImage);
        });
    };
    //Luego de subir una historia
    obj.storyUploaded=function(){
        obj.socialResumeElem.hide();
        obj.newsfeed.start();
    };
    /***************************** GOOGLE CHARTS ******************************/
    //Son llamados desde System cuando el componente de grágficos ha sido cargado
    obj.startCharts=function(){
        obj.statsPoints();
        obj.statsMonthlyPoints();
    };
    /*Points Chart*/
    obj.statsPoints=function(){
        var socialResume=$("#social-resume");
        var points=socialResume.find("#points-resume");
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Signing Up',parseInt(points.find("#pointsSignup").attr("value"))],
            ['Inviting friends',parseInt(points.find("#pointsInvite").attr("value"))],
            ['Giving',parseInt(points.find("#pointsGiving").attr("value"))],
            ['Likes',parseInt(points.find("#pointsLike").attr("value"))],
            ['Sharing',parseInt(points.find("#pointsShare").attr("value"))],
            ['Bondeeds',parseInt(points.find("#pointsBondeed").attr("value"))]
        ]);
        var options = {
            'backgroundColor':"#FCFCFC",
            'chartArea':{
                left:20,
                top:10,
                width:"100%",
                height:"90%"
            },
            'height':180,
            'legend':{
                'alignment':'center',
                'position': 'right',
                'fontSize': 26
            },
            'width':280
        };
        var chart = new google.visualization.PieChart(document.getElementById('pointsChart'));
        chart.draw(data, options);
    };
    /*Monthly points Chart*/
    obj.statsMonthlyPoints=function(){
        var socialResume=$("#social-resume");
        var resume=socialResume.find("#monthlyPointsChart");
        var monthly=resume.find(".monthValue");
        var dataArray=new Array();
        dataArray.push(["Month","Pts"]);
        monthly.each(function(){
            dataArray.push([$(this).attr("month"),parseInt($(this).val())]);
        });
        var data=google.visualization.arrayToDataTable(dataArray);
        var options = {
            'backgroundColor':"#FCFCFC",
            'height':228,
            'legend':'none',
            'title': 'Points',
            'width':450
        };
        var chart = new google.visualization.LineChart(document.getElementById('monthlyPointsChart'));
        chart.draw(data, options);
    };
 //invitacion
    obj.InviteFriendsSocial=function(){
        $(".invite-buttons .fb").click(function(e){
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
    obj.showFolios=function(){
       var divFolios=$("#portafolio #faces");
       var followFolios=$("#portafolio a#btn-folioflip");
       var linumber=$("#portafolio #carrusel_folio_follow .folio").size();
       var directionNav=true;
        if(linumber<4){directionNav=false;}
        
       
       divFolios.bonflip();
       followFolios.click(function(e){
            e.preventDefault();
                setTimeout(function(){
                //Implementa el slider de las follow folios
                $('#carrusel_folio_follow').flexslider({
                    animation: "slide",
                    animationLoop: false,
                    itemWidth: 125,
                    itemMargin: 10,
                    minItems: 3,
                    maxItems: 100,
                    slideshow: false,
                    controlNav: false,             
                    directionNav:directionNav,
                        prevText: "&laquo;",
                        nextText: "&raquo;"
                });
            },420);
    var classSplit=$(this).attr("class").split(" "); 
           if(classSplit[1]!="active"){       
                followFolios.removeClass("active");
                $(this).addClass("active");
                divFolios.bonflip("flip");
            }
       });
    };
//editar perfil
    obj.editProfile=function(){
        var divProfile=$(".perfil-content .faces");
        divProfile.find( "#born" ).datepicker({
            yearRange: "1900:"+new Date('Y'),
            changeYear: true,
            constrainInput: true,
            dateFromat: "mm/dd/yy"
        });
        divProfile.bonflip();
        $(".btn-profile-edit").click(function(e){
            e.preventDefault();
            $(".perfil-content .faces").bonflip("flip");
        });
        $(".perfil-content .btn-do-edit").click(function(e){
            e.preventDefault();
            var userData=obj.checkFormProfile(divProfile);
            if(typeof(userData)==="object"){     
                system.ajax.editUser(userData,function(response){
                    if(response.updated==="success"){
                    $(".perfil-info #nameInfo").html(system.security.secureString(divProfile.find("#name").val()+" "+divProfile.find("#last-name").val()));
                    $(".perfil-info #bornInfo").html(system.security.secureString(divProfile.find("#born").val()));
                    $(".perfil-info #countryInfo").html(divProfile.find("#country option:selected").text());
                    $(".perfil-info #cityInfo").html(divProfile.find("#city option:selected").text());
                    $(".perfil-info #sex-read").html(divProfile.find("#sex option:selected").text());
                    $(".perfil-info #iamInfo").html(system.security.secureString(divProfile.find("#iam").val()));
                    }
                });                   
            }
            $(".perfil-content .faces").bonflip("flip");
        });
    };
    obj.checkFormProfile=function(divProfile){
        var error=false;
        
        var name=divProfile.find("#name").val();
        var resName=system.security.secureString(name);
        
        var lastName=divProfile.find("#last-name").val();
        var resLastName=system.security.secureString(lastName);
        
        var born=divProfile.find("#born").val();
        var resBorn=system.security.isDate(born);
        
        var sex=divProfile.find("#sex").val();
        var resSex=system.security.secureString(sex);
        
        var city=divProfile.find("#city").val();
        var resCity=system.security.secureString(city);

        var iam=divProfile.find("#iam").val();
        var resIam=system.security.secureString(iam);
        
        if(resName.length<2){
            error="enter correct name";
        }else if(resLastName.length<2){
            error="enter correct lastname";
        }else if(!resBorn){
            error="invalid date";
        }else if(resIam.length>140){
            error="many text";
        }else{
            error={
                name:resName,
                lastname:resLastName,
                sex:resSex,
                born:born,
                city:city,
                iam:resIam
            };
        }
        return error;
    };
}    

/**
 * Pseudoclass to manage the Home page
 **/
function Home(){
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
    obj.init=function(){
        //Instancia e inicia el newsfeed
        obj.newsfeed=new Newsfeed();
        obj.newsfeed.init({
            newsfeedElement:$('#newsfeeds'),
            filterBar:$(".content-bottom").find(".filter"),
            loadInit:system.config.activities.loadInit,
            loadScroll:system.config.activities.loadScroll
        });
        
        //Crea una instancia de la búsqueda de amigos
        obj.search=new Search();
        obj.search.init({
            input:$("#top-bar").find("#inputSearch"),
            button:$("#top-bar").find("#buttonSearch"),
            content:$("#top-bar").find("#content"),
            type:"all"
        });
        
        //Crea una instancia del modal de Good Deeds
        obj.deeds=new Deeds();
        
        //Genera los eventos para los objetos del home
        obj.events();
        
        //Carga las sugerencias si existen
        var suggestions=$("#suggestions").find(".suggestion");
        suggestions.suggestions({
            markReadFunction:system.ajaxSocial.suggestionMarkRead
        });
        
        //Crea el objeto para subir las historias
        obj.stories=new Stories();
        obj.stories.init({
            send:$(".btn-upload-story"),
            textarea:$(".upload-stories").find("textarea"),
            callback:obj.storyUploaded
        });
    };
    
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(){
        var topBar=$("#top-bar");
        var giving=topBar.find(".leave-maqinato-btn");
        var deed=topBar.find(".btn-good-deeds");
        var story=topBar.find(".btn-show-stories");
        var feature=$(".content-top").find(".feature");
        var featureImage=feature.find("img");
        var featureGive=feature.find(".give");
        var featureBack=feature.find(".back-face");
        var featureButton=feature.find(".btn-flip");
                
        story.click(function(e){
            e.preventDefault();
            $("#stories").toggleClass("hideStories");
            $("#portafolio").toggleClass("moveContainer");
            $(this).toggleClass("active");
        });
        giving.click(function(e){
            e.preventDefault();
            system.gotoCombination(0);
        });
        
        deed.click(function(e){
            e.preventDefault();//no comprtarse como vinculo
            //Crea el modal de Good deeds
            var html=$("#modals").find("#modalDeeds").html();//trae contenido html
            var dialogDeeds=system.dialog({
                html:html,
                height:"auto",
                width:750
            });
            obj.deeds.init(dialogDeeds);
            //Inicia el cargador de imágenes de Good Deeds
            obj.upDeeds=new Uploader();
            obj.upDeeds.init({
                trigger:obj.deeds.modal.find(".btn-photo"),
                frame:obj.deeds.modal.find("#deedMedia"),
                outputImageH:215,
                outputImageW:285
            });
        });
        
        $('.portafolioCarrusel').flexslider({
            animation: "slide",
            controlNav: false,
            itemWidth: 125,
            directionNav: true,
            minItems: 6,
            maxItems: 6,
            itemMargin: -5
        });
        
        
        // FEATURE
        //Flip Activity
        feature.find(".faces").bonflip();
        featureGive.click(function(e){
            e.preventDefault();
            system.gotoCombination(feature.attr("combination"));
        });
        featureImage.click(function(e){
            e.preventDefault();
            obj.flipFeature();
        });
        featureButton.click(function(e){
            e.preventDefault();
            obj.flipFeature();
        });
    };
        //Luego de subir una historia
    obj.storyUploaded=function(){
        $("#stories").toggleClass("hideStories");
        $("#portafolio").toggleClass("moveContainer");
        $(".btn-show-stories").toggleClass("active");
        //ocultar cuadro de texto
    };
    //Hace el flip del feature
    obj.flipFeature=function(){
        var feature=$(".content-top").find(".feature");
        var featureTags=feature.find(".tagsArea");
        feature.find(".faces").bonflip("flip",function(){
            //Bonslider en tags del feature
            if(!featureTags.hasClass("bonslider")){
                featureTags.bonslider();
            }
        });
    };
}
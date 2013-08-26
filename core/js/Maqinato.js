/**
 * Pseudoclass to manage the common functions in javascript
 **/
function Maqinato(){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.options=null;
    obj.ajax=null;                  //Ajax class to load data from server
    obj.daemonsInterval=1000;       //The interval for the daemons in milliseconds
    obj.daemons=null;               //Daemons object
    //Security
    obj.security=null;
    //Create the exist() function for any selector. I.E: $("selector").exist()
    $.fn.exist=function(){return this.length>0;}
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the systems Scripts 
     **/
    obj.init=function(options){
        //Set the input options
        obj.options=options;        
        //Load the configuration information from the server
        if($("#config").exist()){
            obj.config=JSON.parse($("#config").val());
            if($("#userId").val()!=undefined){
                obj.userId=obj.config.user;
            }
            if(obj.config.server==="development"){
                maqinato.debug(obj.config);
            }
        }
               
        
        //Define the main events
        obj.events();
        
        
        //Start the daemons execution
//        obj.daemonsInterval=obj.config.daemonsInterval;
//        if(obj.config.user){
//            obj.initDaemons();
//        }
        
        
        //Start the lifetime session manager function if the option is active
//        if(obj.options.session&&obj.config.user){
//            obj.lifetimeSession();
//        }
    };
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> SYSTEM <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    obj.events=function(){
        //Reset the limit time for the session when the user click or press any key
        $(document).click(function(){
            obj.resetTimeSession();
        }).keypress(function(){
            obj.resetTimeSession();
        });
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
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> UTILS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
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
     * Cierra un diálogo a partir del elemento generado por maqinato.dialog
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
     * Handler for the console.debug function, avoiding the problem of IE
     **/
    obj.debug=function(message){
        console.debug(message);
        if($("#mq_debug_msgs").exist()){
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
        }
    };
}


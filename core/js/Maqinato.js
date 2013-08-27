/**
 * Carga de scripts minificados: Se puede modificar el script, luego minificar y 
 * pegar aquí. La idea es reducir la cantidad de archivos que se cargan en cada 
 * página.
 * */
/**
 * Funciones del script Tools. Se debe usar:
 *      Tools.time();
 * */
function Tools(){};Tools.properties=function(e){var t=new Array;for(var n in e){if(e.hasOwnProperty(n)){t.push(n)}}return t};Tools.time=function(){var e=new Date;return e.getHours()+":"+e.getMinutes()+":"+e.getSeconds()};Tools.date=function(){var e=new Date;return e.getFullYear()+"-"+(e.getMonth()+1)+"-"+e.getDate()};Tools.now=function(){return Tools.date()+" "+Tools.time()};Tools.addMinutes=function(e,t){return new Date(e.getTime()+t*6e4)};Tools.round=function(e,t){if(!t){t=0}var n=Math.round(e*Math.pow(10,t))/Math.pow(10,t);return n};Tools.currency=function(e,t){if(t){return parseFloat(Math.round(e*100)/100).toFixed(t)}else{return Tools.formatNumber(Tools.round(e,0))}};Tools.formatNumber=function(e,t){t=t||"";e+="";var n=e.split(".");var r=n[0];var i=n.length>1?"."+n[1]:"";var s=/(\d+)(\d{3})/;while(s.test(r)){r=r.replace(s,"$1"+","+"$2")}return t+r+i};Tools.replace=function(e,t,n){var r=new RegExp(e,"g");return n.replace(r,t)};Tools.capitalize=function(e){return e.charAt(0).toUpperCase()+e.slice(1)};Tools.css=function(e){var t=document.styleSheets,n={};for(var r in t){var i=t[r].rules||t[r].cssRules;for(var s in i){if(e.is(i[s].selectorText)){n=$.extend(n,Tools.css2json(i[s].style),Tools.css2json(e.attr("style")))}}}return n};Tools.css2json=function(e){var t={};if(!e)return t;if(e instanceof CSSStyleDeclaration){for(var n in e){if(e[n].toLowerCase){t[e[n].toLowerCase()]=e[e[n]]}}}else if(typeof e=="string"){e=e.split("; ");for(var r in e){var i=e[r].split(": ");t[i[0].toLowerCase()]=i[1]}}return t;};
/**
 * Funciones del script Security. Se debe usar:
 *      Security.isemail();
 * */
function Security(){};Security.isEmail=function(e){var t=new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/);return t.test(e)};Security.isPassword=function(e){var t=new RegExp(/^[a-zA-Z0-9@#$%._-]{6,30}$/);return t.test(e)};Security.isUrl=function(e){var t=new RegExp(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/);return t.test(e)};Security.isFloat=function(e){return!isNaN(parseFloat(e))&&isFinite(e)};Security.isInt=function(e){return typeof e==="number"&&parseFloat(e)==parseInt(e,10)&&!isNaN(e)};Security.secureString=function(e){if(e){return e.replace(/[^\w\s.,áéíóúAÉÍÓÚÑñ@:)(!/\]\[]/gi,"")}else{return""}};Security.isDate=function(e){var t=e.split("/");var n=t[0];var r=t[1];var i=t[2];var s=new Date(i,n-1,r);if(!s||s.getFullYear()==i&&s.getMonth()==n-1&&s.getDate()==r&&i<2100&&i>1900){return true}else{return false}};Security.isCreditCard=function(e){var t=new RegExp(/^[0-9]{9,17}$/);return t.test(e)};Security.isCreditCardCode=function(e){var t=new RegExp(/^[0-9]{2,5}$/);return t.test(e)};
/**
 * Función para usar en i18n, para hacer traducciones. Por ahora no hace nada, 
 * pero en el futuro permitirá traducir cadenas usadas en Javascript. Por ahora
 * solo se usa como "recopiladora" para luego traducir.
 * Posibles librerías:
 *  - http://slexaxton.github.io/Jed/
 *  - http://jsgettext.berlios.de/
 * @param {string} string Cadena que se quiere traducir
 * @return {string} Cadena traducida
 * */
function _(string){
    return string;
};
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
    //Create the exist() function for any selector. i.e.: $("selector").exist()
    $.fn.exist=function(){return this.length>0;};
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the systems Scripts 
     * @param {Object} options Objeto de opciones para inicializar Maqinato
     **/
    obj.init=function(options){
        //Set the input options
        obj.options=options;        
        //Load the configuration information from the server
        if($("#mq_config").exist()){
            obj.config=JSON.parse($("#mq_config").val());
            if($("#userId").val()!==undefined){
                obj.userId=obj.config.user;
            }
            if(obj.config.environment==="development"){
                maqinato.debug(obj.config,true);
            }
        }
        
        //Define the main events
        obj.events();
        
        //Script de operaciones básicas de Ajax
        obj.ajax=new Ajax();
        
        
        //Start the daemons execution
        obj.daemonsInterval=obj.config.daemonsInterval;
        if(obj.config.user){
//            obj.initDaemons();
        }
        
        //Start the lifetime session manager function if the option is active
        if(obj.config.user){
            obj.lifetimeSession();
        }
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
     * Redirecciona a una URL dentro de la aplicación. La url debe ser del tipo
     *      controller/function/parameter1/parameter2/...
     * @param {string} url La url a la que se quiere redireccionar
     * */
    obj.redirect=function(url){
        window.location=obj.path("root")+url;
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
    /**
     * Sale del sistema, destruye la sesión y redirecciona al root
     * */
    obj.logout=function(){
        maqinato.ajax.logout(function(){
            maqinato.redirect("root");
        });
    }
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> SESSIONS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    obj.lifetimeSession=function(){
        obj.resetTimeSession();
        var time = obj.config.sessionCheckTime*60*1000;
        setInterval(
            function(){
                //If the lifetime=0 keep the session alive
                if(parseInt(obj.config.sessionLifetime)===0){
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
     * @param {int} addMinutes Cantidad de minutos a agregar al tiempo de sesión
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
     * Retorna el path de un folder en la aplicación, busca en la lista de paths
     * de parámetros
     * @param {string} folder Nombre del folder para hallar la ruta
     * @return {string} Ruta del folder
     **/
    obj.path=function(folder){
        var path="/";
        if(obj.config.application!==""){
            path+=obj.config.application+"/";
        }
        for(var j in obj.config.paths){
            if(j===folder){
                path+=obj.config.paths[j];
            }
        }
        return path;
    };
    /**
     * Return the relative path for a folder from the caller file
     * @param {string} folder Nombre del folder para hallar la ruta relativa
     * @return {string} Ruta del folder
     **/
    obj.rel=function(folder){
        var current=window.location.pathname;
        var outs=current.split("/").length-3;
        if(obj.config.application===""){
            outs=current.split("/").length-2;
        }
        var output='';
        for(var i=0;i<outs;i++){
            output+='../';
        }
        for(var j in obj.config.paths){
            if(j===folder){
                output+=obj.config.paths[j];
            }
        }
        return output;
    };
    /**
     * Muestra un diálogo de JQuery-UI
     * @param {Object} optsUser Opciones del diálogo
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
            title:"",
            width:400
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
            title:opts.title,
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
     * @param {element} dialog Un elemento de diálogo generado por Maqinato.dialog
     * @param {function} onClose función que se ejecuta cuando se cierra el diálogo
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
     * Muestra un mensaje en la consola y en el cuadro de debug de Maqinato si
     * está disponible.
     * @param {string} message Mensaje que se quiere mostrar
     * @param {bool} onlyConsole Si es false, se muestra el mensaje en la consola
     *      y en el debug de Maqinato, si está disponible. Si es false solo se 
     *      muestra en la consola.
     **/
    obj.debug=function(message,onlyConsole){
        console.debug(message);
        if($("#mq_debug_msgs").exist()&&!onlyConsole){
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
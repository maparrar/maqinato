/*maqinato (www.https://github.com/maparrar/maqinato) - Suggestions v.0.1
 *May 2013
 *Plugin para mostrar sugerencias a los usuarios
 *  options:
 *      id: identificador de la sugerencia en la base de datos
 *      content: texto mostrado en la sugerencia
 *      page: página en la que se debe mostrar
 *      element: elemento alrededor del que se mostrará la sugerencia
 *      position: Posición respecto al elemento [north|south|east|west] 
 *      arrowPosition: Posición de la flecha en el lado especificado, si es north, la $arrowPosition=0
 *                       indica que está a cero pixeles de la parte izquierda de abajo, si $arrowPosition=100
 *                       indica que está a 100 pixeles de la parte izquierda de abajo 
 *      height: Alto de la sugerencia en pixeles, si es 0 se calculará automáticamente 
 *      width: Ancho de la sugerencia en pixeles, si es 0 se calculará automáticamente
 *      image: Si tiene alguna imagen, almacena la ruta respecto a la carpteta data
 *      markReadFunction: Función de ajax que marca una sugerencia como leída,
 *      onShow: función que se ejecuta cuando se muestra la sugerencia,
 *      onHide: función que se ejecuta cuando se oculta la sugerencia,
 *      onMarkRead: función que se ejecuta cuando la sugerencia se marca como leída
 **/
;(function($){
    /**
     * Crea el plugin para cada elemento retornado por jQuery
     * @param {object} userOptions Options provided by the user
     * */
    $.fn.suggestions=function(options){
        switch(options){
            case "show":
                show(this);
                break;
            case "hide":
                hide(this);
                break;
            case "positioning":
                positioning(this);
                break;
            case "markRead":
                markRead(this);
                break;
            default:
                return this.each(function(){
                    init($(this),options);
                });
        }
    };
    
    /**
     * Initialize each element of the selector
     * @param {element} obj DOM Element that will be applied the plugin
     * @param {object} options Options provided by the user
     */
    function init(obj,options){
        //Se reinsertan las sugerencias cerca a los divs de referencia para
        //heredar el z-index y el fixed, si aplica
        var tempObj=$('<div>').append(obj.clone()).html();
        $(".suggestionsContent").find("#"+obj.attr("id")).remove();
        if(typeof options.dialog!=='undefined'){
            options.dialog.find(obj.attr("data-element")).parent().append(tempObj);
        }else{
            $(obj.attr("data-element")).parent().append(tempObj);
        }
        //Options default variables
        var def = {
            arrowLong:10,
            arrowShort:6,
            textPadding:2,
            id:obj.attr("data-id"),
            content:obj.attr("data-content"),
            page:obj.attr("data-page"),
            element:obj.attr("data-element"),
            position:obj.attr("data-position"),
            arrowPosition:obj.attr("data-arrowPosition"),
            height:obj.attr("data-height"),
            width:obj.attr("data-width"),
            image:obj.attr("data-image"),
            markReadFunction:options.markReadFunction,
            dialog:false,
            onShow:false,
            onHide:false,
            onMarkRead:false
        };
        if(typeof options.dialog!=='undefined'){
            obj=options.dialog.find(obj.attr("data-element")).parent().find("#"+obj.attr("id"));
        }else{
            obj=$(obj.attr("data-element")).parent().find("#"+obj.attr("id"));
        }
        obj.opts=$.extend(def,options);
        positioning(obj,obj.opts);
        //Eventos y evita-eventos de las sugerencias
        obj.click(function(e){
            e.stopPropagation();
            e.preventDefault();
            markRead(obj.opts.id);
        });
    };
    /**
     * Muestra la sugerencia
     * @param {element} obj DOM Element that will be applied the plugin
     * @param {object} Opciones ingresadas por el usuario
     * */
    function positioning(obj,opts){
        //Options default variables
        obj.opts = opts;
        var element=obj.opts.element;
        if(obj.opts.dialog!==false){
            element=obj.opts.dialog.find(obj.opts.element);
        }
        //Define el tamaño de la sugerencia
        obj.height(obj.opts.height).width(obj.opts.width);
        //Define la posición respecto al elemento de referencia
        var my="center middle";
        var at="center middle";
        if(obj.opts.position==="north"){
            my="bottom";
            at="top";
            obj.find(".content").height(obj.opts.height-obj.opts.arrowShort-(2*obj.opts.textPadding)).width(obj.opts.width-(2*obj.opts.textPadding)).css('margin-bottom',obj.opts.arrowShort);
        }else if(obj.opts.position==="east"){
            my="left";
            at="right";
            obj.find(".content").height(obj.opts.height-(2*obj.opts.textPadding)).width(obj.opts.width-obj.opts.arrowShort-(2*obj.opts.textPadding)).css('margin-left',obj.opts.arrowShort);
        }else if(obj.opts.position==="south"){
            my="top";
            at="bottom";
            obj.find(".content").height(obj.opts.height-obj.opts.arrowShort-(2*obj.opts.textPadding)).width(obj.opts.width-(2*obj.opts.textPadding)).css('margin-top',obj.opts.arrowShort);
        }else if(obj.opts.position==="west"){
            my="right";
            at="left";
            obj.find(".content").height(obj.opts.height-(2*obj.opts.textPadding)).width(obj.opts.width-obj.opts.arrowShort-(2*obj.opts.textPadding)).css('margin-right',obj.opts.arrowShort);
        }
        obj.position({my:my,at:at,of: element});
        
        //Modifica la posición de acuerdo a la posición de la flecha, si es necesario
        if(obj.opts.position==="north"){
            //Reposiciona el cuadro
            var init=parseInt(obj.css('left'));
            var diff=parseInt(obj.opts.width/2)-obj.opts.arrowPosition-(obj.opts.arrowLong/2);
            obj.css('left',parseInt(init+diff)+"px");
            //Posiciona la y define el tamaño de la flecha
            obj.find(".arrow")
                    .height(obj.opts.arrowShort)
                    .width(obj.opts.arrowLong)
                    .css({
                        "left":(obj.opts.arrowPosition)+"px",
                        "top":(obj.opts.height-obj.opts.arrowShort)+"px"
                    });
        }else if(obj.opts.position==="east"){
            //Reposiciona el cuadro
            var init=parseInt(obj.css('top'));
            var diff=parseInt(obj.opts.height/2)-obj.opts.arrowPosition-(obj.opts.arrowLong/2);
            obj.css('top',parseInt(init+diff)+"px");
            //Posiciona la y define el tamaño de la flecha
            obj.find(".arrow")
                    .height(obj.opts.arrowLong)
                    .width(obj.opts.arrowShort)
                    .css({
                        "left":"0px",
                        "top":(obj.opts.arrowPosition)+"px"
                    });
        }else if(obj.opts.position==="south"){
            //Reposiciona el cuadro
            var init=parseInt(obj.css('left'));
            var diff=parseInt(obj.opts.width/2)-obj.opts.arrowPosition-(obj.opts.arrowLong/2);
            obj.css('left',parseInt(init+diff)+"px");
            //Posiciona la y define el tamaño de la flecha
            obj.find(".arrow")
                    .height(obj.opts.arrowShort)
                    .width(obj.opts.arrowLong)
                    .css({
                        "left":(obj.opts.arrowPosition)+"px",
                        "top":"0px"
                    });
        }else if(obj.opts.position==="west"){
            //Reposiciona el cuadro
            var init=parseInt(obj.css('top'));
            var diff=parseInt(obj.opts.height/2)-obj.opts.arrowPosition-(obj.opts.arrowLong/2);
            obj.css('top',parseInt(init+diff)+"px");
            //Posiciona la y define el tamaño de la flecha
            obj.find(".arrow")
                    .height(obj.opts.arrowLong)
                    .width(obj.opts.arrowShort)
                    .css({
                        "left":(obj.opts.width-obj.opts.arrowShort)+"px",
                        "top":(obj.opts.arrowPosition)+"px"
                    });
        }
        //Asigna la imagen de la flecha
        obj.find(".arrow").addClass(obj.opts.position);
        
        //Muestra el objeto si no se ha mostrado
        obj.show();
    };
    /**
     * Muestra la sugerencia
     * @param {element} obj DOM Element that will be applied the plugin
     * */
    function show(obj){
//        system.debug(obj);
//        obj.show();
    };
    /**
     * Oculta la sugerencia
     * @param {element} obj DOM Element that will be applied the plugin
     * */
    function hide(obj){
        
    };
    /**
     * Marca la sugerencia como leída en la base de datos
     * @param {element} obj la sugerencia que se quiere marcar
     * */
    function markRead(obj){
        var id=parseInt(obj.attr("id").replace("suggestion",""));
        system.ajaxSocial.suggestionMarkRead(id,function(data){
            if(parseInt(data.markReaded)===1){
                $("#suggestion"+id).remove();
            }
        });
    };
    
})(jQuery);
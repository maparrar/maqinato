/*maqinato (www.https://github.com/maparrar/maqinato) - Combination v.1.0
 *2013
 *This plugin builds the interface of a combination in any DIV selected.
 *
 *PARAMETERS:
 **/
;(function($){
    $.fn.combination = function(optUser) {
        var obj=this;
        obj.element=5;
        //Options default variables
        var def = {
            height:660,                     //height of the plugin
            parentWidth: $(window).width(),
            widthLeft:230,
            widthCenter:480,
            widthRight:270
        }
        var opts = $.extend(def,optUser);
        //Set the options for each object
        var circleDefaults={
            id: null,                               //Combination identifier (For internal use only)
            div: null,                              //Div of the area (For internal use only)
            minPercent: 10,                         //Min percent for each slice
            thick: 35,                              //Thick of the circle
            callback: opts.callback,                //Function to return the combination
            causeMix: opts.causeMix,                //Dom element to write the slices
            amountDom: opts.amountDom,              //Dom element of the amount
            amountMin: opts.amountMin,              //Min value of the amount
            amountDefault: opts.amountDefault,      //Default amount value
            amountIncrement: opts.amountIncrement,  //Increment amount
            pointsFunction:opts.pointsFunction,     //Function to calculate the footpoints
            onDropCallback:opts.onDropCallback,     //Si un tag es soltado en el círculo
            onRemoveCallback:opts.onRemoveCallback, //Si un tag es eliminado del círculo
            editingFolio:opts.editingFolio,         //True en caso de que la combinación venga de un folio, false en otro caso
            tagToLoad:opts.tagToLoad                //Si existe un tag en la URL para cargar
        };
        opts.circle=$.extend(circleDefaults,optUser.circle);
        //For each object returned by jQuery
        obj.each(function(){
            var comb=$(this);
            system.combination=comb;
            //Build and sizing the combination structure: comb
            comb.append(htmlCombination(comb.attr('id')));
            comb.addClass("fpCombination");
            //Size the area from the parent (.fpCombination) size
            sizing(comb);
            comb.id=comb.attr('id');
            //Create objects and set the options
            createObjects(comb);
            
            //If receives a Combination, reload it in the circle
            if(opts.reprnt){
                comb.circle.loadCombination(opts.reprnt);
            }
        });
        //Struct the div. Show categories if are true
        function htmlCombination(){
            if(opts.reprnt){;
                var html='<div id="circle">'+
                            '<div id="pointsVisor">'+
                                '<div id="points">78</div>'+
                                '<div id="text">points</div>'+
                            '</div>'+
                            '<div id="tagVisor" class="tag"></div>'+
                            
                        '</div>'+
                        '<img id="regive" src="'+system.rel('web')+'js/vendors/combination/img/icon_regive.png" />';
                return html;
            }else{
                var html='<div id="circle">'+
                        '<div id="pointsVisor">'+
                            '<div id="points">78</div>'+
                            '<div id="text">points</div>'+
                        '</div>'+
                        '<div id="tagVisor" class="tag"></div>'+
                        '<img id="drophere" src="'+system.rel('web')+'js/vendors/combination/img/img-drop-here.png" />'+
                    '</div>';
            return html;
            }
        }
        //Size the structulre
        function sizing(comb){
            var circle=comb.children("#circle");
            var drophere=circle.children("#drophere");
            circle.height(circle.parent().height());
            circle.width(circle.parent().width());
            drophere.css({
                "left":(circle.width()-drophere.width())/2,
                "top":(circle.height()-drophere.height())/2
            });
        }
        //Create objects and set the options
        function createObjects(comb){
            //Set the circle object
            opts.circle.id=comb.id;
            opts.circle.div=comb.find("#circle");
            comb.circle=new Circle(opts.circle);
            comb.circle.setup();
        }
    };
})(jQuery);
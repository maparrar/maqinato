function Tools(){
    var obj=this;
    //Required init function
    obj.init=function(){}
}
/**
 *STATIC METHODS
 *  - Only works with the class
 *  - Called by object returns error
 *  - Use:
 *      Tools.message(message);
 **/
Tools.message=function(message,time){
    "use strict";
    var height=20;
    if(!time){time=7000;}
    var id=Math.floor((Math.random()*10000)+1);
    $("body").append('<div id="'+id+'" class="fpToolsMessage">'+message+'</div>');
    var toolMsg=$("#"+id+".fpToolsMessage");
    toolMsg.css({
        background:"#ffffb5",
        height:height,
        padding:"0px 5px 0px 5px",
        position: "fixed",
        top: getTop(-1),
        'z-index':2000
    });
    toolMsg.css({
        left:(($(window).width()-toolMsg.width())/2)
    });
    setTimeout(function(){
        $("#"+id+".fpToolsMessage").remove();
        $(".fpToolsMessage").each(function(){
            $(this).css("top",getTop($(".fpToolsMessage").index(this)));
        });
    },time);
    function getTop(index){
        if(index==-1){
            var number=$(".fpToolsMessage").length-1;
            return number*height;
        }else{
            return index*height;
        }
    }
};
//**************************** JAVASCRIPT METHODS ****************************//
//Get a list of own properties of the object
Tools.properties=function(object){
    var list=new Array();
    for (var key in object){
        if(object.hasOwnProperty(key)){
            list.push(key);
        }
    }
    return list;
}
//****************************** DATE METHODS ******************************//
Tools.time=function(){
    var date = new Date();
    return date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
}
Tools.date=function(){
    var date = new Date();
    return date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
}
Tools.now=function(){
    return Tools.date()+" "+Tools.time();
}
Tools.addMinutes=function(date,minutes){
    return new Date(date.getTime() + minutes*60000);
}
//***************************** NUMBER METHODS *****************************//
Tools.round=function(number,decimals){
    if(!decimals){decimals=0;}
    var result = Math.round(number*Math.pow(10,decimals))/Math.pow(10,decimals);
    return result;
}
/*Format un number with thousand separator an n decimals*/
Tools.currency=function(number,decimals){
    if(decimals){
        return parseFloat(Math.round(number * 100) / 100).toFixed(decimals);
    }else{
        return Tools.formatNumber(Tools.round(number,0));
    }
}
Tools.formatNumber=function(num,prefix){
    prefix = prefix || '';
    num += '';
    var splitStr = num.split('.');
    var splitLeft = splitStr[0];
    var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '';
    var regx = /(\d+)(\d{3})/;
    while (regx.test(splitLeft)) {
        splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
    }
    return prefix + splitLeft + splitRight;
}
//***************************** STRING METHODS *****************************//
Tools.replace=function(search,replace,string){
    var regexp = new RegExp(search,"g");
    return string.replace(regexp,replace);
}
Tools.capitalize=function(string){
    return string.charAt(0).toUpperCase() + string.slice(1);
}
//****************************** CSS METHODS ******************************//
/*Get the CSS properties of an element and returned as an Object
 *Example: 
 *  var style = css($("#elementToGetAllCSS"));
 *  $("#elementToPutStyleInto").css(style);
 **/
Tools.css=function(a){
    var sheets = document.styleSheets, o = {};
    for(var i in sheets) {
        var rules = sheets[i].rules || sheets[i].cssRules;
        for(var r in rules) {
            if(a.is(rules[r].selectorText)) {
                o = $.extend(o, Tools.css2json(rules[r].style), Tools.css2json(a.attr('style')));
            }
        }
    }
    return o;
}
/*Helper function for the Tools.css function*/
Tools.css2json=function(css){
    var s = {};
    if(!css) return s;
    if(css instanceof CSSStyleDeclaration) {
        for(var i in css) {
            if((css[i]).toLowerCase) {
                s[(css[i]).toLowerCase()] = (css[css[i]]);
            }
        }
    } else if(typeof css == "string") {
        css = css.split("; ");          
        for (var j in css) {
            var l = css[j].split(": ");
            s[l[0].toLowerCase()] = (l[1]);
        };
    }
    return s;
}
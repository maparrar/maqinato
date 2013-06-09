/*latest version and complete README available on Github: https://github.com/louisremi/jquery.transform.js*/
;(function(e,t,n,r,i){function T(t){t=t.split(")");var n=e.trim,i=-1,s=t.length-1,o,u,a,f=h?new Float32Array(6):[],l=h?new Float32Array(6):[],c=h?new Float32Array(6):[1,0,0,1,0,0];f[0]=f[3]=c[0]=c[3]=1;f[1]=f[2]=f[4]=f[5]=0;while(++i<s){o=t[i].split("(");u=n(o[0]);a=o[1];l[0]=l[3]=1;l[1]=l[2]=l[4]=l[5]=0;switch(u){case b+"X":l[4]=parseInt(a,10);break;case b+"Y":l[5]=parseInt(a,10);break;case b:a=a.split(",");l[4]=parseInt(a[0],10);l[5]=parseInt(a[1]||0,10);break;case w:a=M(a);l[0]=r.cos(a);l[1]=r.sin(a);l[2]=-r.sin(a);l[3]=r.cos(a);break;case E+"X":l[0]=+a;break;case E+"Y":l[3]=a;break;case E:a=a.split(",");l[0]=a[0];l[3]=a.length>1?a[1]:a[0];break;case S+"X":l[2]=r.tan(M(a));break;case S+"Y":l[1]=r.tan(M(a));break;case x:a=a.split(",");l[0]=a[0];l[1]=a[1];l[2]=a[2];l[3]=a[3];l[4]=parseInt(a[4],10);l[5]=parseInt(a[5],10);break}c[0]=f[0]*l[0]+f[2]*l[1];c[1]=f[1]*l[0]+f[3]*l[1];c[2]=f[0]*l[2]+f[2]*l[3];c[3]=f[1]*l[2]+f[3]*l[3];c[4]=f[0]*l[4]+f[2]*l[5]+f[4];c[5]=f[1]*l[4]+f[3]*l[5]+f[5];f=[c[0],c[1],c[2],c[3],c[4],c[5]]}return c}function N(e){var t,n,i,s=e[0],o=e[1],u=e[2],a=e[3];if(s*a-o*u){t=r.sqrt(s*s+o*o);s/=t;o/=t;i=s*u+o*a;u-=s*i;a-=o*i;n=r.sqrt(u*u+a*a);u/=n;a/=n;i/=n;if(s*a<o*u){s=-s;o=-o;i=-i;t=-t}}else{t=n=i=0}return[[b,[+e[4],+e[5]]],[w,r.atan2(o,s)],[S+"X",r.atan(i)],[E,[t,n]]]}function C(t,n){var r={start:[],end:[]},i=-1,s,o,u,a;(t=="none"||L(t))&&(t="");(n=="none"||L(n))&&(n="");if(t&&n&&!n.indexOf("matrix")&&_(t).join()==_(n.split(")")[0]).join()){r.origin=t;t="";n=n.slice(n.indexOf(")")+1)}if(!t&&!n){return}if(!t||!n||A(t)==A(n)){t&&(t=t.split(")"))&&(s=t.length);n&&(n=n.split(")"))&&(s=n.length);while(++i<s-1){t[i]&&(o=t[i].split("("));n[i]&&(u=n[i].split("("));a=e.trim((o||u)[0]);O(r.start,k(a,o?o[1]:0));O(r.end,k(a,u?u[1]:0))}}else{r.start=N(T(t));r.end=N(T(n))}return r}function k(e,t){var n=+!e.indexOf(E),r,i=e.replace(/e[XY]/,"e");switch(e){case b+"Y":case E+"Y":t=[n,t?parseFloat(t):n];break;case b+"X":case b:case E+"X":r=1;case E:t=t?(t=t.split(","))&&[parseFloat(t[0]),parseFloat(t.length>1?t[1]:e==E?r||t[0]:n+"")]:[n,n];break;case S+"X":case S+"Y":case w:t=t?M(t):0;break;case x:return N(t?_(t):[1,0,0,1,0,0]);break}return[[i,t]]}function L(e){return m.test(e)}function A(e){return e.replace(/(?:\([^)]*\))|\s/g,"")}function O(e,t,n){while(n=t.shift()){e.push(n)}}function M(e){return~e.indexOf("deg")?parseInt(e,10)*(r.PI*2/360):~e.indexOf("grad")?parseInt(e,10)*(r.PI/200):parseFloat(e)}function _(e){e=/([^,]*),([^,]*),([^,]*),([^,]*),([^,p]*)(?:px)?,([^)p]*)(?:px)?/.exec(e);return[e[1],e[2],e[3],e[4],e[5],e[6]]}var s=n.createElement("div"),o=s.style,u="Transform",a=["O"+u,"ms"+u,"Webkit"+u,"Moz"+u],f=a.length,l,c,h="Float32Array"in t,p,d,v=/Matrix([^)]*)/,m=/^\s*matrix\(\s*1\s*,\s*0\s*,\s*0\s*,\s*1\s*(?:,\s*0(?:px)?\s*){2}\)\s*$/,g="transform",y="transformOrigin",b="translate",w="rotate",E="scale",S="skew",x="matrix";while(f--){if(a[f]in o){e.support[g]=l=a[f];e.support[y]=l+"Origin";continue}}if(!l){e.support.matrixFilter=c=o.filter===""}e.cssNumber[g]=e.cssNumber[y]=true;if(l&&l!=g){e.cssProps[g]=l;e.cssProps[y]=l+"Origin";if(l=="Moz"+u){p={get:function(t,n){return n?e.css(t,l).split("px").join(""):t.style[l]},set:function(e,t){e.style[l]=/matrix\([^)p]*\)/.test(t)?t.replace(/matrix((?:[^,]*,){4})([^,]*),([^)]*)/,x+"$1$2px,$3px"):t}}}else if(/^1\.[0-5](?:\.|$)/.test(e.fn.jquery)){p={get:function(t,n){return n?e.css(t,l.replace(/^ms/,"Ms")):t.style[l]}}}}else if(c){p={get:function(t,n,r){var s=n&&t.currentStyle?t.currentStyle:t.style,o,u;if(s&&v.test(s.filter)){o=RegExp.$1.split(",");o=[o[0].split("=")[1],o[2].split("=")[1],o[1].split("=")[1],o[3].split("=")[1]]}else{o=[1,0,0,1]}if(!e.cssHooks[y]){o[4]=s?parseInt(s.left,10)||0:0;o[5]=s?parseInt(s.top,10)||0:0}else{u=e._data(t,"transformTranslate",i);o[4]=u?u[0]:0;o[5]=u?u[1]:0}return r?o:x+"("+o+")"},set:function(t,n,r){var i=t.style,s,o,u,a;if(!r){i.zoom=1}n=T(n);o=["Matrix("+"M11="+n[0],"M12="+n[2],"M21="+n[1],"M22="+n[3],"SizingMethod='auto expand'"].join();u=(s=t.currentStyle)&&s.filter||i.filter||"";i.filter=v.test(u)?u.replace(v,o):u+" progid:DXImageTransform.Microsoft."+o+")";if(!e.cssHooks[y]){if(a=e.transform.centerOrigin){i[a=="margin"?"marginLeft":"left"]=-(t.offsetWidth/2)+t.clientWidth/2+"px";i[a=="margin"?"marginTop":"top"]=-(t.offsetHeight/2)+t.clientHeight/2+"px"}i.left=n[4]+"px";i.top=n[5]+"px"}else{e.cssHooks[y].set(t,n)}}}}if(p){e.cssHooks[g]=p}d=p&&p.get||e.css;e.fx.step.transform=function(t){var n=t.elem,i=t.start,s=t.end,o=t.pos,u="",a=1e5,f,h,v,m;if(!i||typeof i==="string"){if(!i){i=d(n,l)}if(c){n.style.zoom=1}s=s.split("+=").join(i);e.extend(t,C(i,s));i=t.start;s=t.end}f=i.length;while(f--){h=i[f];v=s[f];m=+false;switch(h[0]){case b:m="px";case E:m||(m="");u=h[0]+"("+r.round((h[1][0]+(v[1][0]-h[1][0])*o)*a)/a+m+","+r.round((h[1][1]+(v[1][1]-h[1][1])*o)*a)/a+m+")"+u;break;case S+"X":case S+"Y":case w:u=h[0]+"("+r.round((h[1]+(v[1]-h[1])*o)*a)/a+"rad)"+u;break}}t.origin&&(u=t.origin+u);p&&p.set?p.set(n,u,+true):n.style[l]=u};e.transform={centerOrigin:"margin"}})(jQuery,window,document,Math);(function(e,t,n){"use strict";function c(e){return e.slice(0,1).toUpperCase()+e.slice(1)}var r=n.createElement("div"),i=r.style,s=["O","ms","Webkit","Moz"],o,u=s.length,a=["transform","transformOrigin","transformStyle","perspective","perspectiveOrigin","backfaceVisibility"],f,l=s.length;while(u--){if(s[u]+c(a[0])in i){o=s[u];continue}}if(!o){return}while(l--){f=o+c(a[l]);if(f in i){e.cssNumber[a[l]]=true;e.cssProps[a[l]]=f;f==="MozTransform"&&(e.cssHooks[a[l]]={get:function(t,n){return n?e.css(t,f).split("px").join(""):t.style[f]},set:function(e,t){/matrix\([^)p]*\)/.test(t)&&(t=t.replace(/matrix((?:[^,]*,){4})([^,]*),([^)]*)/,"matrix$1$2px,$3px"));e.style[f]=t}})}}})(jQuery,window,document)
/*maqinato (www.https://github.com/maparrar/maqinato) - Bonflip v.0.3
 *Jan 2013
 *This plugin builds the flip in any DIV selected.
 * callback: executed when the move id finished
 **/
;(function($){
    $.fn.bonflip = function(optUser,callback) {
        var obj=this;
        //Options default variables
        var def = {
            color:null,
            time:800,    //milliseconds
            trigger: null
        };
        var opts = $.extend(def,optUser);
        //For each object returned by jQuery
        obj.each(function(){
            var elem=$(this);
            if(!opts.color){
                opts.color=elem.parent().css("background-color");
            }
            elem.children("li").hide().first().show();
            elem.attr("face",1);
            elem.css("background",opts.color);
            var countLi=elem.children("li").length;
            elem.click(function(){
//                flip(elem);
            });
            if(!opts.trigger){
                opts.trigger=elem.closest(".activity").find(".btn-flip")
            }
            //Flip with the trigger button
            opts.trigger.click(function(){
                flip(elem);
            });
            //Flip function
            function flip(selector){
                var newFace=parseInt(selector.attr("face"))+1;
                if(newFace>countLi){
                    newFace=1;
                }
                selector.animate(
                    {
                        transform: 'skewY(-25deg) scaleX(.05)'
                    },
                    opts.time/2,
                    function(){
                        selector.children("li").hide();
                        selector.find("li:nth-child("+newFace+")").show();
                        selector.animate({
                            transform: 'scaleX(1) skewY(-180deg)'
                        },opts.time/2,function(){
                            if(callback){callback();}
                        });
                    }
                );
                selector.attr("face",newFace);
            }
        });
    };
})(jQuery);
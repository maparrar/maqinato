/* Clases necesarias para el funcionamiento del círculo:  Point, Slice, RGBColor */
function Point(e){var t=this;t.orig=e;t.c={x:0,y:0};t.r={x:0,y:0};t.setC=function(e){t.c=e;t.toReal()};t.setR=function(e){t.r=e;t.toCirc()};t.toCirc=function(){t.c.x=t.r.x-t.orig.x;t.c.y=t.orig.y-t.r.y};t.toReal=function(){t.r.x=t.c.x+t.orig.x;t.r.y=t.orig.y-t.c.y};t.distance=function(){return Math.sqrt(Math.pow(t.c.x,2)+Math.pow(t.c.y,2))};t.debug=function(){var e="Origin coordinates: ("+t.orig.x+","+t.orig.y+")\n"+"Real coordinates: ("+t.r.x+","+t.r.y+")\n"+"Circ coordinates: ("+t.c.x+","+t.c.y+")\n";return e}}function Slice(){var e=this;e.index=0;e.name=0;e.idArea=0;e.idTag=0;e.percent=0;e.minPercent=0;e.ini=0;e.end=0;e.tempPercent=0;e.selected=false;e.setPercent=function(t){e.percent=t;var n=e.ini+Math.PI/50*e.percent;if(e.percent==100){n=2*Math.PI}else if(n>2*Math.PI){n=n-2*Math.PI}e.end=n};e.setIni=function(t){e.ini=t;var n=e.ini+Math.PI/50*e.percent;e.end=n};e.setEnd=function(t){if(t>2*Math.PI){t=t-2*Math.PI}e.end=t};e.canPercent=function(t){if(t>=e.minPercent&&t<=100){return true}else{return false}}}function RGBColor(e){this.ok=false;if(e.charAt(0)=="#"){e=e.substr(1,6)}e=e.replace(/ /g,"");e=e.toLowerCase();var t={aliceblue:"f0f8ff",antiquewhite:"faebd7",aqua:"00ffff",aquamarine:"7fffd4",azure:"f0ffff",beige:"f5f5dc",bisque:"ffe4c4",black:"000000",blanchedalmond:"ffebcd",blue:"0000ff",blueviolet:"8a2be2",brown:"a52a2a",burlywood:"deb887",cadetblue:"5f9ea0",chartreuse:"7fff00",chocolate:"d2691e",coral:"ff7f50",cornflowerblue:"6495ed",cornsilk:"fff8dc",crimson:"dc143c",cyan:"00ffff",darkblue:"00008b",darkcyan:"008b8b",darkgoldenrod:"b8860b",darkgray:"a9a9a9",darkgreen:"006400",darkkhaki:"bdb76b",darkmagenta:"8b008b",darkolivegreen:"556b2f",darkorange:"ff8c00",darkorchid:"9932cc",darkred:"8b0000",darksalmon:"e9967a",darkseagreen:"8fbc8f",darkslateblue:"483d8b",darkslategray:"2f4f4f",darkturquoise:"00ced1",darkviolet:"9400d3",deeppink:"ff1493",deepskyblue:"00bfff",dimgray:"696969",dodgerblue:"1e90ff",feldspar:"d19275",firebrick:"b22222",floralwhite:"fffaf0",forestgreen:"228b22",fuchsia:"ff00ff",gainsboro:"dcdcdc",ghostwhite:"f8f8ff",gold:"ffd700",goldenrod:"daa520",gray:"808080",green:"008000",greenyellow:"adff2f",honeydew:"f0fff0",hotpink:"ff69b4",indianred:"cd5c5c",indigo:"4b0082",ivory:"fffff0",khaki:"f0e68c",lavender:"e6e6fa",lavenderblush:"fff0f5",lawngreen:"7cfc00",lemonchiffon:"fffacd",lightblue:"add8e6",lightcoral:"f08080",lightcyan:"e0ffff",lightgoldenrodyellow:"fafad2",lightgrey:"d3d3d3",lightgreen:"90ee90",lightpink:"ffb6c1",lightsalmon:"ffa07a",lightseagreen:"20b2aa",lightskyblue:"87cefa",lightslateblue:"8470ff",lightslategray:"778899",lightsteelblue:"b0c4de",lightyellow:"ffffe0",lime:"00ff00",limegreen:"32cd32",linen:"faf0e6",magenta:"ff00ff",maroon:"800000",mediumaquamarine:"66cdaa",mediumblue:"0000cd",mediumorchid:"ba55d3",mediumpurple:"9370d8",mediumseagreen:"3cb371",mediumslateblue:"7b68ee",mediumspringgreen:"00fa9a",mediumturquoise:"48d1cc",mediumvioletred:"c71585",midnightblue:"191970",mintcream:"f5fffa",mistyrose:"ffe4e1",moccasin:"ffe4b5",navajowhite:"ffdead",navy:"000080",oldlace:"fdf5e6",olive:"808000",olivedrab:"6b8e23",orange:"ffa500",orangered:"ff4500",orchid:"da70d6",palegoldenrod:"eee8aa",palegreen:"98fb98",paleturquoise:"afeeee",palevioletred:"d87093",papayawhip:"ffefd5",peachpuff:"ffdab9",peru:"cd853f",pink:"ffc0cb",plum:"dda0dd",powderblue:"b0e0e6",purple:"800080",red:"ff0000",rosybrown:"bc8f8f",royalblue:"4169e1",saddlebrown:"8b4513",salmon:"fa8072",sandybrown:"f4a460",seagreen:"2e8b57",seashell:"fff5ee",sienna:"a0522d",silver:"c0c0c0",skyblue:"87ceeb",slateblue:"6a5acd",slategray:"708090",snow:"fffafa",springgreen:"00ff7f",steelblue:"4682b4",tan:"d2b48c",teal:"008080",thistle:"d8bfd8",tomato:"ff6347",turquoise:"40e0d0",violet:"ee82ee",violetred:"d02090",wheat:"f5deb3",white:"ffffff",whitesmoke:"f5f5f5",yellow:"ffff00",yellowgreen:"9acd32"};for(var n in t){if(e==n){e=t[n]}}var r=[{re:/^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/,example:["rgb(123, 234, 45)","rgb(255,234,245)"],process:function(e){return[parseInt(e[1]),parseInt(e[2]),parseInt(e[3])]}},{re:/^(\w{2})(\w{2})(\w{2})$/,example:["#00ff00","336699"],process:function(e){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}},{re:/^(\w{1})(\w{1})(\w{1})$/,example:["#fb0","f0f"],process:function(e){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}}];for(var i=0;i<r.length;i++){var s=r[i].re;var o=r[i].process;var u=s.exec(e);if(u){channels=o(u);this.r=channels[0];this.g=channels[1];this.b=channels[2];this.ok=true}}this.r=this.r<0||isNaN(this.r)?0:this.r>255?255:this.r;this.g=this.g<0||isNaN(this.g)?0:this.g>255?255:this.g;this.b=this.b<0||isNaN(this.b)?0:this.b>255?255:this.b;this.toRGB=function(){return"rgb("+this.r+", "+this.g+", "+this.b+")"};this.toHex=function(){var e=this.r.toString(16);var t=this.g.toString(16);var n=this.b.toString(16);if(e.length==1)e="0"+e;if(t.length==1)t="0"+t;if(n.length==1)n="0"+n;return"#"+e+t+n};this.getHelpXML=function(){var e=new Array;for(var n=0;n<r.length;n++){var i=r[n].example;for(var s=0;s<i.length;s++){e[e.length]=i[s]}}for(var o in t){e[e.length]=o}var u=document.createElement("ul");u.setAttribute("id","rgbcolor-examples");for(var n=0;n<e.length;n++){try{var a=document.createElement("li");var f=new RGBColor(e[n]);var l=document.createElement("div");l.style.cssText="margin: 3px; "+"border: 1px solid black; "+"background:"+f.toHex()+"; "+"color:"+f.toHex();l.appendChild(document.createTextNode("test"));var c=document.createTextNode(" "+e[n]+" -> "+f.toRGB()+" -> "+f.toHex());a.appendChild(l);a.appendChild(c);u.appendChild(a)}catch(h){}}return u}};


/*Area class
*INPUTS:
*   opts: options defined by the user
**/
function Circle(opts){
    var obj=this;
    obj.id=opts.id;                     //Id of the combination DOM element
    obj.div=opts.div;                   //DOM element of the area
    obj.areas=opts.areas;               //List of Areas and tags
    obj.height=0;                       //Height of the circle
    obj.width=0;                        //Width of the circle
    obj.init=new Point({x:0,y:0});      //circle position over the browser
    obj.origin=new Point({x:0,y:0});    //Origin's (center of canvas) area coordinates
    obj.radius=0;                       //Radius of the middle circle
    obj.cursor=null;                    //Cursor position over the area
    //Canvas variables
    obj.canvas=null;                    //Canvas of the combination
    obj.context=null;                   //Canvas context
    obj.background=null;                //Canvas background color
    //Canvas events variables
    obj.moving=false;                   //Define if is moving on canvas
    obj.inside=false;                   //Define if is inside canvas
    obj.insideSlice=false;              //Define if is inside of one slice
    obj.distanceToSlider=0.1;           //Indica la distancia en porcentaje (0,1) para aceptar el cursor como sercano a un slider
    //Circle Variables
    obj.circleColor=null;               //Circle background color
    obj.circleHighlight=null;           //Circle Highlight background color
    obj.minPercent=opts.minPercent;     //Min percent for each slice
    obj.thick=opts.thick;               //Thick of the circle
    obj.highlight=false;                //If an draggable is on canvas
    //Slices variables
    obj.slices=new Array();             //Array of slices
    obj.maxSlices=8;                    //Maximum number of slices allowed in the circle
    obj.sliceSpace=1;                   //Space 1% of the circle between each slice
    obj.flags=opts.flags;               //Folder of flags
    //Reprnt options
    obj.reprntId=0;                     //Combination identificator if is a reprnt
    //DOM Elements
    obj.causeMix=opts.causeMix;         //DOM Element to add the html slices
    obj.amountDom=opts.amountDom;       //DOM Element of the amount
    obj.amountMin=opts.amountMin;       //Min value of the amount
    obj.amountDefault=opts.amountDefault;//Default amount value
    obj.amountIncrement=opts.amountIncrement;//Increment amount
    //Functions to use for the circle
    obj.pointsFunction=opts.pointsFunction;//Function to calculate the points
    obj.outputPoints=null;              //Dom element to write the points
    obj.callback=opts.callback;         //Function to return the slices
    obj.onDropCallback=opts.onDropCallback;//Si un tag es soltado en el círculo
    obj.onRemoveCallback=opts.onRemoveCallback;//Si un tag es eliminado del círculo
    obj.editingFolio=opts.editingFolio; //True en caso de que la combinación venga de un folio, false en otro caso
    obj.changed=false;                  //Indica si la combinación ha cambiado
    obj.tagToLoad=opts.tagToLoad;       //Si existe un tag en la URL para cargar
    
    /*Configure the area*/
    obj.setup=function(){
        //Struct the html
        obj.div.append(obj.htmlCircle());
        //set the canvas and the context
        obj.canvas=document.getElementById('canvas'+obj.id);
        //Para que explorer asimile el canvas hay que esperar...
        var waitCanvas=0;
        if ($.browser.msie&&parseInt($.browser.version)<9){
            waitCanvas=1000;
        }
        //Get styles from the Style Sheet (combination.css)
        obj.background=obj.div.css("backgroundColor");                
        obj.circleColor=obj.div.find(".circleOut").css("backgroundColor");
        obj.circleHighlight=obj.div.find(".circleOver").css("backgroundColor");
        obj.pointsVisor=obj.div.find("#pointsVisor");
        obj.outputPoints=obj.pointsVisor.find("#points");
        obj.dropHere=obj.div.find("#drophere");
        //If not support canvas, make a link to root
        obj.context=obj.canvas.getContext('2d');
        if(obj.context){
            //Set the amount events
            obj.amountEvents();
            //Set the height and width
            obj.height=obj.div.height();
            obj.width=obj.div.width();
            //Configure the init, origin points and the cursor
            obj.positionConf();
            //Set the radius of the circle
            obj.radius=(obj.height/2)-(obj.thick/2);
            //Draw canvas the first time
            obj.draw();
            //Set the canvas as droppable
            obj.div.droppable({
                over:function(){     obj.onDropOver();},
                out:function(){      obj.onDropOut();},
                drop: function(e,ui){obj.onDrop(ui);}
            });
            
            //Si viene un tag en la url se carga en el círculo
            if(obj.tagToLoad){
                obj.loadTag(obj.tagToLoad);
            }
            
            //Set the move event to the canvas
            if (obj.canvas.addEventListener){
                obj.canvas.onmousedown=function(e){
                    obj.canvasOnMouseDown(e.pageX,e.pageY);
                };
                obj.canvas.onmousemove=function(e){
                    obj.canvasOnMouseMove(e.pageX,e.pageY);
                };
                obj.canvas.onmouseout=function(){
                    obj.canvasOnMouseOut();
                };
                /**
                 * PRUEBAS DE EVENTOS EN PANTALLAS TÁCTILES
                 * */
//                obj.canvas.addEventListener("touchstart",function (event) {
//                    var xy = extractXY(event.originalEvent.touches[0]);
//                    obj.canvasOnMouseDown(xy.x,xy.y);
//                },false);
//                obj.canvas.addEventListener("touchmove",function (event) {
//                    var xy = extractXY(event.originalEvent.touches[0]);
//                    obj.canvasOnMouseMove(xy.x,xy.y);
//                },false);
            } else if (obj.canvas.attachEvent){ // IE
                var trackend = false;
                var trackstart = false;
                var trackmid = false;
                obj.canvas.onselectstart = function () {
                    obj.canvas.onmousemove();
                    trackstart = true;
                    return false;
                }
                obj.canvas.onclick = function () {
                    trackend = true;
                }
                obj.canvas.onmousemove = function () {
                    var mtarget = obj.canvas;
                    var x = event.clientX + mtarget.scrollLeft;
                    var y = event.clientY + mtarget.scrollTop - 20;
                    var mtype = 'mousemove';
                    if (trackstart) {
                        trackstart = false;
                        trackmid = true;
                        mtype = 'mousedown';
                    }
                    if (trackend) {
                        trackmid = false;
                        mtype = 'mouseup';
                    }
                    if (trackend || trackmid || trackstart) {
                        trackend = false;
                        ev_canvas({
                            type: mtype,
                            layerX: x,
                            layerY: y,
                            target: mtarget
                        });
                    }
                }
            }
            $(document).mousemove(function(e){
                if(obj.moving){
                    //Set the cursor position
                    obj.cursor.setR({x:e.pageX,y:e.pageY});
                    obj.movingSlider(obj.cursor,obj.inside);
                    obj.draw();
                }
                if(obj.removing){
                    //Set the cursor position
                    e.stopPropagation();
                    obj.cursor.setR({x:e.pageX,y:e.pageY});
                    obj.removingSlice(obj.cursor,obj.insideSlice);
                    obj.draw();
                }
            }).mouseup(function(){
                if(obj.moving){
                    obj.calculatePoints();
                    obj.moving=false;
                }
                obj.removing=false;
                $("body").removeClass("fpUnselect");
            });
        }else{
            //Make a link to maqinato
            system.message("Sorry, your browser does not support maqinato circle");
        }
    }
    
/**************************************  PRINCIPAL METHODS  **************************************/
    //Calculate the points in the server using AJAX
    obj.calculatePoints=function(){
        obj.pointsFunction(obj.getAmount(),obj.getSlices(),obj.reprntId,function(points){
            var total=0;
            if(points.total){
                total=points.total;
            }
            if(total>0){
                obj.outputPoints.text(total);
                obj.pointsVisor.show();
                obj.dropHere.hide();
            }else{
                obj.pointsVisor.hide();
                obj.dropHere.show();
            }
        });
    };
    /*Return the combination image*/
    obj.getImage=function(){
        var image=null;
        obj.draw(false);
        image=obj.canvas.toDataURL("image/png");
        return image;
    }
    //Load a combination in the circle
    obj.loadCombination=function(combination){
        for(var i in combination.slices){
            var inputSlice=combination.slices[i];
            var inputTag=inputSlice.tagObject;
            var slice=new Slice();
            slice.id=inputSlice.id;
            slice.type=inputTag.type;
            slice.name=inputTag.name;
            slice.idArea=inputTag.area.id;
            slice.idTag=inputTag.id;
            slice.color=inputTag.area.color;
            slice.percent=parseInt(inputSlice.percent);
            slice.minPercent=obj.minPercent;
            slice.ini=parseFloat(inputSlice.angleIni);
            slice.end=parseFloat(inputSlice.angleEnd);
            obj.slices.push(slice);
        }
        obj.reasignIndexSlices();
        obj.setReprnt(combination.id);
        obj.draw();
        obj.calculatePoints();
    };
    /**
     * Carga un tag pasado como parámetro. Usado para cargar los tags cuando vienen
     * de una página externa.
     * @param {Tag} tag Objeto de tipo tag en Json
     * */
    obj.loadTag=function(tag){
        var added=obj.addSliceFromData(tag.type,tag.name,tag.area.id,tag.id,tag.area.color);
        obj.draw();
        if(added){
            setTimeout(function(){
                obj.onDropCallback(tag.id);
            },500);
        }
    };
    /**
     * Carga un tag a partir de un elemento Tag del DOM
     * @param {element} element Elemento Tag del DOM
     * */
    obj.loadTagFromElement=function(element){
        var type=parseInt(element.attr("type"));
        var id=parseInt(element.attr("id").replace("tag",""));
        var area=parseInt(element.attr("area"));
        var color=element.attr("color");
        var added=obj.addSliceFromData(type,element.text(),area,id,color);
        obj.draw();
        if(added){
            setTimeout(function(){
                obj.onDropCallback(id);
            },500);
        }
    };
    /*Set as a reprnt*/
    obj.setReprnt=function(reprntId){
        var reprntDom=obj.div.find("#reprnt");
        obj.reprntId=reprntId;
        if(!obj.editingFolio){
//            reprntDom.text("Regifve");
        }
    }
    /*Reset the reprnt value if any value is changed*/
    obj.resetReprnt=function(){
        var reprntDom=obj.div.parent().find("#regive");
        reprntDom.hide();
        obj.reprntId=0;
        reprntDom.text("");
        obj.changed=true;
        obj.calculatePoints();
    }

/**************************************  CIRCLE EVENTS  **************************************/
    //When a Tag is dropped into the circle
    obj.onDrop=function(object){
        //Reload the position values
        obj.positionConf();
        obj.highlight=false;
        obj.draw();
        //Add the slice to the array of slices
        var id=object.draggable.attr('id').replace("area","").replace("tag","");
        var typeString=$.trim(object.draggable.attr('class').replace("ui-draggable","").replace("active",""));
        var color=object.draggable.attr('color');
        var type=0;
        var name="";
        var idArea=0;
        var idTag=0;
        if(typeString=="area"){
//            type=3
//            name=object.draggable.find("h2").text();
//            idArea=parseInt(id);
        }else{
            type=1;
            name=object.draggable.text();
            idArea=parseInt($.trim(object.draggable.attr('class').replace("area","").replace("tag","").replace("results","")));
            idTag=parseInt(id);
            var added=obj.addSliceFromData(type,name,idArea,idTag,color);
            obj.draw();
            //Restore the keywords terms to the system search
            system.giving.search.restoreKeywords();
            if(added){
                obj.onDropCallback(id);
            }
        }
    }
    //When a Tag is moved into the circle before drop
    obj.onDropOver=function(){
        obj.highlight=true;
        obj.draw();
    }
    //When a Tag is moved out of the circle
    obj.onDropOut=function(){
        obj.highlight=false;
        obj.draw();
    }
    //Canvas events
    obj.canvasOnMouseDown=function(eventX,eventY){
        //Set the cursor position
        obj.cursor.setR({x:eventX,y:eventY});
        //Move the angles if the cursor is inside of one slice's slider
        obj.inside=obj.closeToSlider(obj.cursor);
        if(obj.inside){
            obj.moving=true;
            $("body").addClass("fpUnselect");
        }
        //If drag since a slice, to remove it
        obj.insideSlice=obj.inSlice(obj.cursor);
        if(obj.insideSlice&&!obj.inside){
            obj.removing=true;
            $("body").addClass("fpUnselect");
        }
    }
    obj.canvasOnMouseMove=function(eventX,eventY){
        if(!obj.moving){
            //Set the cursor position
            obj.cursor.setR({x:eventX,y:eventY});
            //If drag since a slice
            var inSlice=obj.inSlice(obj.cursor);
            if(inSlice){
                obj.notselected();
                obj.selected(inSlice,true);
            }else{
                obj.notselected();
            }
        }
    }
    obj.canvasOnMouseOut=function(){
        obj.notselected();
    }
    //Check if a Tag exist in the list of tags, if is return true, false otherwise
    obj.existTag=function(idTag){
        var exist=false;
        for(var i in obj.slices){
            if(obj.slices[i].idTag==idTag){
                exist=true;
                break;
            }
        }
        return exist;
    }

/**********************************  CANVAS: DRAW OBJECTS  **********************************/    
    /*Draw the canvas*/
    obj.draw=function(withoutPercent){
        obj.clean();
        obj.drawCircle();
        obj.drawSlices(withoutPercent);
        //Show the slices in the donations div
        obj.showSlices();
    }
    /*Draw the circle*/
    obj.drawCircle=function(){
        //Draw the outer circle
        obj.context.beginPath();
        obj.context.fillStyle = obj.circleColor;
        obj.context.arc(obj.width/2,obj.height/2,obj.radius+(obj.thick/2), 0, Math.PI*2, true);
        obj.context.closePath();
        obj.context.fill();
        //Draw the inner circle
        obj.context.beginPath();
        if(!obj.highlight){
            obj.context.fillStyle = obj.background;
        }else{
            obj.context.fillStyle = obj.circleHighlight;
        }
        obj.context.arc(obj.width/2,obj.height/2,obj.radius-(obj.thick/2), 0, Math.PI*2, true);
        obj.context.closePath();
        obj.context.fill();
    }
    //Draw the slices
    obj.drawSlices=function(withoutPercent){
        for(var i in obj.slices){
            var x = obj.canvas.width/2;
            var y = obj.canvas.height/2;
            var space=0;
            if(obj.slices.length>1){
                space=(Math.PI/50)*obj.sliceSpace;
            }
            var ini=obj.slices[i].ini;
            var end=obj.slices[i].end-space;
            
            var colorSlice=obj.slices[i].color;
            //If is selected change the color
            if(obj.slices[i].selected){
                var inc=0.3;
                var color = new RGBColor(obj.slices[i].color);
                var r=parseInt(color.r-(color.r*inc));
                var g=parseInt(color.g-(color.g*inc));
                var b=parseInt(color.b-(color.b*inc));
                var newColor = new RGBColor('rgb('+r+','+g+','+b+')');
                colorSlice=newColor.toHex();
            }
            //Draw the slice
            obj.context.beginPath();
            obj.context.arc(x, y,obj.radius,(2*Math.PI)-ini,(2*Math.PI)-end,true);
            obj.context.lineWidth = obj.thick;
            obj.context.strokeStyle = colorSlice;
            obj.context.stroke();
            obj.context.closePath();
            //Draw the slider
            obj.context.beginPath();
            obj.context.arc(x, y,obj.radius,(2*Math.PI)-end,(2*Math.PI)-obj.slices[i].end,true);
            obj.context.strokeStyle = obj.background;
            obj.context.stroke();
            obj.context.closePath();
            
            //Draw text
            var mediumAngle=ini+((end-ini)/2);
            if(obj.slices[i].end<ini){
                mediumAngle=ini+(((2*Math.PI+end)-ini)/2);
            }
            
            var heightText=5;
            var widthText=33;
            //Move the circle to adjust the text
            var textPos=new Point({x:x-(widthText/2),y:y-(heightText/2)});
            textPos.setC({
                x:(obj.radius)*(Math.cos(mediumAngle)),
                y:(obj.radius)*(Math.sin(mediumAngle))
            });
            obj.context.fillStyle = '#fff';
            obj.context.font = 'bold 13px sans-serif';
            obj.context.textBaseline = 'middle';
            if(withoutPercent==null){
                obj.context.fillText(obj.slices[i].percent+"%",textPos.r.x,textPos.r.y);
            }
        }
        //Draw the arrows for 2 or more slices and if is with percent
        if(obj.slices.length>1){
            if(withoutPercent==null){
                var slider=obj.closeToSlider(obj.cursor);
                if(slider){
                    obj.drawArrow(slider,"end");
                    if(obj.slices.length===parseInt(slider)+1){
                        obj.drawArrow(0,"ini");
                    }else{
                        obj.drawArrow(parseInt(slider)+1,"ini");
                    }
                    obj.div.css("cursor","move");
                }else{
                    obj.div.css("cursor","default");
                }
            }
        }
    }
    
    /* 
     * Dibuja una flecha para un slice i.
     * @param int id del slice
     * @param string la flecha:
     *  - "ini" para la flecha inicial
     *  - "end" para la flecha final
     * */
    obj.drawArrow=function(sliceIndex,arrowToShow){
        var space=(Math.PI/50)*obj.sliceSpace;
        var ini=obj.slices[sliceIndex].ini;
        var end=obj.slices[sliceIndex].end-space;
        var degreesToSlider=5;
        var arrow=new Image();
        arrow.src = system.rel("js")+"vendors/combination/img/arrowcirculo.png";
        //Move the circle to adjust the arrows position
        var arrowPos=new Point({x:obj.canvas.width/2,y:obj.canvas.height/2});
        if(arrowToShow=="ini"){
            arrowPos.setC({
                x:(obj.radius)*(Math.cos(ini+((Math.PI/180)*degreesToSlider))),
                y:(obj.radius)*(Math.sin(ini+((Math.PI/180)*degreesToSlider)))
            });
            obj.drawRotatedImage(arrow,arrowPos.r.x,arrowPos.r.y,(Math.PI-ini));
        }else{
            arrowPos.setC({
                x:(obj.radius)*(Math.cos(end-((Math.PI/180)*degreesToSlider))),
                y:(obj.radius)*(Math.sin(end-((Math.PI/180)*degreesToSlider)))
            });
            obj.drawRotatedImage(arrow,arrowPos.r.x,arrowPos.r.y,-end);
        }
    };
    
    /*Clean the canvas with the background color of the div*/
    obj.clean=function(){
        obj.canvas.width=obj.width;
        obj.canvas.height=obj.height;
        obj.context.fillStyle = obj.background;
        obj.context.fillRect(0,0,obj.width,obj.height);
    }
    
    //Draw an rotated image in the canvas, angle in radians
    //Modified version of: http://creativejs.com/2012/01/day-10-drawing-rotated-images-into-canvas/
    obj.drawRotatedImage=function(image,x,y,angle){
        // save the current co-ordinate system 
        // before we screw with it
        obj.context.save();
        // move to the middle of where we want to draw our image
        obj.context.translate(x, y);
        // rotate around that point
        obj.context.rotate(angle);
        // draw it up and to the left by half the width
        // and height of the image 
        obj.context.drawImage(image, -(image.width/2), -(image.height/2));
        // and restore the co-ords to how they were when we began
        obj.context.restore(); 
    }
    
/**********************************  SLICES FUNCTIONS  **********************************/
    /*
     *Add an slice to the circle from its values
     * type: int type of Tag:
     *      |  1 | Giving Tag       |
            |  2 | Good Deed Tag    |
            |  3 | Area Tag         |
            |  4 | Organization Tag |
    *   name: string Name of the Tag
    *   idArea: int Area identificator
    *   idTag: int Tag identificator
    *   color: string Color of the Area
    */
    obj.addSliceFromData=function(type,name,idArea,idTag,color){
        var slice=new Slice();
        slice.type=type;
        slice.name=name;
        slice.idArea=parseInt(idArea);
        slice.idTag=parseInt(idTag);
        slice.color=color;
        slice.minPercent=obj.minPercent;
        return obj.addSlice(slice);
    }
    //Add a slice to the array. If exist does not add the slice.
    obj.addSlice=function(slice){
        var exist=false;
        var added=false;
        if(obj.slices.length<obj.maxSlices){
            for(var i in obj.slices){
                if(obj.slices[i].idArea==slice.idArea&&obj.slices[i].idTag==slice.idTag){
                    exist=true;
                    break;
                }
            }
            if(!exist){
                var percent=obj.getPercent();
                if(percent>obj.minPercent){
                    slice.index=obj.slices.length;
                    slice.setPercent(percent);
                    obj.slices.push(slice);
                    obj.calculateAngles();
                    obj.resetReprnt();
                    obj.calculatePoints();
                }
                added=true;
            }
        }
        return added;
    }
    //Remove all the slices from the array and redraw all
    obj.removeSlices=function(){
        //Retorna los tags a la lista de tags
        for(var i in obj.slices){
             obj.onRemoveCallback(obj.slices[i].idTag);
        }
        obj.slices=new Array();
        obj.resetReprnt();
        obj.calculatePoints();
        obj.removing=false;
        obj.draw();
        //Muestra el principio de la lista (mover esto de aquí)
        $("#cause-mix").find("#list").css("margin-top",0);
    }
    //Remove a slice from the array and redraw all
    obj.removeSlice=function(index){
        var percent=obj.slices[index].percent;
        //Retorna el tag a la lista de tags
        obj.onRemoveCallback(obj.slices[index].idTag);
        if(obj.slices.length==1){
            obj.slices=new Array();
        }else{
            obj.slices.splice(index,1);
            obj.redistPercent(percent);
        }
        obj.reasignIndexSlices();
        obj.resetReprnt();
        obj.calculatePoints();
        obj.removing=false;
        obj.draw();
        //Muestra el principio de la lista (mover esto de aquí)
        $("#cause-mix").find("#list").css("margin-top",0);
    }
    //Reassign the index to all slices
    obj.reasignIndexSlices=function(){
        for(var i in obj.slices){
            obj.slices[i].index=i;
        }
    }
    //Configure and determine if a slice is removing
    obj.removingSlice=function(cursor,index){
        if(cursor.distance()>(obj.radius+(obj.thick/2))){
            obj.removeSlice(index);
        }
    }
    //Return the list of slices with the percent normalized (0,1) in percentNorm variable
    obj.getSlices=function(){
        for(var i in obj.slices){
            obj.slices[i].percentNorm=obj.slices[i].percent/100;
        }
        return obj.slices;
    }
    //Move the slices between the slider that is moving
    //INPUTS:
    //  cursor: pointer position
    //  index:  index of the slice that is moving
    obj.movingSlider=function(cursor,index){
        var diff=0;
        var end=obj.slices[index].end;
        var opposite=obj.opposite(end);
        var pi=Math.PI;
        index=parseInt(index);
        var angle=Math.atan2(cursor.c.y,cursor.c.x);
        //Mapping the angle to positive radians
        if(angle<0){
            angle=2*pi-Math.abs(angle);
        }
        if(end==0||end==2*pi){
            if(angle>0&&angle<=pi){
                diff=angle;
            }else{
                diff=angle-(2*pi);
            }
        }else if(end==pi){
            diff=angle-pi;
        }else if(end<pi){
            if(angle<=opposite&&angle>end){
                diff=angle-end;
            }else{
                if(angle>=opposite&&angle<2*pi){
                    diff=angle-end-(2*pi);
                }else if(angle>0&&angle<end){
                    diff=angle-end;
                }
            }
        }else if(end>pi){
            if(angle>=opposite&&angle<end){
                diff=angle-end;
            }else{
                if(angle>end&&angle<2*pi){
                    diff=angle-end;
                }else{
                    diff=((2*pi)-end)+angle;
                }
            }
        }
        //Snap to integer percentajes
        var percent=Math.round((50*diff)/pi);        
        if(obj.slices.length>1){
            var last=obj.slices.length-1;
            var next=index+1;
            if(index==last){
                next=0;
            }
            var canCurrent=obj.slices[index].canPercent(obj.slices[index].percent+percent);
            var canNext=obj.slices[next].canPercent(obj.slices[next].percent-percent);
            if(canCurrent&&canNext){
                obj.slices[index].setPercent(obj.slices[index].percent+percent);
                obj.slices[next].setIni(obj.slices[index].end);
                obj.slices[next].setPercent(obj.slices[next].percent-percent);
            }
        }
        obj.resetReprnt();
    }
    //Return the INDEX of the slice if the cursor is inside
    obj.inSlice=function(cursor){
        var angle=Math.atan2(cursor.c.y,cursor.c.x);
        var index=false;
        var space=(Math.PI/50)*obj.sliceSpace;
        //Set the radius interval
        var minRadius=obj.radius-(obj.thick/2);
        var maxRadius=obj.radius+(obj.thick/2);
        var distance=cursor.distance();
        if(distance>=minRadius&&distance<=maxRadius){
            //Mapping the angle to positive radians
            if(angle<0){
                angle=2*Math.PI-Math.abs(angle);
            }
            for(var i in obj.slices){
                var ini=obj.slices[i].ini;
                var end=obj.slices[i].end-space;
                if(round(end,4)==0){
                    end=2*Math.PI;
                }
                if(ini>end){
                    if((angle>ini&&angle<2*Math.PI)||(angle>0&&angle<end)){
                        index=i;
                        break;
                    }
                }else if(angle<=end&&angle>=ini){
                    index=i;
                    break;
                }
            }
        }
        return index;
    }
    //Return the INDEX of the slices if the cursor is over the slider
    obj.inSlider=function(cursor){
        var angle=Math.atan2(cursor.c.y,cursor.c.x);
        var index=false;
        var space=(Math.PI/50)*obj.sliceSpace;
        //Set the radius interval
        var minRadius=obj.radius-(obj.thick/2);
        var maxRadius=obj.radius+(obj.thick/2);
        var distance=cursor.distance();
        if(obj.slices.length>1){
            if(distance>=minRadius&&distance<=maxRadius){
                //Mapping the angle to positive radians
                if(angle<0){
                    angle=2*Math.PI-Math.abs(angle);
                }
                for(var i in obj.slices){
                    var end=obj.slices[i].end;
                    if(round(end,4)==0){
                        end=2*Math.PI;
                    }
                    var alpha=end;
                    var beta=end-space;
                    if(angle<=alpha&&angle>=beta){
                        index=i;
                        break;
                    }
                }
            }
        }
        return index;
    }
    //Return the INDEX of the slices if the cursor is near to the slider
    obj.closeToSlider=function(cursor){
        var angle=Math.atan2(cursor.c.y,cursor.c.x);
        var index=false;
        var space=(Math.PI/50)*obj.sliceSpace;
        //Define el radio máx y min incluyendo el espacio adicional para estar cerca
        var minRadius=obj.radius-(obj.thick/2)-(obj.radius*obj.distanceToSlider);
        var maxRadius=obj.radius+(obj.thick/2)+(obj.radius*obj.distanceToSlider);
        var distance=cursor.distance();
        if(obj.slices.length>1){
            if(distance>=minRadius&&distance<=maxRadius){
                //Mapping the angle to positive radians
                if(angle<0){
                    angle=2*Math.PI-Math.abs(angle);
                }
                for(var i in obj.slices){
                    var end=obj.slices[i].end;
                    if(round(end,4)==0){
                        end=2*Math.PI;
                    }
                    var alpha=end+((Math.PI/2)*obj.distanceToSlider);
                    var beta=end-space-((Math.PI/2)*obj.distanceToSlider);
                    if(end==2*Math.PI){
                        alpha=((Math.PI/2)*obj.distanceToSlider);
                        if(angle>0&&angle<alpha){
                            index=i;
                            break;
                        }else{
                            if(angle>=beta){
                                index=i;
                                break;
                            }
                        }
                    }else{
                        if(angle<=alpha&&angle>=beta){
                            index=i;
                            break;
                        }
                    }
                }
            }
        }
        return index;
    }
    //Substract the x% to each slice percent and retuned (0,100)
    obj.getPercent=function(){
        var substract=0.33;  //Percent to substract (0,1)
        var percent=0;
        var sumCurrent=0;
        if(obj.slices.length>0){
            //In the first loop take a percent of each slice
            for(var i=0;i<obj.slices.length;i++){
                var part=obj.slices[i].percent*substract;
                var newPercent=Math.round(obj.slices[i].percent-part);
                if(newPercent>=obj.minPercent){
                    obj.slices[i].tempPercent=newPercent;
                    sumCurrent+=newPercent;
                }else{
                    obj.slices[i].tempPercent=obj.minPercent;
                    sumCurrent+=obj.slices[i].tempPercent;
                }
            }
            //For the percent, return the value from substract the others
            percent=100-sumCurrent;
            //If don't, take all until complete the minimum
            if(percent<obj.minPercent){
                for(var j=0;j<obj.slices.length;j++){
                    if(percent>obj.minPercent){
                        break;
                    }
                    percent+=obj.slices[j].tempPercent-obj.minPercent;
                    obj.slices[j].tempPercent=obj.minPercent;
                }
            }
            //If complete the minimum, set the persent values
            if(percent>=obj.minPercent){
                for(var k=0;k<obj.slices.length;k++){
                    obj.slices[k].setPercent(obj.slices[k].tempPercent);
                }
            }
        }else{
            percent=100;
        }
        return percent;
    }
    //Redistributes value of a slice removed
    obj.redistPercent=function(percent){
        var part=percent/obj.slices.length;
        var sum=0;
        var angle=0;
        for(var i=0;i<obj.slices.length-1;i++){
            obj.slices[i].setPercent(Math.round(obj.slices[i].percent+part));
            obj.slices[i].setIni(angle);
            angle=obj.slices[i].end;
            sum+=obj.slices[i].percent;
        }
        obj.slices[obj.slices.length-1].setPercent(100-sum);
        obj.slices[obj.slices.length-1].setIni(angle);
    }
    /*Redistribuye los porcentajes luego de hacer el cambio en el causemix
     * @param int index índice del slice que se cambió y que se debe dejar fijo
     * @param int percent porcentaje que tiene el slice del índice
     */
    obj.redistPercentMix=function(index,percent){
        var angle=0;
        var diff=0;
        var remain=100-percent;
        var min=obj.minPercent;
        var max=100-((obj.slices.length-1)*obj.minPercent);
        //Se divide el anterior porcentaje del slice[index] en el resto de slices
        var percentPart=obj.slices[index].percent/(obj.slices.length-1);
        //Se redistribuye ese porcentaje en los otros slices
        var accumulated=0;
        for(var i=0;i<obj.slices.length;i++){
            if(i!=index){
                obj.slices[i].setPercent(Math.round(obj.slices[i].percent+percentPart));
                //Suma los futuros porcentajes para saber cuanto se debe restar o sumar para completar los 100
                var newPercent=Math.round(remain*(obj.slices[i].percent/100));
                if(newPercent<min){newPercent=min;}
                if(newPercent>max){newPercent=max;}
                accumulated+=newPercent;
            }
        }
        diff=accumulated-remain;
        //Se calcula el nuevo porcentaje de cada slice ponderando el sobrante del slice[index] por el respectivo porcentaje
        var sum=0;
        for(var i=0;i<obj.slices.length;i++){
            if(i!=index){
                //Se corrige el valor en caso de que supere los mínimos o máximos
                var newPercent=Math.round(remain*(obj.slices[i].percent/100));
                if(newPercent>max){newPercent=max;}
                if(newPercent<=min){
                    newPercent=min;
                }else{
                    if((newPercent-min)>=diff){
                        newPercent=newPercent-diff;
                        diff=0;
                    }else{
                        diff=diff-(newPercent-min);
                        newPercent=min;
                    }
                }
                obj.slices[i].setPercent(newPercent);
            }else{
                obj.slices[i].setPercent(Math.round(percent));
            }
            obj.slices[i].setIni(angle);
            angle=obj.slices[i].end;
        }
    }
    //Calculates the angles for each slice
    obj.calculateAngles=function(){
        var marker=0;      //Position of the last position of slice (radians)
        for(var i in obj.slices){
            obj.slices[i].setIni(marker);
            marker=obj.slices[i].end;
        }
    }
    //Highlight or not a slice
    obj.selected=function(index,selected){
        obj.slices[index].selected=selected;
        obj.clean();
        obj.tagVisor(index);
        obj.drawCircle();
        obj.drawSlices();
    }
    //Remove highlight of all slices
    obj.notselected=function(){
        for(var i in obj.slices){
            obj.selected(i,false);
        }
        obj.tagVisor(false);
    }
    //Muestra el tag en el centro del círculo cuando se pasa el ratón por encima
    obj.tagVisor=function(index){
        var tag=obj.div.find("#tagVisor");
        if(index){
            tag.text(obj.reWriteTags(obj.slices[index].name).texttag);
            tag.removeClass();
            tag.addClass("tag area"+obj.slices[index].idArea);
            tag.show();
        }else{
            tag.hide();
        }
    }
    //Reload the init position (left from windows border to canvas)
    obj.positionConf=function(){
        obj.init.setR({
            x:obj.div.offset().left,
            y:obj.div.offset().top
        });
        obj.origin.setR({
            x:(obj.width/2)+obj.init.r.x,
            y:(obj.height/2)+obj.init.r.y
        });
        obj.cursor=new Point(obj.origin.r);
    }
    //Assign events to the slices
    obj.eventSlices=function(){
        var slices=obj.causeMix.find(".slice");
        var deletes=obj.causeMix.find(".delete");
        var inputs=obj.causeMix.find("input");
        slices.mouseenter(function(){
            obj.selected($(this).attr("id").replace("slice",""),true);
        }).mouseleave(function(){
            obj.selected($(this).attr("id").replace("slice",""),false);
            obj.tagVisor(false);
        });
        deletes.click(function(){
            var index=$(this).parent().attr("id").replace("slice","");
            obj.removeSlice(index);
        });
        inputs.change(function(){
            var percent=parseInt($(this).val().replace("%",""));
            var index=$(this).parent().attr("id").replace("slice","")
            //CONDICIONES:
            //Solo se aceptan cambios cuando hay al menos un slice
            //Solo se reciben enteros positivos
            //El mínimo porcentaje aceptable para un slice es obj.minPercent
            //El máximo porcentaje aceptable para un slice es 100-((cantSlices-1)*obj.minPercent)
            // para dejar el mínimo para los demás
            var min=obj.minPercent;
            var max=100-((obj.slices.length-1)*obj.minPercent);
            if(system.security.isInt(percent)&&percent>=min&&percent<=max&&obj.slices.length>1){
                obj.redistPercentMix(index,percent);
                obj.draw();
                obj.calculatePoints();
            }else{
                $(this).val($(this).attr("percent"));
            }
        }).click(function(){
            $(this).val("");
        });
    }
    //Set values for an Slice
    obj.setSliceValues=function(idTag,tagName,type,points,amount,organization){
        for(var i in obj.slices){
            if(type==4){
                if(obj.slices[i].name==tagName){
                    obj.slices[i].idTag=idTag;
                    obj.slices[i].type=parseInt(type);
                    obj.slices[i].points=parseInt(points);
                    obj.slices[i].amount=parseFloat(amount);
                    obj.slices[i].organization=organization;
                }
            }else{
                if(obj.slices[i].idTag==idTag){
                    obj.slices[i].type=parseInt(type);
                    obj.slices[i].points=parseInt(points);
                    obj.slices[i].amount=parseFloat(amount);
                    obj.slices[i].organization=organization;
                }
            }
        }
    }
/**********************************  OTHER OBJECTS ACCESS  **********************************/ 
    //Show the slices like divs in donations section
    obj.showSlices=function(){
        var htmlSlices='';
        obj.causeMix.empty();
        if(obj.slices.length>0){
            for(var i in obj.slices){
                htmlSlices+=obj.htmlSlice(obj.slices[i]);
            }
            obj.causeMix.append(htmlSlices);
            
            obj.eventSlices();
        }
    }
    //Count length tags
    obj.reWriteTags=function(name){
        var ntags=name.length;
        var texttag=name;
        var titletag="";
        var rtag={
            texttag: texttag,
            titletag:titletag
        };
        if(ntags>1){
            texttag=name.substring(0, 18)+"...";
            titletag='title="'+name+'"';
            rtag={
                texttag: texttag,
                titletag:titletag
            }
        }
        return rtag;    
    }
    
    
    //Define the events of the amount DOM Element
    obj.amountEvents=function(){
        var incr=obj.amountIncrement;
        var contAmount=obj.amountDom;
        var more=contAmount.find(".more");
        var less=contAmount.find(".less");
        var amount=contAmount.find("input");
        more.click(function(e){
            e.preventDefault();
            obj.changeAmount(obj.getAmount()+incr);
        });
        less.click(function(e){
            e.preventDefault();
            obj.changeAmount(obj.getAmount()-incr);
        });
        amount.change(function(){
            var money=parseInt(amount.val().replace("$",""));
            if(system.security.isInt(money)&&money>=obj.amountMin){
                obj.changeAmount(money);
            }else{
                obj.changeAmount(obj.amountDefault);
            }
        }).click(function(e){
            amount.val(" ");
        }).focusout(function(){
            obj.changeAmount(obj.getAmount());
        });
    };
    obj.getAmount=function(){
        var contAmount=obj.amountDom;
        var amount=contAmount.find("input");
        return parseInt(amount.attr("amount"));
    };
    obj.changeAmount=function(newAmount){
        var contAmount=obj.amountDom;
        var amount=contAmount.find("input");
        if(newAmount>=obj.amountMin){
            amount.attr("amount",newAmount);
            amount.val("$"+newAmount);
            obj.calculatePoints();
        }
    };
/**********************************  MATHS AND UTILS  **********************************/
    /*Round the number to dec decimals*/
    function round(num,dec) {
        var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
        return result;
    }
    //Get the opposite angle
    obj.opposite=function(angle){
        var opposite=0;
        if(angle==0||angle==2*Math.PI){
            opposite=Math.PI;
        }else if(angle<Math.PI){
            opposite=angle+Math.PI;
        }else if(angle>Math.PI){
            opposite=angle-Math.PI;
        }
        return opposite;
    }
    //Debug slices
    obj.debugSlices=function(){
        var string='';
        var sum=0;
        for(var i in obj.slices){
            string+=
                'Slice['+i+']:: '+
                obj.slices[i].name+
                ' ('+obj.slices[i].idArea+' > '+obj.slices[i].idTag+') ->'+
                ' ['+obj.slices[i].percent+'%]\n';
            sum+=obj.slices[i].percent;
        }
        string+="Sum: "+sum;
        return string;
    }
    
/**********************************  HTML METHODS  **********************************/
    //Return the html of the circle
    obj.htmlCircle=function(){
        var html=
            '<canvas class="fpcanvas" id="canvas'+obj.id+'"></canvas>'+
            //¡¡IMPORTANT!!: This objects are used only for load the 
            //styles and use them in the canvas elements
            '<div class="circleOut"></div>'+      
            '<div class="circleOver"></div>';
        return html;
    }
    //Html for a slice to show in the causesMix
    obj.htmlSlice=function(slice){
        var objtag=obj.reWriteTags(slice.name);
        var classSlice='area'+slice.idArea;
        var html='<div id="slice'+slice.index+'" class="slice '+classSlice+'">'+
            '<div id="delete" class="section delete"></div>'+
            '<div id="subcategory" class="section" '+objtag.titletag+' >'+objtag.texttag+'</div>'+
            '<input type="text" placeholder="'+slice.percent+'" percent="'+slice.percent+'" value="'+slice.percent+'"><label>%</label>'+
        '</div>';
        return html;
    }
}
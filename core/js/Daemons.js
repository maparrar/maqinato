/**
 * Daemon Pseudoclass
 **/
function Daemon(name,callback,cycles){
    "use strict";
    var obj=this;
    obj.name=name;          //Unique identificator to the daemon
    obj.callback=callback;  //Function where the server data will be returned
    obj.params=new Array(); //Pairs [data,value] to filter the returned data
    if(!cycles){cycles=2;};
    obj.cycles=cycles;      //How many cycles must execute the daemon.
}
/**
 * Pseudoclass to manage the daemons that request data from the server
 * @param {int} interval intervalo para ejecutar los demonios en milisegundos
 **/
function Daemons(interval){
    "use strict";
    var obj=this;
    obj.counter=0;                  //Number of times that the daemons are executed
    obj.timer=null;                 //Timer to execute the daemons
    obj.daemons=new Array();        //List of daemons to check in server
    
    obj.daemonsInterval=interval;   //The interval for the daemons in milliseconds
    obj.ajaxFunction=null;          //Function that take the info from the server
    /**
     * Start the request to the server with the registered daemons
     **/
    obj.exec=function(){
        obj.process();
        obj.timer=setTimeout(obj.exec,obj.daemonsInterval);
    };
    /**
     *Process daemons, force the execution of the daemons out of time
     **/
    obj.process=function(){
        if(obj.daemons.length>0){
            //Pass the list of actives daemons
            var actives=new Array();
            for(var i in obj.daemons){
                var module=obj.counter%obj.daemons[i].cycles;
                if(module==0){
                    actives.push(obj.daemons[i]);
                }
            }
            if(actives.length>0){
                obj.ajaxFunction(actives);
            }
            obj.counter++;
        }
    };
    /**
     * Add a Daemon to the daemons list
     * @param {Daemon} daemon Objeto de tipo Daemon que se quiere agregar para ejecutar
     **/
    obj.add=function(daemon){
        //If not exist
        var exist=false;
        if(obj.daemons.length>0){
            for(var i in obj.daemons){
                if(obj.daemons[i].name===daemon.name){
                    exist=true;
                    break;
                }
            }
        }
        if(!exist){
            obj.daemons.push(daemon);
            if(obj.daemons.length===1){
                obj.exec();
            }
        }
    };
    /**
     * Elimina un demonio de la lista de demonios ejecutables
     * @param {string} name Nombre del demonio que se quiere eliminar
     * */
    obj.remove=function(name){
        for(var i in obj.daemons){
            if(obj.daemons[i].name==name){
                obj.daemons.splice(i,1);
                break;
            }
        }
        if(obj.daemons.length==0){
            clearTimeout(obj.timer);
        }
    };
    /**
     * Response from the server in JSON, distributed the data to each daemon's callback
     * @param {Object} response Aplica los datos a cada callback de cada demonio
     **/
    obj.response=function(response){
        var daemons=response.daemons;
        for(var i in daemons){
            var daemon=obj.getDaemon(daemons[i].name);
            if(daemon){
                daemon.callback(daemons[i].data);
            }
        }
    };
    /**
     * Return a daemon from the daemons list
     * @param {string} name Nombre del demonio que se quiere obtener
     **/
    obj.getDaemon=function(name){
        var daemon=null;
        if(obj.daemons.length>0){
            for(var i in obj.daemons){
                if(obj.daemons[i].name==name){
                    daemon=obj.daemons[i];
                    break;
                }
            }
        }
        return daemon;
    };
    /**
     * Insert a list of parameters in the especified daemon
     * parameters must be an array
     * @param {string} name Nombre del demonio
     * @param {array} parameters Array de parámetros que se aplicarán al demonio
     **/
    obj.parameterizeDaemon=function(name,parameters){
        if(obj.daemons.length>0){
            for(var i in obj.daemons){
                if(obj.daemons[i].name==name){
                    obj.daemons[i].params=parameters;
                    break;
                }
            }
        }
    };
};
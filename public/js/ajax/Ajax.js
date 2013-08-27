function Ajax(){
    var obj=this;
    obj.path=maqinato.path('ajax');
    obj.root=maqinato.path('root');
    /**
     * Registro de usuarios nuevos
     * @param {string} email Correo del nuevo usuario
     * @param {string} password Contraseña del nuevo usuario
     * @param {string} name Nombre del nuevo usuario
     * @param {string} lastname Apellido del nuevo usuario
     * @param {function} callback Función a la que se retorna lo recibido del servidor
     * */
    obj.signup=function(email,password,name,lastname,callback){
        $.ajax({
            type:"POST",
            async:false,
            url: obj.path+"accessing/jxSignup.php",
            data: {
                email:email,
                password:password,
                name:name,
                lastname:lastname
            }
            }).done(function(response){
                callback(Security.secureString(response));
            }
        );
    };
    /**
     * Ingreso de usuarios
     * @param {string} email Correo del nuevo usuario
     * @param {string} password Contraseña del nuevo usuario
     * @param {bool} keep Si se mantiene la sesión indefinidamente o no
     * @param {function} callback Función a la que se retorna lo recibido del servidor
     * */
    obj.login=function(email,password,keep,callback){
        $.post(
            obj.path+"accessing/jxLogin.php",{
                email:email,
                password:password,
                keep:keep
            },
            function(response){
                callback(Security.secureString(response));
            }
        );
    };
    /**
     * Salida del sistema, borra la sesión de usuario
     * @param {function} callback Función a la que se retorna lo recibido del servidor
     * */
    obj.logout=function(callback){
        $.post(
            obj.path+"accessing/jxLogout.php",
            function(){
                if(callback){callback();}
            }
        );
    };
    /**
     * Function that refresh the session
     **/
    obj.refreshSession=function(){
        $.post(obj.path+"core/jxRefreshSession.php");
    };
    /**
     * Function that load the system data.
     * Send the daemons to return the server data for each daemon
     * @param {Daemon·[]} daemons Lista de objetos de tipo Daemon
     **/
    obj.daemons=function(daemons){
        $.ajax({
            type: "POST",
            url: obj.path+"core/jxDaemons.php",
            data: {daemons:JSON.stringify(daemons)},
            timeout: 10000,
            success: function(response){
                var data=JSON.parse(response);
                if(data.type==="Error"){
                    system.error(data);
                }else{
                    system.daemons.response(data);
                }
            },
            error: function(){
                var error;
                error.name="daemonError";
                system.error(error);
            }
        });
    };
}

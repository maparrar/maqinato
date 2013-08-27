function Ajax(){
    var obj=this;
    obj.path=maqinato.path('ajax');
    obj.root=maqinato.path('root');
    /*METHODS*/
    //TODO: pass the error to the system object
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
    //TODO: pass the error to the system object
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
    //TODO: pass the error to the system object
    obj.logout=function(){
        $.post(
            obj.path+"core/jxLogout.php",
            function(){
                window.location=obj.root;
            }
        );
    };
    /*Function that refresh the session
     **/
    obj.refreshSession=function(){
        $.post(obj.path+"core/jxRefreshSession.php");
    };
    /*Function that load the system data.
     *Send the daemons to return the server data for each daemon
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

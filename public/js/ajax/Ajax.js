function Ajax(){
    var obj=this;
    obj.path=maqinato.rel('ajax');
    obj.root=maqinato.rel('root');
    
    /*METHODS*/
    //TODO: pass the error to the system object
    obj.signup=function(email,password,name,lastname){
        $.ajax({
            type:"POST",
            async:false,
            url: obj.path+"core/jxSignup.php",
            data: {
                email:email,
                password:password,
                name:name,
                lastname:lastname
            }
            }).done(function(response) {
                if(response==="logged"){
                    maqinato.debug("REGISTRADO Y LOGUEADO");
//                    window.location=obj.root+"views/home/index.php?user=new";
                }else if(response==="error"){
                    callback(false);
                }else if(response==="exist"){
                    callback("exist");
                }
            }
        );
    };
    obj.signupReserve=function(name,lastname,sex,email,city,password,callback){
        $.ajax({
            type:"POST",
            async:false,
            url: obj.path+"core/jxSignupReserve.php",
            data: {
                name:name,
                lastname:lastname,
                sex:sex,
                email:email,
                city:city,
                password:password
            }
            }).done(function(response) {
                if(callback){
                    if(response==="logged"){
                        callback("registered");
                    }else if(response==="error"){
                        callback(false);
                    }else if(response==="exist"){
                        callback("exist");
                    }
                }
            }
        );
    };
    //TODO: pass the error to the system object
    obj.login=function(email,password,keep,callback,provider,token1,token2){
        $.post(
            obj.path+"core/jxLogin.php",{
                email:email,
                password:password,
                keep:keep,
                provider:provider,
                token1:token1,
                token2:token2
            },
            function(response){
                if(response==="logged"){
                    window.location=obj.root+"views/home/";
                }else if(response==="error"){
                    callback(response);
                }
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

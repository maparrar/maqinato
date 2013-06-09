function AjaxCore(){
    var obj=this;
    obj.path=system.rel('ajax');
    obj.root=system.rel('root');
    
/*METHODS*/
    //Required init function
    obj.init=function(){}
    //TODO: pass the error to the system object
    obj.signup=function(name,lastname,sex,email,idCity,city,country,password,callback){
        $.ajax({
            type:"POST",
            async:false,
            url: obj.path+"core/jxSignup.php",
            data: {
                name:name,
                lastname:lastname,
                sex:sex,
                email:email,
                idCity:idCity,
                city:city,
                country:country,
                password:password
            }
            }).done(function(response) {
                if(response==="logged"){
                    window.location=obj.root+"views/home/index.php?user=new";
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
    obj.signupNop=function(name,responsible,email,password,callback){
        $.post(
            obj.path+"core/jxSignupNop.php",{
                name:name,
                responsible:responsible,
                email:email,
                password:password
            },
            function(response){
                if(response=="logged"){
                    window.location=obj.root+"views/nonprofit/";
                }else if(response==="error"){
                    callback(false);
                }else if(response==="exist"){
                    callback("exist");
                }
            }
        );
    };
    //TODO: pass the error to the system object
    obj.login=function(email,password,keep,callback){
        $.post(
            obj.path+"core/jxLogin.php",{
                email:email,
                password:password,
                keep:keep
            },
            function(response){
                if(response=="logged"){
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
    obj.search=function(keyword,callback){
        $.post(
            obj.path+"core/jxSearch.php",{
                keyword:keyword
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    if(callback){
                        callback(data);
                    }
                }
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
                if(data.type=="Error"){
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
    /**
     *Change the basic user data
     **/
    obj.editUser=function(userData,callback){
        system.debug(userData);
        $.post(
                
            obj.path+"core/jxUserEdit.php",userData,
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    /**
    /**
     *read de city of country
     **/
    obj.readCity=function(country,city,callback){
        $.post(
            obj.path+"core/jxReadCity.php",{
                country:country,
                city:city
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    /**
    /**
     *Change the user password
     **/
    obj.changePassword=function(lastPwr,newPwr,email,callback){
        $.post(
            obj.path+"core/jxChangePassword.php",{
                lastPwr:lastPwr,
                newPwr:newPwr,
                email:email
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    /**
     * Send a comment from the contact-us form
     **/
    obj.contactUs=function(email,message,callback){
        $.post(
            obj.path+"core/jxContactUs.php",{
                email:email,
                message:message
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    if(callback){
                        callback(data);
                    }
                }
            }
        );
    };
    /**
     * Corta una imagen a partir de una que esté en el direcotorio temp
     **/
    obj.cropImage=function(srcName,srcX,srcY,srcH,srcW,dstH,dstW,previous,callback){
        $.post(
            obj.path+"core/jxCropImage.php",{
                srcName:srcName,
                srcX:srcX,
                srcY:srcY,
                srcH:srcH,
                srcW:srcW,
                dstH:dstH,
                dstW:dstW,
                previous:previous
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    //Publica un archivo en el bucket de uso público de AWS
    /**
     * Send a comment from the contact-us form
     **/
    obj.publishFile=function(file, callback){
        $.post(
            obj.path+"core/jxPublishFile.php",{
                file:file
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    };
    /**
     * Envia el email de confirmación a la cuenta de correo sel usuario 
     * registrado en la sesión
     * @param {function} callback para retornar la respuesta
     * */
    obj.sendValidationEmail=function(callback){
        $.post(
            obj.path+"core/jxSendValidationEmail.php",
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    }
    /**
     * Envia el email de confirmación a la cuenta de correo sel usuario 
     * registrado en la sesión
     * @param {function} callback para retornar la respuesta
     * */
    obj.send24hValidationEmail=function(callback){
        $.post(
            obj.path+"core/jxSend24hValidationEmail.php",
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data);
                }
            }
        );
    }
}

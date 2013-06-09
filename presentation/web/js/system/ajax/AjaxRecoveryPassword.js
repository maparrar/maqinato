function AjaxRecoveryPassword(){
    var obj=this;
    obj.path=system.rel('ajax');
    obj.root=system.rel('root');
    //Required init function
    obj.init=function(){}
       
    /****************************** COMENTARIOS *******************************/
    /**
     * grabar datos registro Nonprofits
     **/
    obj.sendMail=function(datos,callback){
       $.post(
            obj.path+"core/jxRecoveryPassword.php",datos,
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
}
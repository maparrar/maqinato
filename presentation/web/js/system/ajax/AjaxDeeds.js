function AjaxDeeds(){
    var obj=this;
    obj.path=system.rel('ajax');
    obj.root=system.rel('root');
    
    /**
     * Save a Good deed
     **/
    obj.saveDeed=function(tags,string,media,type,folio,callback){
       $.post(
            obj.path+"deeds/jxDeedSave.php",{
                tags:tags,
                string:string,
                media:media,
                type:type,
                folio:folio
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
    }
}
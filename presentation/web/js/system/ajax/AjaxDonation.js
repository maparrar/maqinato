function AjaxDonation(){
    var obj=this;
    obj.path=system.rel('ajax');
    obj.root=system.rel('root');
    //Required init function
    obj.init=function(){}
    /**
     *Get the data for the checkout
     * INPUTS:
     *      amount:
     *          - (int) Amount of the donation
     *      tags[]: 
     *          - Set of Tags of the combination
     *      reprntId:
     *          - (int) 0 if is not reprnt, other if is a reprnt
     *      callback:
     *          - (function) function to return the response
     **/
    obj.checkout=function(amount,tags,reprntId,callback){
        $.post(
            obj.path+"donation/jxCheckout.php",{
                amount:amount,
                tags:JSON.stringify(tags),
                reprntId:reprntId
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
    /**
     *Refresh the organizations in the checkout
     * INPUTS:
     *      tags[]: 
     *          - Set of Tags of the combination
     *      callback:
     *          - (function) function to return the response
     **/
    obj.refreshOrganizations=function(amount,tags,reprntId,callback){
        $.post(
            obj.path+"donation/jxCheckout.php",{
                amount:amount,
                tags:JSON.stringify(tags),
                reprntId:reprntId
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
    /**
     *Make a donation.
     *  if reprnt=0:
     *      - create a new combination
     *      - create the combination image
     *      - make a distribution (set of donations) with the new combination
     *      - make a donation for each slice
     *  if reprnt!=0
     *      - load the previous reprnt set of tags
     *      - load the combination image
     *      - make a distribution (set of donations) with the old combination
     *      - make a donation for each slice
     * INPUTS:
     *      callback:
     *          - (function) function to return the response
     **/
    obj.makeGiving=function(data,callback){
        $.post(
            obj.path+"donation/jxMakeGiving.php",{
                data:data
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
     * Salva una combinación (solo si se está editando, para un folio)
     **/
    obj.saveCombination=function(data,callback){
        $.post(
            obj.path+"donation/jxSaveCombination.php",{
                data:data
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
    /**
     * Return the footpoints for a determinated configuration
     * amount: float with the amount of the donation
     * tags: array of integers with the tags' ids
     **/
    obj.points=function(amount,tags,reprntId,callback){
        $.post(
            obj.path+"donation/jxPoints.php",{
                amount:amount,
                tags:JSON.stringify(tags),
                reprntId:reprntId
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data.points);
                }
            }
        );
    }
    /**
     * Return a list of Tags related with the keyword
     *  keyword: part of a word, word, set of words
     **/
    obj.searchTags=function(keyword,callback){
        $.post(
            obj.path+"donation/jxSearchTags.php",{
                keyword:keyword
            },
            function(response){
                //decoded data
                var data=JSON.parse(response);
                if(data.type=="Error"){
                    system.error(data);
                }else{
                    callback(data.tags);
                }
            }
        );
    }
    /**
     * Crea la factura y la envía por correo, la crea en pdf o la imprime según
     * sel caso.
     * INPUTS:
     *      email: true si se debe mandar un email
     *      pdf: true si se debe generar un pdf y mandarlo al cliente
     *      print: true si debe mandar a la impresora
     *      amount:
     *          - (int) Amount of the donation
     *      tags[]: 
     *          - Set of Tags of the combination
     *      callback:
     *          - (function) function to return the response
     **/
    obj.invoice=function(data,callback){
        $.post(
            obj.path+"donation/jxInvoice.php",{
                data:data
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
     * Hace el pago por medio de ajax, usado en stripe
     * @param {string} token Token de pago en stripe
     * @param {string} agent Agente, usado principalmente con "stripe"
     * @param {string} method Puede ser "visa","master","amex","discover","paypal"
     * @param {float} amount Monto a pagar
     * @param {object} data datos del checkout pasados como objeto
     * @param {function} callback función para retornar los datos
     * */
    obj.payStripe=function(token,agent,method,amount,data,callback){
        $.post(
            obj.path+"donation/jxPay.php",{
                token:token,
                agent:agent,
                method:method,
                amount:amount,
                data:JSON.stringify(data)
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
     * Hace todo el proceso sin pago, por ahora solo por el usuario maqinato
     * @param {float} amount Monto a pagar
     * @param {object} data datos del checkout pasados como objeto
     * @param {function} callback función para retornar los datos
     * */
    obj.withoutPay=function(amount,data,callback){
        $.post(
            obj.path+"donation/jxWithoutPay.php",{
                amount:amount,
                data:JSON.stringify(data)
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
}
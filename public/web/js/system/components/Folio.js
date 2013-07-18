/**
 * Pseudoclass to manage the Folio page
 **/
function Folio(){
    "use strict";
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.folio=0;
    obj.combination=0;
    //Amount
    obj.amount=0;               //Almacena el monto cuando un seguidor quiere hacer un giving en el folio actual
    obj.amountDom=false;        //DOM Element of the amount
    obj.amountMin=false;        //Min value of the amount
    obj.amountDefault=false;    //Default amount value
    obj.amountIncrement=false;  //Increment amount
    //Diálogo del checkout
    obj.checkoutDialog=null;
    
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init the systems Scripts 
     **/
    obj.init=function(opts){
        //Ajusta las áreas de la combinación
        obj.adjustAreas();
        
        //Establece las opciones ingresadas
        obj.amountDom=null;                 //DOM Element of the amount, se establece cuando se crea el modal del checkout
        obj.amountMin=opts.amountMin;       //Min value of the amount
        obj.amountDefault=opts.amountDefault;//Default amount value
        obj.amountIncrement=opts.amountIncrement;//Increment amount
        obj.lastPayment=opts.lastPayment;
        
        //Establece el Id del folio actual y la combinación
        obj.folio=opts.folio;
        obj.combination=opts.combination;
        //Instancia e inicia el newsfeed
        obj.newsfeed=new Newsfeed();
        obj.newsfeed.init({
            newsfeedElement:$('#newsfeeds'),
            filterBar:$(".content-bottom").find(".filter"),
            loadInit:system.config.activities.loadInit,
            loadScroll:system.config.activities.loadScroll,
            folio:obj.folio
        });
        
        //Crea una instancia de la búsqueda de amigos
        obj.search=new Search();
        obj.search.init({
            input:$("#top-bar").find("#inputSearch"),
            button:$("#top-bar").find("#buttonSearch"),
            content:$("#top-bar").find("#search").find("#content"),
            type:"all"
        });
        
        //Crea una instancia del modal de Good Deeds
        obj.deeds=new Deeds();
        
        //Crea la instancia del uploader de la imagen del folio
        obj.upFolio=new Uploader();
        obj.upFolio.init({
            trigger:$(".img-btn-edit"),
            frame:$(".folio-avatar"),
            outputImageH:182,
            outputImageW:243,
            callback:obj.uploadedImage
        });
        
        //Crea el objeto para subir las historias
        obj.stories=new Stories();
        obj.stories.init({
            send:$(".btn-upload-story"),
            textarea:$(".upload-stories").find("textarea"),
            folio:obj.folio
        });
        
        //Si recibe información válida del último pago, muestra el congratulations
        if(obj.lastPayment.success!=="null"){
            if(obj.lastPayment.success==="true"){
                obj.showCongratulations();
            }else{
                system.message("The payment was rejected by Paypal. Please try again.");
            }
        }
        
        //Genera los eventos para los objetos del home
        obj.events();
        
        //Crea el scroller de los seguidores del folio
        $('.scroller').tinyscrollbar();
        
        //Pone imágenes default a las imágenes que no pudieron ser cargadas
        $(".user").on('error',system.defaultImage);
    };
    
    /**
     * Set the login, logout and signup events
     **/
    obj.events=function(){
        var buttons=$(".call-accion-cont");
        var giving=buttons.find(".btn_give");
        var deed=buttons.find(".btn-good-deeds");
        var editGiving=$(".folio_mix_results").find("#editGiving");
        var matters=$(".content-top").find(".why-matter");
        var tagsArea=$(".content-top").find(".tagsArea");
        var follow=$(".content-top").find(".btn-folio-follow");
        
        editGiving.click(function(e){
            e.stopPropagation();
            e.preventDefault();
            system.gotoCombination(false,obj.folio);
        });
        giving.click(function(e){
            e.stopPropagation();
            e.preventDefault();
            obj.fillCheckOut();
        });
        deed.click(function(e){
            e.preventDefault();
            //Crea el modal de Good deeds
            var html=$("#modals").find("#modalDeeds").html();
            var dialogDeeds=system.dialog({
                html:html,
                height:"auto",
                width:750
            });
            obj.deeds.init(dialogDeeds,obj.folio);
            //Inicia el cargador de imágenes de Good Deeds
            obj.upDeeds=new Uploader();
            obj.upDeeds.init({
                trigger:obj.deeds.modal.find(".btn-photo"),
                frame:obj.deeds.modal.find("#deedMedia"),
                outputImageH:215,
                outputImageW:285
            });
        });
        matters.find("#saveMatters").click(function(e){
            e.stopPropagation();
            e.preventDefault();
            var linksave=matters.find("#saveMatters").html();
            var text=system.security.secureString(matters.find("textarea").val());
            system.ajaxSocial.saveMatters(obj.folio,text,function(data){
                system.debug("response: "+data);
            });
            if(linksave=="Save"){
                matters.find("#saveMatters").html("Edit");
                matters.find("textarea#content").attr("disabled","disable");
            }else{
                matters.find("#saveMatters").html("Save");
                matters.find("textarea#content").removeAttr("disabled").focus();
            }
        });
        follow.click(function(e){
            e.stopPropagation();
            e.preventDefault();
            system.ajaxSocial.toggleFollowFolio(obj.folio,function(data){
                if(data.followingFolio==="follow"){
                    follow.text("Unfollow");
                }else{
                    follow.text("Follow");
                }
            });
        });
        
        //Bonslider en tags y usuarios
        tagsArea.bonslider();
    };
    
    //Posiciona las áreas de la combinación
    obj.adjustAreas=function(){
        var areas=$(".folio_mix").find(".area");
        switch(areas.length){
            case 1:areas.first().css("margin-top",80);break;
            case 2:areas.first().css("margin-top",60);break;
            case 3:areas.first().css("margin-top",42);break;
            case 4:areas.first().css("margin-top",28);break;
            case 5:areas.first().css("margin-top",8);break;
            case 6:areas.each(function(){$(this).css("margin-top",5);});break;
        }
    };
    
    obj.fillCheckOut=function(){
        //Crea el modal del checkout
        var html=$("#modals").find("#modalCheckout").html();
        obj.checkoutDialog=system.dialog({
            html:html,
            height:670,
            width:720
        });
        obj.amount=obj.amountDefault;
        var causeMix=obj.checkoutDialog.find(".your-cause-mix").find("#tableMix");
        var points=obj.checkoutDialog.find(".points-achived");
        var nonprofits=$(".nonprof-list");
        var slices=obj.combination.slices;
        var checkoutData=obj.checkoutDialog.find("#checkoutData");
        //Actualiza los valores necesarios de los slides para procesarlos en el checkout
        for(var i in slices){
            slices[i].idTag=slices[i].tag;
            slices[i].percentNorm=slices[i].percent/100;
        }
        system.ajaxDonation.checkout(obj.amount,slices,0,function(data){
            nonprofits.empty();
            var tags=data.tags;
            var totalTags=0;
            //Add the ammount
            obj.checkoutDialog.find("#total_amount").text(Tools.currency(obj.amount,2));
            //Add the causes mix
            causeMix.find(".tagMix").remove();
            for(var i in tags){
                causeMix.append(obj.htmlTagMix(tags[i],obj.amount));
                totalTags+=parseInt(tags[i].footpoints);
                //Add the organizations
                nonprofits.append(obj.htmlNonprofitMix(tags[i].organization));
            }
            //Add the points
            var basic=data.points.giving;
            var extra=data.points.set+data.points.reprnt;
            points.find("#fpBasic").text(basic);
            points.find("#fpExtra").text("+ "+extra);
            data.amount=obj.amount;
            checkoutData.val(JSON.stringify(data));
            
            //Eventos
            obj.fillCheckOutEvents();
        });
    };
    /* Eventos del checkout después de ser llenado*/
    obj.fillCheckOutEvents=function(){
        var confirm=obj.checkoutDialog.find("#confirmGiving");
        obj.checkoutDialog.find("#scrollOrgs").tinyscrollbar({sizethumb: 40});
        obj.checkoutDialog.find('#scrollMix').tinyscrollbar({sizethumb: 40});
        var hideAmount=obj.checkoutDialog.find('#hide-amount');
        
        //Se previene la propagación
        hideAmount.click(function(e){
            e.stopPropagation();
        });

        //Cuando se hace cliek en refresh
        obj.checkoutDialog.find("#refresh-alert").click(function(){
            obj.refreshOrganizations();
        });
        //Hacer la donación
        confirm.click(function(e){
            e.preventDefault();
            obj.makeGiving();
        });
        //Eventos del amount
        obj.amountEvents();
    };
    //Refresh the organizations of the checkout
    obj.refreshOrganizations=function(){
        var amount=parseInt($("#srcAmount").attr("amount"));
        var nonprofits=obj.checkoutDialog.find(".nonprof-list");
        var slices=obj.combination.slices;
        var checkoutData=obj.checkoutDialog.find("#checkoutData");
        system.ajaxDonation.refreshOrganizations(amount,slices,0,function(data){
            nonprofits.empty();
            var tags=data.tags;
            for(var i in tags){
                //Add the organizations
                nonprofits.append(obj.htmlNonprofitMix(tags[i].organization));
            }
            obj.checkoutDialog.find("#scrollOrgs").tinyscrollbar_update();
            data.amount=amount;
            checkoutData.val(JSON.stringify(data));
        });
    }
    //Define the events of the amount DOM Element
    obj.amountEvents=function(){
        var incr=obj.amountIncrement;
        obj.amountDom=obj.checkoutDialog.find(".amount");
        var more=obj.amountDom.find(".more");
        var less=obj.amountDom.find(".less");
        var amount=obj.amountDom.find("input");
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
            obj.amount=newAmount;
            obj.recalculatePoints();
        }
    };
    //Calculate the points in the server using AJAX
    obj.recalculatePoints=function(){
        var checkoutData=obj.checkoutDialog.find("#checkoutData");
        var slices=obj.combination.slices;
        system.ajaxDonation.points(obj.getAmount(),slices,0,function(points){
            obj.checkoutDialog.find();
            //Add the points
            if(points.total){
                var basic=points.giving;
                var extra=points.set+points.reprnt;
                obj.checkoutDialog.find("#fpBasic").text(basic);
                obj.checkoutDialog.find("#fpExtra").text("+ "+extra);
                //Actualiza la cadena de datos que se envía al backend
                var data=JSON.parse(checkoutData.val());
                data.points.giving=points.giving;
                data.points.set=points.set;
                data.points.reprnt=points.reprnt;
                data.points.total=points.total;
                data.amount=obj.amount;
                checkoutData.val(JSON.stringify(data));
            }
        });
    };
    //Hace la donación
    obj.makeGiving=function(){
        var checkoutData=obj.checkoutDialog.find("#checkoutData");
        var shareAmount=obj.checkoutDialog.find("#hide-amount");
        var shareFacebook=obj.checkoutDialog.find("#share-facebook");
        var shareTwitter=obj.checkoutDialog.find("#share-twitter");
        var useBalance=obj.checkoutDialog.find("#use-balance");
        var data=JSON.parse(checkoutData.val());
        //Indica al backend que debe asociar este giving al folio
        data.addToFolio=obj.folio;
        data.createFolio=false;
        data.shareAmount=shareAmount.prop('checked');
        data.shareFacebook=shareFacebook.prop('checked');
        data.shareTwitter=shareTwitter.prop('checked');
        data.useBalance=useBalance.prop('checked');
        data.image="";
        data.combinationId=obj.combination.id;
        data.reprntId=0;
        data.folioName="";
        data.slices=obj.combination.slices;
        //Para cada slice se agrega la organización de cada tag y el área
        for(var j in data.slices){
            for(var k in data.tags){
                if(data.slices[j].idTag===data.tags[k].id){
                    data.slices[j].organization=data.tags[k].organization;
                    data.slices[j].idArea=data.tags[k].area.id;
                }
            }
        }
        obj.askCreditCard(data.amount,"stripe",data);
    };
    /**
     * Solicita los datos de la tarjeta de crédito para hacer un pago
     * */
    obj.askCreditCard=function(amount,agent,data){
        //Crea el modal del checkout.
        var html=$("#modals").find("#modalCreditCard").html();
        var dialog=system.dialog({
            html:html,
            height:550,
            width:515
        });
        var accept=dialog.find("#accept");
        accept.click(function(){
            var name=system.security.secureString(dialog.find("#name").val());
            var lastname=system.security.secureString(dialog.find("#lastname").val());
            var number=$.trim(dialog.find("#number").val());
            var month=dialog.find("#month").val();
            var year=dialog.find("#year").val();
            var code=dialog.find("#code").val();
            var options=dialog.find(".option input");
            //Dependiendo el método de pago, se envían los datos o se solicitan los datos de tarjeta de crédito
            //Verifica si hay uno chequeado
            var method=false;
            options.each(function(){
                if($(this).is(':checked')){
                    method=$(this).val();
                }
            });
            if(!method){
            system.message("Please select a payment method.");
            }else{
                //Si es paypal, pasa al pago, si es tarjeta de crédito, solicita los datos
                if(method==="paypal"){
                    obj.pay(data.amount,"paypal",method,data);
                }else{        
                    if($.trim(name).length>2&&$.trim(lastname).length>2){
                        number=Tools.replace(" ","",number);
                        number=Tools.replace("-","",number);
                        number=Tools.replace("_","",number);
                        if(system.security.isCreditCard(number)){
                            if(system.security.isCreditCardCode(code)){
                                var methodDetails={
                                    name:name,
                                    lastname:lastname,
                                    number:number,
                                    month:month,
                                    year:year,
                                    code:code
                                }
                                obj.pay(amount,agent,method,data,methodDetails,dialog);
                            }else{
                                system.message("Invalid credit card validation code",dialog.find("#code"));
                            }
                        }else{
                            system.message("Invalid credit card number",dialog.find("#number"));
                        }
                    }else{
                        system.message("The name and lastname must have at least 3 characters.",dialog.find("#name"));
                    }
                }
            }
        });
    };
    /**
     * Envía los datos de pago al servidor
     * @param {float} amount Monto a pagar
     * @param {string} agent Agente/Entity al que se va a pagar: "paypal", "bankABC"
     * @param {string} method Puede ser "visa","master","amex","discover","paypal"
     * @param {object} data datos del checkout pasados como objeto
     * @param {object} methodDetails detalles del método si son necesarios, p.e.
     *                 en tarjetas de crédito:
     *                 {
     *                      number: 123123123123,
     *                      month: 11,
     *                      year: 2013,
     *                      code: 1243
     *                 }
     **/
    obj.pay=function(amount,agent,method,data,methodDetails,dialogCard){
        system.debug("pay");
        dialogCard.find("#accept").hide();
        dialogCard.find("#loading").show();
        if(agent==="stripe"){
            Stripe.createToken({
                number: methodDetails.number,
                cvc: methodDetails.code,
                exp_month: methodDetails.month,
                exp_year: methodDetails.year
            }, function(status,response){
                if (response.error) {
                    system.dialogClose(dialogCard);
                    var html='<h2>The payment was rejected.</br>Please try again.</h2><div id="line-btn"><input id="btn-rejected" class="btn-pay-rejected" type="submit" value="Edit"></div>';
                    var payrejected=system.dialog({
                        html:html,
                        height:118,
                        width:290,
                        position:{
                            my: "center center",
                            at: "center center"
                        }
                    });
                    payrejected.find("#btn-rejected").click(function(){
                        system.dialogClose(payrejected);
                    });
                } else {
                    system.ajaxDonation.payStripe(response.id,agent,method,amount,data,function(data){
                        if(data.response==="success"){
                            window.location=data.url;
                        }else{
                            system.dialogClose(dialogCard);
                            var html='<h2>The payment was rejected.</br>Please try again.</h2><div id="line-btn"><input id="btn-rejected" class="btn-pay-rejected" type="submit" value="Edit"></div>';
                            var payrejected=system.dialog({
                                html:html,
                                height:118,
                                width:290,
                                position:{
                                    my: "center center",
                                    at: "center center"
                                }
                            });
                            payrejected.find("#btn-rejected").click(function(){
                                system.dialogClose(payrejected);
                            });
                        }
                    });
                }
            });
        }else{
//            system.gotoByPost(
//                system.rel("ajax")+"donation/pay.php",
//                {
//                    amount:amount,
//                    agent:agent,
//                    method:method,
//                    data:JSON.stringify(data),
//                    methodDetails:JSON.stringify(methodDetails)
//                }
//            );
        }
    };
    /* Muestra el modal de congratulations*/
    obj.showCongratulations=function(){
        var setId=parseFloat(obj.lastPayment.setId);
        var paymentId=parseFloat(obj.lastPayment.paymentId);
        var amount=parseFloat(obj.lastPayment.amount);
        var base=parseFloat(obj.lastPayment.base);
        var transactionCost=parseFloat(obj.lastPayment.transactionCost);
        var total=amount;
        //Crea el modal del checkout
        var html=$("#modals").find("#modalCongratulations").html();
        var dialog=system.dialog({
            html:html,
            height:280,
            width:300,
            styles:"congratulationsDialog",
            onClose:obj.onCongratulationsClose
        });
        dialog.find("#amount").text("$ "+Tools.currency(base,2));
        dialog.find("#transactionCost").text("$ "+Tools.currency(transactionCost,2));
        dialog.find("#total").text("$ "+Tools.currency(total,2));
        
        //Eventos del modal de congratulations
        dialog.find("#email").click(function(e){
            e.preventDefault();
            var data=new Object();
            data.setId=setId;
            data.paymentId=paymentId;
            data.email=true;
            data.pdf=false;
            data.print=false;
            system.ajaxDonation.invoice(data,function(){
                obj.onCongratulationsClose(dialog);
            });
        });
        dialog.find("#pdf").click(function(e){
            e.preventDefault();
            system.debug("pdf");
            obj.onCongratulationsClose(dialog);
        });
        dialog.find("#print").click(function(e){
            e.preventDefault();
            system.debug("print");
            obj.onCongratulationsClose(dialog);
        });
    };
    /* Evento para cuando se cierra el modal de congratulations*/
    obj.onCongratulationsClose=function(dialog){
        system.dialogClose(dialog);
        system.dialogClose(obj.checkoutDialog);
        obj.updateFolio();
        var activityId=parseInt(obj.lastPayment.activityId);
        var urlTwitter=system.abs('home')+activityId;
        var shareFacebook=obj.lastPayment.shareFacebook;
        var shareTwitter=obj.lastPayment.shareTwitter;
        if(shareFacebook==true){shareFacebook=1;}else{shareFacebook=0;}
        if(shareTwitter==true){shareTwitter=1;}else{shareTwitter=0;}
        var activity;
        system.ajaxSocial.loadActivity(activityId,function(htmlActivity){
            activity=$(htmlActivity);
            var image=activity.find(".circle").html();;
            var basicPoints=parseInt(activity.find(".basic_points span").text());
            var extraPoints=parseInt(activity.find(".extra_points span").text());
            var image=activity.find(".faces").find(".front").find("img").attr("src");
            var result=basicPoints+extraPoints;
            var type=activity.attr("activityType");
            var user=activity.find("h4 .user").text();
            var points=" and obtain "+result;
            if(type=="Cause Mix"){
                type="giving";
            }
            if(isNaN(result)){
                points="";
            }
            if(shareTwitter){
                window.open("https://twitter.com/intent/tweet?text="+user+" "+type+points+"&&url="+urlTwitter+"&&via=maqinato",'Continue_to_Application',"width=600,height=500");
            }
            if(shareFacebook){
                FB.ui({
                method:'feed',
                name:'maqinato',
                link:system.abs('home')+'index.php?activity='+activityId,
                picture:image,
                caption:user+" "+type+points,
                description:'Connect with maqinato'
                });
            }
        });
    };
    /**Actualiza la información del folio usando ajax*/
    obj.updateFolio=function(){
        system.ajaxSocial.updateFolio(obj.folio,function(data){
            var info=$(".folio_mix");
            info.find("#folioAmount").text("$ "+data.amount);
            info.find("#folioPoints").text(data.points);
            info.find("#folioToDistribute").text(data.toDistribute);
            info.find("#folioNextDate").text(data.nextDate);
        });
    }
    //Return an Tag for the checkout's cause mix in HTML
    obj.htmlTagMix=function(tag,amount){
        var html='<tr class="tagMix">'+
            '<td class="column column1 cell1 category">'+tag.name+'</td>'+
            '<td class="column column2 category-percentage">'+Tools.round(tag.percent*100,3)+'%</td>'+
            '<td class="column column3 total-amount">$ '+Tools.round(tag.percent*amount,2)+'</td>'+
            '<td class="column column4 points">'+tag.points+'</td>'+
        '</tr>';
        return html;
    };
    //Return an Nonprofit for the checkout's cause mix in HTML
    obj.htmlNonprofitMix=function(nonprofit){
        var html='<li class="nonprofit">'+
            '<img src="'+nonprofit.logo+'">'+
            '<span>'+
                '<h3 class="name">'+nonprofit.name+'</h3>'+
                '<a class="website" href="'+nonprofit.website+'" target="_blank">'+nonprofit.website+'</a>'+
                '<p class="description">'+nonprofit.description+'</p>'+
            '</span>'+
        '</li>';
        return html;
    };
    //Luego de subir la imagen del uploader
    obj.uploadedImage=function(data){
        system.ajaxSocial.saveFolioImage(obj.folio,data.name,function(response){
            $("#frameImageFolio").attr("src",response.path);
            //Pone imágenes default a las imágenes que no pudieron ser cargadas
            $("#frameImageFolio").on('error',system.defaultImage);
        });
    };
}
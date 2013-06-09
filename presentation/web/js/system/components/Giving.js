/**
 * Pseudoclass to manage the giving page
 **/
function Giving(){
    "use strict";
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ATTRIBUTES <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    var obj=this;
    obj.reprnt=false;   //Si es un reprnt, contiene el objeto reprnt
    obj.folioIdInGiving=false;  //Si es un giving que se hace a partir de un folio
    obj.editingFolio=false;
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> METHODS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<   
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    /**
     * Configure and init 
     **/
    obj.init=function(optsUser){
        var combinationDiv=$(".main-content").find("#combination");
        obj.reprnt=optsUser.regive;
        obj.folioIdInGiving=optsUser.folioIdInGiving;
        obj.lastPayment=optsUser.lastPayment;
        system.initFacebook();
        if(obj.folioIdInGiving){
            obj.editingFolio=true;
        }
        combinationDiv.combination({
            reprnt:obj.reprnt,
            causeMix:$("#cause-mix").find("#list"),
            amountDom:$(".amount"),
            amountMin:optsUser.amountMin,
            amountDefault:optsUser.amountDefault,
            amountIncrement:optsUser.amountIncrement,
            pointsFunction:system.ajaxDonation.points,
            callback: obj.makeDonation,
            onDropCallback: obj.tagDropped,     //Si un tag es soltado en el círculo
            onRemoveCallback: obj.tagRemoved,   //Si un tag es eliminado del círculo
            editingFolio:obj.editingFolio,      //True en caso de que la combinación venga de un folio, false en otro caso
            tagToLoad:optsUser.tagToLoad        //Si existe un tag en la URL para cargar
        });
        //Crea una instancia de la búsqueda de tags
        obj.search=new Search();
        obj.search.init({
            input:$(".main-content").find("#inputSearch"),
            button:$(".main-content").find("#buttonSearch"),
            content:$(".main-content").find("#content"),
            type:"tags"
        });
        obj.events();
        
        if(obj.editingFolio){
            //Prepara la página cuando es la edición de un Folio
            obj.prepareForFolio();
        }
        
        //Si recibe información válida del último pago, muestra el congratulations
        if(obj.lastPayment.success!=="null"){
            if(obj.lastPayment.success==="true"){
                obj.showCongratulations();
            }else{
                var html='<h2>The payment was rejected by Paypal.</br>Please try again.</h2><div id="line-btn"><input id="btn-rejected" class="btn-pay-rejected" type="submit" value="Edit"></div>';
                var payrejected=system.dialog({
                    html:html,
                    height:118,
                    width:290,
                    position:{
                        my: "center center",
                        at: "center center"
                    }
                });
            }
        }
        $(".ui-dialog-content").find("#btn-rejected").click(function(){
            system.dialogClose(payrejected);
        });
        
        //Carga las sugerencias si existen
        var suggestions=$("#suggestions").find(".suggestion");
        suggestions.suggestions({
            markReadFunction:system.ajaxSocial.suggestionMarkRead
        });
    };
    
    obj.events=function(){
        var menuAreas=$("#area-menu").find(".area");
        var infoAreas=$(".about-area");
        var tagsAreas=$("#tags-menu").find(".tagsArea");
        var giveNow=$("#giveNow");
        
        
        //Evita que se pierda la combinación
//        window.onbeforeunload=function(){
//            if(system.combination.circle.slices.length>0&&!system.isLogout){
//                return "Are you sure you want to cancel the combination?";
//            }
//        }
        
        //Oculta e inserta bonslider en las listas de tags
        tagsAreas.bonslider();
        tagsAreas.hide();
        
        //Cuando se hace click en las áreas
        menuAreas.click(function(e){
            e.stopPropagation();
            //Muestra la información de cada área
            infoAreas.find(".about-info").hide();
            infoAreas.find("#name").text($(this).find("#name").text());
            infoAreas.find("."+$(this).attr("id")).show();
            //Muestra la lista de tags del área
            tagsAreas.hide();
            $("#tags-menu").children("."+$(this).attr("id")).show();
        });
        tagsAreas.click(function(e){
            e.stopPropagation();
        });
        //Evento de arrastrar tags
        $("#tags-menu").find(".tag").draggable({
            containment: $(".main-content"),
            helper: "clone"
        });
        //Evento de click para móviles
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
            $("#tags-menu").find(".tag").click(function(e){
                e.preventDefault();
                system.combination.circle.loadTagFromElement($(this));
            });
        }
        //Evento para cerrar la lista de tags cuando se da click en otro lado
        $('html').click(function(){
            tagsAreas.hide();
            infoAreas.find(".about-info").hide();
            infoAreas.find(".area0").show();
            infoAreas.find("#name").text("");
        });
        
        //Eventos del Cause Mix
        var causeMix=$("#cause-mix");
        var causeClear=causeMix.find("#clear");
        var step=32;
        var posList=0;
        $('.scroller').tinyscrollbar();
        causeClear.click(function(e){
            e.preventDefault();
            system.combination.circle.removeSlices();
            $('.scroller').tinyscrollbar_update();
        });
        //Los otros eventos de los slices están en Circle.eventSlices
        giveNow.click(function(e){
            e.preventDefault();
            if(system.combination.circle.slices.length<=0){
                system.message("Select at least one Tag","",false);
            }else if(!obj.editingFolio){
                obj.fillCheckOut();
            }else{
                obj.fillSaveCombination();
            }
        });
    };
    //Si un tag es soltado en el círculo
    obj.tagDropped=function(tagId){
        var tag=$("#tags-menu").find("#tag"+tagId);
        var area=tag.closest(".tagsArea");
        var usedTags=$("#usedTags");
        tag.appendTo(usedTags);
        area.bonslider("hideCutted");
        $('.scroller').tinyscrollbar_update();
        system.letLogout=false;
    }
    //Si un tag es quitado del círculo
    obj.tagRemoved=function(tagId){
        var tag=$("#usedTags").find("#tag"+tagId);
        var area=$("#tags-menu").find("#tags"+tag.attr("area"));
        var inserted=false;
        area.find(".tag").each(function(){
            if(tag.text().toLowerCase()<$(this).text().toLowerCase()){
                tag.insertBefore($(this));
                inserted=true;
                return false; //break
            }
        });
        if(!inserted){
            area.find(".bsSlider").append(tag);
        }
        area.bonslider("hideCutted");
        $('.scroller').tinyscrollbar_update();
        if(system.combination.circle.slices.length===0){
            system.letLogout=true;
        }
    }
    obj.fillCheckOut=function(){
        //Crea el modal del checkout
        var html=$("#modals").find("#modalCheckout").html();
        var dialog=system.dialog({
            html:html,
            height:670,
            width:720
        });
        var amount=parseInt($("#srcAmount").attr("amount"));
        var causeMix=dialog.find(".your-cause-mix").find("#tableMix");
        var points=dialog.find(".points-achived");
        var nonprofits=$(".nonprof-list");
        var circle=system.combination.circle;
        var checkoutData=dialog.find("#checkoutData");
        system.ajaxDonation.checkout(amount,circle.getSlices(),circle.reprntId,function(data){
            nonprofits.empty();
            var tags=data.tags;
            var totalTags=0;
            //Add the ammount
            dialog.find("#total_amount").text(Tools.currency(amount,2));
            //Add the causes mix
            causeMix.find(".tagMix").remove();
            for(var i in tags){
                causeMix.append(obj.htmlTagMix(tags[i],amount));
                totalTags+=parseInt(tags[i].footpoints);
                circle.setSliceValues(tags[i].id,tags[i].name,tags[i].type,tags[i].points,tags[i].percent*amount,tags[i].organization);
                //Add the organizations
                nonprofits.append(obj.htmlNonprofitMix(tags[i].organization));
            }
            //Add the points
            var basic=data.points.giving;
            var extra=data.points.set+data.points.reprnt;
            points.find("#fpBasic").text(basic);
            points.find("#fpExtra").text("+ "+extra);
            data.amount=amount;
            //Add if is reprnt, the id of the combination
            data.reprntId=circle.reprntId;
            checkoutData.val(JSON.stringify(data));
            
            //Eventos
            obj.fillCheckOutEvents(dialog);
        });
    };
    /* Eventos del checkout después de ser llenado*/
    obj.fillCheckOutEvents=function(dialog){
        var checkoutData=dialog.find("#checkoutData");
        var preCreateFolio=$("#preCreateFolio");
        var createFolio=dialog.find("#createFolio");
        var folioName=dialog.find("#createFolioName");
        var confirm=dialog.find("#confirmGiving");
        
        dialog.find("#scrollOrgs").tinyscrollbar({sizethumb: 40});
        dialog.find('#scrollMix').tinyscrollbar({sizethumb: 40});

        //Cuando se hace cliek en refresh
        dialog.find("#refresh-alert").click(function(){
            obj.refreshOrganizations(dialog);
        });
        
        //Create folio
        createFolio.prop('checked',preCreateFolio.prop('checked'));
        createFolioName();
        createFolio.change(function() {
            createFolioName();
        });
        //Función para mostrar el nombre cuando create folio está chequeado
        function createFolioName(){
            if(createFolio.prop('checked')){
                folioName.show();
            }else{
                folioName.hide();
            }
        }
        //Hacer la donación
        confirm.click(function(e){
            e.preventDefault();
            obj.makeGiving(dialog);
        });
    };
    //Refresh the organizations of the checkout
    obj.refreshOrganizations=function(dialog){
        var amount=parseInt($("#srcAmount").attr("amount"));
        var nonprofits=$(".nonprof-list");
        var circle=system.combination.circle;
        var checkoutData=dialog.find("#checkoutData");
        system.ajaxDonation.refreshOrganizations(amount,circle.getSlices(),circle.reprntId,function(data){
            nonprofits.empty();
            var tags=data.tags;
            for(var i in tags){
                //Add the organizations
                nonprofits.append(obj.htmlNonprofitMix(tags[i].organization));
            }
            dialog.find("#scrollOrgs").tinyscrollbar_update();
            data.amount=amount;
            //Add if is reprnt, the id of the conbination
            data.reprntId=circle.reprntId;
            checkoutData.val(JSON.stringify(data));
        });
    };
    //Hace la donación
    obj.makeGiving=function(dialog){
        var checkoutData=dialog.find("#checkoutData");
        var shareAmount=dialog.find("#hide-amount");
        var shareFacebook=dialog.find("#share-facebook");
        var shareTwitter=dialog.find("#share-twitter");
        var useBalance=dialog.find("#use-balance");
        var createFolio=dialog.find("#createFolio");
        var folioName=dialog.find("#createFolioName");
        var data=JSON.parse(checkoutData.val());
        data.createFolio=createFolio.prop('checked');
        data.folioName=system.security.secureString(folioName.val());
        //Valida los datos
        if(data.createFolio&&data.folioName.length<=3){
            system.message("Please enter a valid name for the folio, At least three characters.",data.folioName,false);
        }else{
            data.shareAmount=shareAmount.prop('checked');
            data.shareFacebook=shareFacebook.prop('checked');
            data.shareTwitter=shareTwitter.prop('checked');
            data.useBalance=useBalance.prop('checked');
            data.image=system.combination.circle.getImage();
            data.slices=system.combination.circle.slices;
            //Si es reprnt asigna el atributo id de cada slice de la combinación 
            //sobre la que se hace reprnt a cada slice que se va a enviar con los 
            //nuevos puntajes
            if(obj.reprnt){
                for(var k in data.slices){
                    for(var l in obj.reprnt.slices){
                        if(data.slices[k].idTag==obj.reprnt.slices[l].tag){
                            data.slices[k].id=obj.reprnt.slices[l].id;
                        }
                    }
                }
            }
            obj.askCreditCard(data.amount,"stripe",data,dialog);
        }
    };
    /**
     * Solicita los datos de la tarjeta de crédito para hacer un pago
     * */
    obj.askCreditCard=function(amount,agent,data,checkoutDialog){
        //Crea el modal del checkout
        var html=$("#modals").find("#modalCreditCard").html();
        var dialog=system.dialog({
            html:html,
            height:550,
            width:515
        });
        var accept=dialog.find("#accept");
        var withoutPay=dialog.find("#withoutPay");
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
                                };
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
        withoutPay.click(function(e){
            e.preventDefault();
            $(this).hide();
            var createFolio=checkoutDialog.find("#createFolio");
            var folioName=checkoutDialog.find("#createFolioName");
            if(createFolio.prop('checked')){
                obj.withoutPay(amount,data,dialog);
            }else{
                createFolio.prop('checked',true);
                folioName.show();
                system.dialogClose(dialog);
                system.message("Allowed only for creating folios, Type a name for the folio and try again.",folioName.focus());
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
                        system.dialogClose(dialogCard);
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
    /**
     * Crea un folio con una donación sin pago, por ahora solo permitido para el
     * usuario de maqinato
     * @param {float} amount Monto a pagar
     * @param {object} data datos del checkout pasados como objeto
     * @param {element} dialogCard diálogo de pago
     **/
    obj.withoutPay=function(amount,data,dialogCard){
        dialogCard.find("#accept").hide();
        dialogCard.find("#loading").show();
        system.ajaxDonation.withoutPay(amount,data,function(data){
            if(data.response==="success"){
                system.gotoFolio(data.folio);
            }else{
                system.message("The folio could not be created, please try again.");
                system.dialogClose(dialogCard);
            }
        });
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
                obj.onCongratulationsClose();
            });
        });
        dialog.find("#pdf").click(function(e){
            e.preventDefault();
            system.debug("pdf");
            obj.onCongratulationsClose();
        });
        dialog.find("#print").click(function(e){
            e.preventDefault();
            system.debug("print");
            obj.onCongratulationsClose();
        });
    };
        obj.share=function(activityId){
        
        };
    
    /* Evento para cuando se cierra el modal de congratulations*/
    obj.onCongratulationsClose=function(){
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
            window.location.replace(system.rel("views")+"home/"+activityId);
        });
    };
    /**
     * Si no es una combinación normal o un reprnt, muestra el modal para confirmar
     * que se salva como la combinación del folio
     * @returns {undefined}
     */
    obj.fillSaveCombination=function(){
        obj.saveCombination();
        //TODO: Borrar esto si definitivamente no se va a usar
//        //Crea el modal del checkout
//        var html=$("#modals").find("#modalSaveCombination").html();
//        var dialog=system.dialog({
//            html:html,
//            height:150,
//            width:340
//        });
//        var confirm=dialog.find("#confirmSave");
//        //Hacer la donación
//        confirm.click(function(e){
//            e.preventDefault();
//            obj.saveCombination();
//        });
    };
    //Guarda una combinación en caso de que se esté editando
    obj.saveCombination=function(){
        var data={
            folio:obj.folioIdInGiving,
            image:system.combination.circle.getImage(),
            slices:system.combination.circle.slices
        };
        if(system.combination.circle.changed){
            system.ajaxDonation.saveCombination(data,function(response){
                system.gotoFolio(obj.folioIdInGiving);
            });
        }else{
            system.gotoFolio(obj.folioIdInGiving);
        }
    };
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
    //Prepara la página cuando es la edición de un Folio
    obj.prepareForFolio=function(){
        var createFolio=$("#createFolioWrapper");
        
        createFolio.hide();
    };
}
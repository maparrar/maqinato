function Security(){
    var obj=this;
    obj.urlRegexp=new RegExp(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/);
    //Required init function
    obj.init=function(){};
    obj.isemail=function(string){
        var pattern = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/);
        return pattern.test(string);
    };
    obj.ispassword=function(string){
        var pattern=new RegExp(/^[a-zA-Z0-9@#$%._-]{6,30}$/);
        return pattern.test(string);
    };
    obj.isurl=function(string){
        var pattern=obj.urlRegexp;
        return pattern.test(string);
    };
    obj.isFloat=function(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    };
    obj.isInt=function(n) {
        return typeof n === 'number' && parseFloat(n) == parseInt(n, 10) && !isNaN(n);
    };
    obj.secureString=function(string){
        if(string){
            return string.replace(/[^\w\s.,áéíóúAÉÍÓÚÑñ@:)(!/\]\[]/gi, '');
        }else{
            return "";
        }
    };
    obj.isDate=function(string){
        var dateArr = string.split('/');
        var mes = dateArr[0];
        var dia = dateArr[1];
        var aho = dateArr[2];
        var nDate = new Date(aho, mes - 1, dia);//mes empieza de cero Enero = 0
        if(!nDate || nDate.getFullYear() == aho && nDate.getMonth() == mes -1 && nDate.getDate() == dia&&aho<2100&&aho>1900){
            return true;
        }else{
            return false;
        }
    };
    obj.isCreditCard=function(number){
        var pattern=new RegExp(/^[0-9]{9,17}$/);
        return pattern.test(number);
    }
    obj.isCreditCardCode=function(number){
        var pattern=new RegExp(/^[0-9]{2,5}$/);
        return pattern.test(number);
    }
}
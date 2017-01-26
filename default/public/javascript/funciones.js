function soloNumeros(e){
    var keynum = window.event ? window.event.keyCode : e.which;

    if((keynum == 8) || (keynum == 46))
        return true;

    return /\d/.test(String.fromCharCode(keynum));
}

function rs(){
    hb = $('body').innerHeight();
    hw = window.innerHeight;

    h = (hw - hb) / 2;

    if(hb <= hw){
        $('html').addClass('contenido_admin');
        $('body').addClass('cuerpo_admin');
        $('body').css({
            'margin-top': h.toString() + "px"
        });
        $('#cab_admin').addClass('cabecera_admin');
        $('#dv_infoinstitutoadmin').addClass('pie_admin');
    }else{
        $('html').removeClass('contenido_admin');
        $('body').removeClass('cuerpo_admin');
        $('body').removeAttr('style');
        $('#cab_admin').removeClass('cabecera_admin');
        $('#dv_infoinstitutoadmin').removeClass('pie_admin');
    }
}

function convertiranumero(cadena){
    cadena = cadena.replace(/\./g, "");
    cadena = cadena.replace(',','.');
    
    return parseFloat(cadena);
}

function validar_email(email){
    var regexpattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    
    if((email.match(regexpattern))){
        return true;
    }else{
        return false;
    }
}
$(function($){
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: 'Anterior',
        nextText: 'Siguiente',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

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

function cargaregresados(page, programa, anio){
    $.ajax({
        type: 'POST',
        url: window.public_path + "management/cargaregresados/" + page,
        data: {
            programa: programa,
            anio: anio
        },
        beforeSend: function(){
            $('body').addClass('menuopen');
            
            $.blockUI({
                message: "Cargando datos..."
            });
        },
        success: function(data){
            $('body').removeClass('menuopen');
            
            $('#dv_resultadoegresados').html('');
            $('#dv_resultadoegresados').html(data);
        },
        error: function(){
            console.log('Error');
        }
    }).always(function(){
        $.unblockUI();
    });
}
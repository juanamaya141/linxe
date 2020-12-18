

var hash = document.location.hash;
var tabSeleccion = false;
var tabHistorial = false;
var tabInfo = false;
var user_name, user_lastname, document_type, document_number, celphone, email, tope, history_url, password;
var tope = 1000000;


var cantidad_min = 500000;
var cantidad_max = tope;
var calc1 = cantidad_min + ((cantidad_max - cantidad_min) / 2);
var cuotas_col;
var current_plazo;
var tasa = 2.20;
var tasa_val;
var cuota_mensual;
var seguros = 0.30;
var cargo_seguros;
var cuota_mas_seguros;
var cargo_tecnologia = 50000;
var cuota_tecnologia;
var iva = 19;
var cargo_iva;
var total_desembolsar;
var total_pagar;
var plazos = [6,8,10];
var montos = [];
var solicitudJSON;

var m = 0;

var enviado = false;


(function ($, Drupal, drupalSettings) {
Drupal.behaviors.LinxecreditBehavior = {
  attach: function (context, settings) {


    jQuery(function cargainicial() {
        
            hash = '#seleccion';
        
        jQuery('.nav-tabs a[href="' + hash + '"]').tab('show');
        
    });
    
    if (jQuery('body').hasClass('dashboard')) {
        if(m==0)
        {
            jQuery('#firmar').click( function(){
                enviarAutorizacion(solicitudJSON) 
            });
            cantidad_min = parseInt(drupalSettings.linxecredit.librarydashboard.gl_cantidad_min);
            tope = parseInt(drupalSettings.linxecredit.librarydashboard.valormontoaprobado);
            cantidad_max = tope;

            if(cantidad_max >= 800000)
            {
                calc1 = 800000;
            }else{
                calc1 = cantidad_min + ((cantidad_max - cantidad_min) / 2);  
            }         

            console.log("calc1:",calc1);
            
            tasa = (drupalSettings.linxecredit.librarydashboard.gl_tasa)*1/1;
            seguros = drupalSettings.linxecredit.librarydashboard.gl_seguro;
            iva = parseInt(drupalSettings.linxecredit.librarydashboard.iva);
            cargo_tecnologia = parseInt(drupalSettings.linxecredit.librarydashboard.cargo_tecnologia);
            document_type = drupalSettings.linxecredit.librarydashboard.tipoid;
            document_number = drupalSettings.linxecredit.librarydashboard.numid;
            plazostxt = drupalSettings.linxecredit.librarydashboard.plazos;
            plazos = plazostxt.split(",");
            plazos = plazos.map(function (x) { 
              return parseInt(x, 10); 
            });

            montostxt = drupalSettings.linxecredit.librarydashboard.rangomontos;
            montos = montostxt.split(",");
            montos = montos.map(function (x) { 
              return parseInt(x, 10); 
            });
            
            m=m+1;
            
            seleccionarCredito();
        }
    }
    
    
  }
};
})(jQuery, Drupal, drupalSettings);

////////////////////////////////////////////////////////////////////////////////////////
    function seleccionarCredito() {

       

            
            if( tabSeleccion == false ){
                jQuery('.instruccion').hide();
                jQuery('.row-paso').hide();
                jQuery('[data-step="paso2"]').slideDown();
                jQuery('#cantidad').val(calc1);
                jQuery('#r_min_cantidad').html(formatNumber.new(cantidad_min, "$"));
                jQuery('#r_max_cantidad').html(formatNumber.new(cantidad_max, "$"));
                setCols();
                activaSeleccion();
                tabSeleccion = true;
            }
            else{
                activaSeleccion();
            }

            


        
        
    }


    //SETCOLS
    function setCols(){
        
        var p_carousel = jQuery('<div>', {
            'id': 'carouselPlazos',
            'class': 'col-12 carousel slide',
            'data-ride': 'carousel',
            'data-wrap': 'false' ,
            'data-interval': 'false'
        }).append(jQuery('<div>', { 'class': 'row' }).append(jQuery('<div>', {
            'class': 'col-12 carousel-inner px-md-0'
        }), jQuery('<a>', {
            'class': 'carousel-control-prev d-md-none',
            'href': '#carouselPlazos',
            'role': 'button',
            'data-slide': 'prev'
        }).append(jQuery('<span>', {
            'class': 'carousel-control-prev-icon',
            'aria-hidden': 'true',
            'html': '<i class="fas fa-chevron-left"></i>'
        }), jQuery('<span>', {
            'class': 'sr-only',
            'html': 'Anterior'
        })), jQuery('<a>', {
            'class': 'carousel-control-next d-md-none',
            'href': '#carouselPlazos',
            'role': 'button',
            'data-slide': 'next'
        }).append(jQuery('<span>', {
            'class': 'carousel-control-next-icon',
            'aria-hidden': 'true',
            'html': '<i class="fas fa-chevron-right"></i>'
        }), jQuery('<span>', {
            'class': 'sr-only',
            'html': 'Siguiente'
        }))));
        jQuery('.cont-plazos').append(jQuery(p_carousel));
        
        getPlazos();

        
        jQuery('[data-toggle="tooltip"]').tooltip();
        calcular();
    }

    function getPlazos(){
        var numrow = 0;
        jQuery.each(plazos, function(indice, valor){
            console.log("indice",indice);
            console.log("valor",valor);
            var option_plazo = jQuery('<div>', {
                        'class': 'cont-plazo col-md-4 justify-content-center px-2',
                        'data-option': valor
                    }).append(jQuery('<div>',{
                        'class': 'plazo-col cont-form'
                    }).append(
                        jQuery('<div>',{'class':'disable'}),
                        jQuery('<div>',{'class':'tit-plazo','html':'Plazo '+valor+' meses'}),
                        jQuery('<div>',{
                            'class': 'descripcion'
                        }).append( 
                            jQuery('<div>',{'class':'item-desc', 'id': 'tasa_val'}).append(
                                jQuery('<div>',{'class':'name',
                                'data-toggle' : 'tooltip',
                                'data-placement' : 'top',
                                'title': 'Es el índice utilizado para medir el costo de tu crédito. La tasa de interés cobrada por Linxe en todos tus créditos es del 29,55% E.A. (Efectivo Anual).',
                                'html':'Tasa mensual <i class="fas fa-info-circle"></i>'
                                }),
                                 jQuery('<div>',{'class':'valor','html':'0'})
                                ),
                            jQuery('<div>',{'class':'item-desc', 'id': 'cuota_mensual'}).append(
                                jQuery('<div>',{'class':'name',
                                'data-toggle' : 'tooltip',
                                'data-placement' : 'top',
                                'title': 'Corresponde al pago mensual que será descontado directamente de tu nómina e incluye el capital, intereses y seguros de tu crédito.',
                                'html':'Cuota mensual <i class="fas fa-info-circle"></i>'}),
                                 jQuery('<div>',{'class':'valor upsize','html':'0'})
                                ),
                            /*
                            jQuery('<div>',{'class':'item-desc', 'id': 'cargo_seguros'}).append(
                                jQuery('<div>',{'class':'name',
                                'data-toggle' : 'tooltip',
                                'data-placement' : 'top',
                                'title': 'Hace referencia a un pago de póliza de aseguramiento sobre el valor del crédito en caso de muerte o incapacidad del titular.',
                                'html':'Seguros <i class="fas fa-info-circle"></i>'}),
                                 jQuery('<div>',{'class':'valor','html':'0'})
                                ), 
                            jQuery('<div>',{'class':'item-desc', 'id': 'cuota_mas_seguros'}).append(
                                jQuery('<div>',{'class':'name',
                                'html':'Cuota más seguros'}),
                                 jQuery('<div>',{'class':'valor','html':'0'})
                                ), 
                            jQuery('<div>',{'class':'item-desc', 'id': 'cuota_tecnologia'}).append(
                                jQuery('<div>',{'class':'name',
                                'data-toggle' : 'tooltip',
                                'data-placement' : 'top',
                                'title': 'Estos son cargos asociados al uso de la plataforma, te garantiza la disponibilidad, administración y mantenimiento de la misma, también están relacionados con el manejo de tus créditos y los beneficios que podrás obtener dentro de nuestro sistema. Tendrás acceso a una cuenta desde la cual podrás hacer seguimiento al estado de: tus créditos y al historial crediticio con Linxe.',
                                'html':'Tecnología <i class="fas fa-info-circle"></i>'}),
                                 jQuery('<div>',{'class':'valor','html':'0'})
                                ), 
                            jQuery('<div>',{'class':'item-desc', 'id': 'cargo_iva'}).append(
                                jQuery('<div>',{'class':'name',
                                'html':'I.V.A.'}),
                                 jQuery('<div>',{'class':'valor','html':'0'})
                                ), */
                            jQuery('<div>',{'class':'item-desc', 'id': 'total_desembolsar'}).append(
                                jQuery('<div>',{'class':'name',
                                'html':'*Total a desembolsar'}),
                                 jQuery('<div>',{'class':'valor','html':'0'})
                                ), 
                            jQuery('<div>',{'class':'item-desc', 'id': 'total_pagar'}).append(
                                jQuery('<div>',{'class':'name',
                                'html':'Total a pagar'}),
                                 jQuery('<div>',{'class':'valor','html':'0'})
                                )
                        ),
                         jQuery('<div>',{'class': 'terminos'}).append(
                             jQuery('<div>',{'class':'form-element custom-check mr-0'}).append(
                                 jQuery('<input>',{
                                     'type': 'checkbox',
                                     'id': 'terminos',
                                     'name': 'terminos',
                                     'class': 'form-check-input',
                                     'value': ''
                                 }),
                                 jQuery('<span>',{'class':'checkmark'}),
                                 jQuery('<label>',{'class':'label', 'for':'terminos'}).append(
                                     jQuery('<a>',{
                                         'href': '#',
                                         'class': 'text-left terminos-item',
                                         'html': 'Acepto términos<br />y condiciones'
                                     })))),
                         jQuery('<button>',{
                             'type': 'button',
                             'class': 'btn-submit btn1 w-100 modal-btn sendSol',
                             'id': 'option'+valor,
                             'data-option': valor,
                             'data-modal': 'credito-seleccionado',
                             'html': 'Seleccionar Crédito'
                         }) )
                );
            if (currentDevice == 'desktop') {
                if (((indice + 1) % 3) == 1) {
                    numrow++;
                    jQuery('#carouselPlazos .carousel-inner').append(jQuery('<div>', {
                        'class': 'row row-' + numrow
                    }));
                    jQuery('#carouselPlazos .row-' + numrow).append(jQuery(option_plazo));

                    if (numrow == 1) {
                        jQuery('#carouselPlazos .row-1 .cont-plazo').addClass('active');
                    }
                }
                else {
                    jQuery('#carouselPlazos .row-' + numrow).append(jQuery(option_plazo));
                }
            }
            else {
                numrow++;
                jQuery('#carouselPlazos .carousel-inner').append(jQuery('<div>', {
                    'class': 'row carousel-item row-' + numrow
                }));
                jQuery('#carouselPlazos .row-' + numrow).append(jQuery(option_plazo));
                if (numrow == 1) {
                    jQuery('#carouselPlazos .row-1').addClass('active');
                }
            }

            jQuery(".terminos-item").on("click",function(e){
                console.log("click terminos item")
                var monto_temp = jQuery('#cantidad').val();
                var plazo_temp = jQuery(this).closest(".cont-plazo").data("option");
                
                var h = (window.innerHeight)*80/100;
                jQuery("#modalShowTerminos .modal-body").css("height",h+"px");
                jQuery('#modalShowTerminos').on('shown.bs.modal', function () {
                    jQuery("#iframeterms").attr('src',"/terminos-y-condiciones-creditos?monto_temp="+monto_temp+"&plazo_temp="+plazo_temp)
                    /*jQuery('#celular-conf').html(celphone);
                    jQuery('#correo-conf').html(email);
                    jQuery('input#btn-submit').on('click', paso4);*/
                });
                jQuery('#modalShowTerminos').on('hidden.bs.modal', function () {
                    jQuery('header').removeClass('inBlur');
                    jQuery('main').removeClass('inBlur');
                    jQuery('footer').removeClass('inBlur');
                    //jQuery('.modal').remove();
                });
                jQuery('#modalShowTerminos').modal('show');
            });

        });         
    }


//ACTIVASELECCION
    function activaSeleccion(){
        jQuery('.btnMas').on('click', changeCantidad);
        jQuery('.btnMenos').on('click', changeCantidad);
        
        function changeCantidad(e){
            var actual = parseInt(jQuery('#cantidad').val());
            var nueva=actual;
            switch (e.target.id) {
                case 'btnMenos':
                    if( (actual - 50000) >= cantidad_min )
                        nueva = actual - 50000;
                    break;
                case 'btnMas':
                    if( (actual + 50000) <= cantidad_max )
                        nueva = actual + 50000;
                    break;
            }
            jQuery('#cantidad').val(nueva);
            calcular();
        }
    }
    //CALCULAR
    function calcular(){     
        //jQuery('[data-toggle="tooltip"]').tooltip();
        var cantidad = jQuery('#cantidad').val();
        //alert(cantidad)
        if( cantidad >= cantidad_max ){
            jQuery('.btnMas').addClass('active');
        }
        else if(cantidad <= cantidad_min ){
            jQuery('.btnMenos').addClass('active');
        }
        else{
            jQuery('.btnMenos').removeClass('active');
            jQuery('.btnMas').removeClass('active');
        }
        //SETPLAZOS
        var cantidadNum = parseFloat(cantidad);

        if( tope < montos[0] )
        {
            jQuery('.cont-plazo').addClass('no-disponible');
        }else{
            if( cantidadNum === montos[1] ){
                jQuery('.cont-plazo').removeClass('no-disponible');
            }else{
                if( cantidadNum < montos[1] ){
                    jQuery('.cont-plazo[data-option="'+plazos[1]+'"] , .cont-plazo[data-option="'+plazos[2]+'"]').addClass('no-disponible');
                }
                else{
                    jQuery('.cont-plazo[data-option="'+plazos[0]+'"]').addClass('no-disponible');
                    if( cantidadNum > montos[2] ){
                       jQuery('.cont-plazo[data-option="'+plazos[1]+'"]').addClass('no-disponible'); 
                    }
                    else{
                        jQuery('.cont-plazo[data-option="'+plazos[1]+'"]').removeClass('no-disponible');
                    }
                }
            }
        }
        

        jQuery.each(jQuery('.cont-plazo'), function(){
           current_plazo = parseInt(jQuery(this).attr('data-option'));
            var plazo = -(current_plazo);
            //CALCULO DE VALORES
            tasa_val = (cantidad / 100)*tasa;
            cuota_mensual = ((cantidad / 100) * tasa) / (1 - (((100 + tasa) / 100)**plazo ) );
            //cargo_seguros = (cantidad * seguros) / 100;
            //cuota_mas_seguros = cuota_mensual + cargo_seguros;
            //cuota_tecnologia = cargo_tecnologia;
            //cargo_iva = (cuota_tecnologia / 100) * iva;
            //total_desembolsar = cantidad - (cuota_tecnologia + cargo_iva);
            total_desembolsar = cantidad;
            //total_pagar = cuota_mas_seguros * current_plazo; 
            total_pagar = cuota_mensual * current_plazo; 
            if( !jQuery(this).hasClass('no-disponible') ){
                jQuery.each(jQuery(this).find('.item-desc'), function(){
                    var item = jQuery(this).attr('id');
                    var item_v = eval(item);
                    jQuery(this).find('.valor').html(formatNumber.new(Math.round(item_v), "$"));
                    if(item === 'tasa_val'){
                        jQuery(this).find('.valor').html(tasa+'%');
                    }
                });
            }else{
                jQuery(this).find('.valor').html('0');
            }
            jQuery(this).find('.btn-submit').attr('disabled','disabled');
            jQuery(this).find('#terminos').prop('checked', false);
            jQuery(this).find('#terminos').val('');
            
        });
        var cantidadvalue0 = parseInt(jQuery('#cantidad').val(), 10);
        if(cantidadvalue0 < cantidad_min)
        {
            jQuery('#cantidadVal').html("0");
        }else{
           jQuery('#cantidadVal').html(formatNumber.new(jQuery('#cantidad').val())); 
        }
        
        activeButton();
    }

    //ACTIVEBUTTON
    function activeButton() {
        jQuery('.cont-plazo #terminos').on('click', function(){
            if(jQuery(this).is(':checked')){
                jQuery('#carouselPlazos #terminos').not(jQuery(this)).prop('checked', false);
                jQuery('#carouselPlazos #terminos').not(jQuery(this)).val('');
                jQuery('.cont-plazo').removeClass('current');
                jQuery('.cont-plazo .btn-submit').attr('disabled','disabled');
                jQuery(this).parents('.cont-plazo').addClass('current');
                jQuery(this).parents('.cont-plazo').find('.btn-submit').attr('disabled',false);
            }else{
                jQuery(this).parents('.cont-plazo').removeClass('current');
                jQuery(this).parents('.cont-plazo').find('.btn-submit').attr('disabled','disabled');
            }
            jQuery('.cont-plazo').find('.btn-submit').on('click', enviarSolicitud);
            
        });

        var setSolicitud = function(){
            var currentCredit = jQuery('.cont-plazo.current');
            var currentOption = jQuery(currentCredit).attr('data-option');
            var datos = jQuery(currentCredit).find('.item-desc');
            var solicitud = [];
            current_plazo = currentOption;
            solicitud.push({'name': 'numero_cuotas', 'value': parseInt(currentOption)});
            datos.each(function (i) {
                var name = jQuery(datos[i]).attr('id');
                var valor_txt = jQuery(datos[i]).find('.valor').text();
                var valor = valor_txt.replace(/[^0-9]/g, '');
                solicitud.push({
                    'name': name,
                    'value': valor
                });
            });
            solicitudJSON = solicitud.reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
            return true;
        }

        function enviarSolicitud(e){
            if( !setSolicitud() ){
                console.log('NO SE ENVIA');
                e.preventDefault();
            }
            else{
                //solicitudJSON = JSON.stringify(solicitudJSON);
                console.log(solicitudJSON);
                

                showModal();
                function showModal() {

                    jQuery('#modalSeleccionCredito').modal('show');
                    jQuery('#modalSeleccionCredito').on('shown.bs.modal', function(){
                        active_modals_seleccion();
                    });
                }
                e.preventDefault();
            }
            e.stopImmediatePropagation();
        }
    }


    function active_modals_seleccion() {
        jQuery('header').addClass('inBlur');
        jQuery('main').addClass('inBlur');
        jQuery('footer').addClass('inBlur');
        jQuery('#modalSeleccionCredito').on('hidden.bs.modal', function () {
            jQuery('header').removeClass('inBlur');
            jQuery('main').removeClass('inBlur');
            jQuery('footer').removeClass('inBlur');
        });
        
        isform();
    }


    function enviarAutorizacion(solicitudJSON)
    {   
        console.log(solicitudJSON)
        var parametros = {
            "tipoid" : document_type,
            "numid" : document_number,
            "monto" : solicitudJSON["total_desembolsar"],
            "plazo" : solicitudJSON["numero_cuotas"]
        };
        console.log(parametros)

        if(enviado==false)
        {
            enviado=true;
            jQuery.ajax({
                data:  parametros, //datos que se envian a traves de ajax
                url:   '/servicios/setseleccioncredito', //archivo que recibe la peticion
                type:  'post', //método de envio
                success:  function (data) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    console.log('AJAX call was successful!');
                    console.log(data);
                    if(data.status == true)
                    {
                        console.log(data.msg); 
                        var parametros = {
                            "tipoid" : document_type,
                            "numid" : document_number
                        };
                        console.log(parametros)
                        //jQuery('body').append('<div class="modal fade" role="dialog"></div>');
                        jQuery.ajax({
                            data:  parametros, 
                            url:   '/servicios/setpagareblanco', 
                            type:  'post', 
                            success:  function (data) { 
                                console.log(data);
                                enviado=false;
                                if(data.status == true)
                                {
                                    console.log(data.msg); 
                                }else{
                                    console.log('Error:'+data.error); 
                                }
                                jQuery('#modalSeleccionCredito').modal('hide');
                                document.location.href = "/dashboard/contrato";
                            },
                            error: function() {
                                console.log('There was some error performing the AJAX call!');
                                jQuery('#modalSeleccionCredito').modal('hide');
                                document.location.href = "/dashboard/contrato";
                            }
                        });
                        
                    }else{
                        jQuery('#modalSeleccionCredito').modal('hide');
                        console.log('Error:'+data.error); 
                    }
                },
                error: function() {
                    console.log('There was some error performing the AJAX call!');
                }
            });
        }
            
    }





var hash = document.location.hash;
var tabSeleccion = false;
var tabHistorial = false;
var tabInfo = false;
var user_name, user_lastname, document_type, document_number, celphone, email, tope, history_url, password;
var tope = 1000000;


var cantidad_min = 500000;
var cantidad_max = tope;
var calc1 = cantidad_min + ((cantidad_max - cantidad_min) / 2);

var seguros = 0.30;
var iva = 19;
var cargo_tecnologia = 50000;
var cargo_administrativo = 0;

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

    console.log("entro adelanto");
    jQuery(function cargainicial() {
        
            hash = '#seleccion';
        
        jQuery('.nav-tabs a[href="' + hash + '"]').tab('show');
        
    });
    
    if (jQuery('body').hasClass('dashboard')) {
        if(m==0)
        {
            jQuery('#firmar').click( function(){
                console.log(solicitudJSON)
                enviarAutorizacion(solicitudJSON) 
            });
            cantidad_min = parseInt(drupalSettings.linxecredit.librarydashboard.cantidad_min);
            tope = parseInt(drupalSettings.linxecredit.librarydashboard.valormontoaprobado);
            cantidad_max = tope;

            calc1 = tope;          

            console.log("calc1:",calc1);
            console.log("librarydashboard: ",drupalSettings.linxecredit.librarydashboard);
            
            seguros = drupalSettings.linxecredit.librarydashboard.seguro_adelanto;
            iva = parseInt(drupalSettings.linxecredit.librarydashboard.iva_adelanto);
            cargo_tecnologia = parseInt(drupalSettings.linxecredit.librarydashboard.cargo_tecnologia_adelanto);
            cargo_administrativo = parseInt(drupalSettings.linxecredit.librarydashboard.cargo_administracion_adelanto);

            document_type = drupalSettings.linxecredit.librarydashboard.tipoid;
            document_number = drupalSettings.linxecredit.librarydashboard.numid;
            register_id = drupalSettings.linxecredit.librarydashboard.idregistro;
            

            plazostxt = drupalSettings.linxecredit.librarydashboard.plazos;
            plazos = plazostxt.split(",");
            plazos = plazos.map(function (x) { 
              return parseInt(x, 10); 
            });

            montostxt = drupalSettings.linxecredit.librarydashboard.montos;
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
                console.log("veo",jQuery('#cantidad').val())
                jQuery('#r_min_cantidad').html(formatNumber.new(montos[0], "$"));
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
        jQuery.each(montos, function(indice, valor){
            console.log("indice",indice);
            console.log("valor",valor);

            //CALCULO DE VALORES
            var cantidad = valor;
            var cuota_administracion = cargo_administrativo;
            var cuota_tecnologia = cargo_tecnologia;
            var cargo_seguros = (cantidad * seguros) / 100;
            var cargo_iva = (cuota_tecnologia / 100) * iva;

            
            
            
            var total_pagar = cantidad + cuota_administracion + cuota_tecnologia + cargo_seguros + cargo_iva;
            //total_desembolsar = cantidad;


            var option_plazo = jQuery('<div>', {
                        'class': 'cont-plazo col-md-4 justify-content-center px-2',
                        'data-option': valor
                    }).append(jQuery('<div>',{
                        'class': 'plazo-col cont-form'
                    }).append(
                        jQuery('<div>',{'class':'disable'}),
                        jQuery('<div>',{'class':'tit-plazo','html':'Plazo '+plazos[indice]+' días'}),
                        jQuery('<div>',{
                            'class': 'descripcion'
                        }).append( 
                            jQuery('<div>',{'class':'item-desc', 'id': 'tasa_val'}).append(
                                jQuery('<div>',{'class':'name',
                                'data-toggle' : 'tooltip',
                                'data-placement' : 'top',
                                'title': 'Este costo corresponde a la disponibilidad del cupo aprobado, cada vez que los pagues, siempre lo tendras disponible. Teniendo en cuenta que las condiciones de riesgo analizadas se manetengan iguales o mejoren.',
                                'html':'Administración <i class="fas fa-info-circle"></i>'
                                }),
                                 jQuery('<div>',{'class':'valor','html':formatNumber.new(Math.round(cuota_administracion), "$")})
                                ),
                            

                            jQuery('<div>',{'class':'item-desc', 'id': 'cargo_seguros'}).append(
                                jQuery('<div>',{'class':'name',
                                'data-toggle' : 'tooltip',
                                'data-placement' : 'top',
                                'title': 'Hace referencia a un pago de póliza de aseguramiento sobre el valor del adelanto de nómina en caso de muerte o incapacidad del titular.',
                                'html':'Seguros <i class="fas fa-info-circle"></i>'}),
                                 jQuery('<div>',{'class':'valor','html':formatNumber.new(Math.round(cargo_seguros), "$")})
                                ), 
                            
                            jQuery('<div>',{'class':'item-desc', 'id': 'cuota_tecnologia'}).append(
                                jQuery('<div>',{'class':'name',
                                'data-toggle' : 'tooltip',
                                'data-placement' : 'top',
                                'title': 'Es un cargo asociado al uso de la plataforma, te garantiza la disponibilidad, administración y mantenimiento de la misma, además de los beneficios que podrás obtener dentro de nuestro sistema. Tendrás acceso a través de una cuenta personal desde la cual podrás hacer seguimiento al estado de tus créditos y al historial crediticio con Linxe. Este es un cargo opcional, si no lo quieres pagar, el proceso será diferente y deberás enviarnos un mail a contacto@linxe.com para que te expliquemos cómo sería.',
                                'html':'Tecnología <i class="fas fa-info-circle"></i>'}),
                                 jQuery('<div>',{'class':'valor','html':formatNumber.new(Math.round(cuota_tecnologia), "$")})
                                ), 

                            jQuery('<div>',{'class':'item-desc', 'id': 'cargo_iva'}).append(
                                jQuery('<div>',{'class':'name',
                                'html':'I.V.A.'}),
                                 jQuery('<div>',{'class':'valor','html':formatNumber.new(Math.round(cargo_iva), "$")})
                                ), 

                            jQuery('<div>',{'class':'item-desc', 'id': 'total_pagar'}).append(
                                jQuery('<div>',{'class':'name',
                                'html':'Total a pagar'}),
                                 jQuery('<div>',{'class':'valor','html':formatNumber.new(Math.round(total_pagar), "$")})
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
                                 jQuery('<label>',{'class':'label text-center', 'for':'terminos'}).append(
                                     jQuery('<a>',{
                                         'href': '#',
                                         'class': 'text-left terminos-item',
                                         'html': 'Acepto términos y condiciones'
                                     })))),
                         jQuery('<button>',{
                             'type': 'button',
                             'class': 'btn-submit btn1 w-100 modal-btn sendSol',
                             'id': 'option'+indice,
                             'data-option': indice,
                             'data-modal': 'credito-seleccionado',
                             'html': 'Seleccionar Adelanto'
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
                
                var h = (window.innerHeight)*80/100;
                jQuery("#modalShowTerminos .modal-body").css("height",h+"px");
                jQuery('#modalShowTerminos').on('shown.bs.modal', function () {
                    jQuery("#iframeterms").attr('src',"/terminos-y-condiciones-adelanto-nomina?monto_temp="+monto_temp)
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
            var index = 0;
            for(i=0;i<montos.length;i++)
            {
                if(actual==montos[i])
                    index = i;
            }
            console.log("index : ",index)
            switch (e.target.id) {
                case 'btnMenos':
                        if(index > 0){
                            index--;
                            nueva = montos[index];
                            console.log("resto")
                        }
                    break;
                case 'btnMas':
                    if(index < montos.length-1 ){
                        index++;
                        nueva = montos[index];
                        console.log("sumo")
                    }
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
        
        if( cantidad == cantidad_max ){
            jQuery('.btnMas').addClass('active');
        }else{
            jQuery('.btnMas').removeClass('active');
        }
        if(cantidad == cantidad_min ){
            jQuery('.btnMenos').addClass('active');
        }else{
            jQuery('.btnMenos').removeClass('active');
        }
        //SETPLAZOS
        var cantidadNum = parseFloat(cantidad);

        jQuery.each(jQuery('.cont-plazo'), function(){
            var canticurrent =parseFloat(jQuery(this).data("option"));
            if( canticurrent == cantidadNum )
            {
                jQuery(this).removeClass('no-disponible');
            }else{
                jQuery(this).addClass('no-disponible');
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
            solicitud.push({'name': 'monto_seleccionado', 'value': parseInt(currentOption)});
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
            "idregistro" : register_id,
            "monto" : solicitudJSON["monto_seleccionado"]
        };
        console.log(parametros)

        if(enviado==false)
        {
            enviado=true;
            jQuery.ajax({
                data:  parametros, //datos que se envian a traves de ajax
                url:   '/servicios/setseleccionadelanto', //archivo que recibe la peticion
                type:  'post', //método de envio
                success:  function (data) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    console.log('AJAX call was successful!');
                    console.log(data);
                    if(data.status == true)
                    {
                        console.log(data.msg); 
                        jQuery('#modalSeleccionCredito').modal('hide');
                        document.location.href = "/dashboard/datos-empresa";
                        
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



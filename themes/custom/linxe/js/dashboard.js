var hash = document.location.hash;
var tabSeleccion = false;
var tabHistorial = false;
var tabInfo = false;
var user_name, user_lastname, document_type, document_number, celphone, email, tope, history_url, password;

function getDatos() {
    var URLprotocol = window.location.protocol;
    //console.log(URLprotocol);

    jQuery.ajax({
        url: '',
        crossDomain: true,
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
            jQuery.each(data, function(i){
               user_name = data[i].nombre;
               user_lastname = data[i].apellido;
               document_type = data[i].tipo_documento;
               document_number = data[i].numero_documento;
               celphone = data[i].celular;
               email = data[i].correo;
               tope = data[i].tope;
               history_url = data[i].historial;
               user_pass = data[i].password;
            });
            initDashboard();
        }
    });
}
function initDashboard() {
    jQuery('#tope').html(formatNumber.new(tope));
    jQuery('#username').html('Hola '+user_name);
    jQuery(function cargainicial() {
        if (!hash) {
            hash = '#seleccion';
        }
        jQuery('.nav-tabs a[href="' + hash + '"]').tab('show');
    });

    jQuery('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
        
        var destino = jQuery(e.target).attr('aria-controls');
        switch (destino) {
            case 'seleccion':
                seleccionarCredito();
                break;
            case 'creditos':
                historial();
                break;
            case 'info':
                editarInfo();
                break;
            default:
                console.log('No hubo carga');
        }
         jQuery('body, html').animate({scrollTop:0}, '300');
    });
    ////////////////////////////////////////////////////////////////////////////////////////
    function seleccionarCredito() {
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
        var solicitudJSON;
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
        //SETCOLS
        function setCols(){
            var numrow = 0;
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

            function getPlazos(){
                jQuery.each(plazos, function(indice, valor){
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
                                         jQuery('<div>',{'class':'valor','html':'0'})
                                        ),
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
                                        ), 
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
                                                 'class': 'text-left',
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
                });         
            }
            jQuery('[data-toggle="tooltip"]').tooltip();
            calcular();
        }
        //ACTIVASELECCION
        function activaSeleccion(){
            jQuery('.btnMas').on('click', changeCantidad);
            jQuery('.btnMenos').on('click', changeCantidad);
            
            function changeCantidad(e){
                var actual = parseInt(jQuery('#cantidad').val());
                var nueva;
                switch (e.target.id) {
                    case 'btnMenos':
                        nueva = actual - 50000;
                        break;
                    case 'btnMas':
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
            if( cantidadNum === 800000 ){jQuery('.cont-plazo').removeClass('no-disponible');}
            else{
                if( cantidadNum < 800000 ){
                    jQuery('.cont-plazo[data-option="8"] , .cont-plazo[data-option="10"]').addClass('no-disponible');
                }
                else{
                    jQuery('.cont-plazo[data-option="6"]').addClass('no-disponible');
                    if( cantidadNum > 1000000 ){
                       jQuery('.cont-plazo[data-option="8"]').addClass('no-disponible'); 
                    }
                    else{
                        jQuery('.cont-plazo[data-option="8"]').removeClass('no-disponible');
                    }
                }
            }

            jQuery.each(jQuery('.cont-plazo'), function(){
               current_plazo = parseInt(jQuery(this).attr('data-option'));
                var plazo = -(current_plazo);
                //CALCULO DE VALORES
                tasa_val = (cantidad / 100)*tasa;
                cuota_mensual = ((cantidad / 100) * tasa) / (1 - (((1*100 + tasa) / 100)**plazo ) );
                cargo_seguros = (cantidad * seguros) / 100;
                cuota_mas_seguros = cuota_mensual + cargo_seguros;
                cuota_tecnologia = cargo_tecnologia;
                cargo_iva = (cuota_tecnologia / 100) * iva;
                total_desembolsar = cantidad - (cuota_tecnologia + cargo_iva);
                total_pagar = cuota_mas_seguros * current_plazo; 
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
            jQuery('#cantidadVal').html(formatNumber.new(jQuery('#cantidad').val()));
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
                    var valor = parseInt(valor_txt.replace(/[^0-9]/g, ''));
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
                    solicitudJSON = JSON.stringify(solicitudJSON);
                    console.log(solicitudJSON);
                    jQuery('body').append('<div class="modal fade" role="dialog"></div>');
                    showModal();
                    function showModal() {
                        jQuery('.modal').load('modals/credito-seleccionado.html', function () {
                            jQuery('.modal').modal('show');
                            jQuery('.modal').on('shown.bs.modal', function(){
                                active_modals();
                                jQuery('#firmar').on('click', paso3);
                            });
                        });
                    }
                    e.preventDefault();
                }
                e.stopImmediatePropagation();
            }
        }
        ////////////////////////////PASOS
        function paso2(){
            console.log('PASO 2');
            jQuery('.estado-paso.current').removeClass('current');
            jQuery('.estado-paso[data-state="paso2"]').switchClass('check','current');
            jQuery('.progress-bar').css('width','33%');
            jQuery('.progress-bar').attr('aria-valuenow','33%');
            jQuery('#selected').slideUp(function(){
                jQuery('#selected #plazo-selected').html('');
                jQuery('#selected #monto-selected').html('');
                jQuery('#no-selected').slideDown();
            });
        }

        function paso3(){
            jQuery('.progress-bar').css('width','66%');
            jQuery('.progress-bar').attr('aria-valuenow','66%');
            jQuery('.estado-paso.current').addClass('check');
            jQuery('.estado-paso.current').removeClass('current');
            jQuery('.estado-paso[data-state="paso3"]').addClass('current');
            jQuery('[data-step="paso2"]').fadeOut(function(){
                jQuery('#no-selected').slideUp(function(){
                    jQuery('#selected #plazo-selected').html('Plazo: '+current_plazo+' meses');
                    jQuery('#selected #monto-selected').html(formatNumber.new(jQuery('#cantidad').val()));
                    jQuery('#selected').slideDown();
                });
                jQuery('[data-step="paso3"]').slideDown(function(){
                    jQuery('.modal').modal('hide');
                });
            });
            jQuery('#regresar').on('click', function(){
                jQuery('[data-step="paso3"]').fadeOut(function(){
                    jQuery('[data-step="paso2"]').slideDown();
                    paso2();
                });
            });
            jQuery('.cont-pdf').slideDown();
            

            jQuery('#confirmar-firma').on('click', function () {
                jQuery('body').append('<div class="modal fade" role="dialog"></div>');
                showModal();
                function showModal() {
                    jQuery('.modal').load('modals/confirmar-credito.html', function () {
                        jQuery('.modal').modal('show');
                        jQuery('.modal').on('shown.bs.modal', function () {
                            isform();
                            jQuery('#celular-conf').html(celphone);
                            jQuery('#correo-conf').html(email);
                            jQuery('input#btn-submit').on('click', paso4);
                        });
                        jQuery('.modal').on('hidden.bs.modal', function () {
                            jQuery('header').removeClass('inBlur');
                            jQuery('main').removeClass('inBlur');
                            jQuery('footer').removeClass('inBlur');
                            jQuery('.modal').remove();
                        });
                    });
                }
                e.stopImmediatePropagation();
            });
        }

        function paso4(){
            jQuery('.progress-bar').css('width','100%');
            jQuery('.progress-bar').attr('aria-valuenow','100%');
            jQuery('.estado-paso.current').addClass('check');
            jQuery('.estado-paso.current').removeClass('current')
            jQuery('.estado-paso[data-state="paso4"]').addClass('check');
            jQuery('[data-step="paso3"]').fadeOut(function(){
                jQuery('[data-step="paso4"]').slideDown(function(){
                    jQuery('.modal').modal('hide');
                });
            });
            jQuery('#felicitacion').html('Felicitaciones '+user_name);
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////
    function historial() {
        if (tabHistorial == false) {
            var numrow = 0;
            var c_carousel = jQuery('<div>', {
                'id': 'misCreditos',
                'class': 'col-12 carousel slide',
                'data-ride': 'carousel',
                'data-wrap': 'false' ,
                'data-interval': 'false'
            }).append(jQuery('<div>', { 'class': 'row' }).append(jQuery('<div>', {
                'class': 'col-12 carousel-inner'
            }), jQuery('<a>', {
                'class': 'carousel-control-prev',
                'href': '#misCreditos',
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
                'class': 'carousel-control-next',
                'href': '#misCreditos',
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
            jQuery('.cont-creditos').append(jQuery(c_carousel));
            getCreditos();

            function getCreditos() {
                jQuery.ajax({
                    url: ''+history_url,
                    
                    crossDomain: true,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {
                        jQuery.each(data, function (i) {
                            var estado = data[i].estado;
                            var fecha_desembolso = data[i].fecha_desembolso;
                            var tasa = data[i].tasa;
                            var cuota = data[i].cuota;
                            var seguros = data[i].seguros;
                            var cuotaYseguros = data[i].cuota_mas_seguros;
                            var tecnologia = data[i].tecnologia;
                            var iva = data[i].iva;
                            var totalDesembolso = data[i].total_desembolso;
                            var totalPagar = data[i].total_pagar;
                            var nCuotas = data[i].cuotas_total;
                            var cuotasP = data[i].cuotas_pendientes;
                            var saldo = data[i].saldo;
                            var labelestado;
                            if(estado === 'vigente'){labelestado = 'a pagar';}
                            else if(estado === 'pagado'){labelestado = 'pagado';}

                            var singleCre = jQuery('<div>', {
                                'class': 'col-md-4 d-flex justify-content-center px-2'
                            }).append(jQuery('<div>', {
                                'class': 'credito-col ' + estado
                            }).append(jQuery('<div>', {
                                'class': 'tit-credito'
                            }).append(jQuery('<div>', {
                                'class': 'tit',
                            }).append(jQuery('<div>', {
                                'class': 'estado',
                                'html': 'Crédito <span class="pos"></span>'+ estado
                            }),
                                jQuery('<div>', {
                                    'class': 'fecha-desembolso'
                                }).append(
                                    jQuery('<span>', {
                                        'class': 'label',
                                        'html': 'Fecha Desembolso: '
                                    }),
                                    jQuery('<span>', {
                                        'class': 'fecha',
                                        'html': fecha_desembolso
                                    }))), jQuery('<button>', {
                                        'class': 'btn-collapse',
                                        'type': 'button',
                                        'data-toggle': 'collapse',
                                        'data-target': '#credito-' + i,
                                        'aria-expanded': 'false',
                                        'aria-controls': 'credito-' + i,
                                        'html': '<i class="fas fa-chevron-down"></i>'
                                    })), jQuery('<div>', {
                                        'class': 'descripcion collapse',
                                        'id': 'credito-' + i
                                    }).append(
                                        jQuery('<div>', { 'class': 'item-desc' }).append(
                                            jQuery('<div>', { 'class': 'name', 'html': 'Tasa mensual ' }), jQuery('<div>', { 'class': 'valor', 'html': tasa })),
                                        jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Cuota mensual ' }), jQuery('<div>', { 'class': 'valor', 'html': cuota })),
                                        jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Seguros ' }), jQuery('<div>', { 'class': 'valor', 'html': seguros })),
                                        jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Cuota + Seguros ' }), jQuery('<div>', { 'class': 'valor', 'html': cuotaYseguros })),
                                        jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Tecnología ' }), jQuery('<div>', { 'class': 'valor', 'html': tecnologia })),
                                        jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'I.V.A. ' }), jQuery('<div>', { 'class': 'valor', 'html': iva })),
                                        jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': '*Total desembolsado ' }), jQuery('<div>', { 'class': 'valor', 'html': totalDesembolso })),
                                        jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Total '+labelestado }), jQuery('<div>', { 'class': 'valor', 'html': totalPagar })),
                                    ), jQuery('<div>', { 'class': 'info-credito' }).append(
                                        jQuery('<div>', { 'class': 'item-info' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Número de Cuotas ' }), jQuery('<div>', { 'class': 'valor', 'html': nCuotas })),
                                        jQuery('<div>', { 'class': 'item-info' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Cuotas Pendientes ' }), jQuery('<div>', { 'class': 'valor', 'html': cuotasP })),
                                        jQuery('<div>', { 'class': 'item-info' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Saldo ' }), jQuery('<div>', { 'class': 'valor', 'html': saldo }))
                                    )));
                            if (currentDevice == 'desktop') {
                                if (((i + 1) % 3) == 1) {
                                    numrow++;
                                    jQuery('#misCreditos .carousel-inner').append(jQuery('<div>', {
                                        'class': 'row carousel-item row-' + numrow
                                    }));
                                    jQuery('#misCreditos .row-' + numrow).append(jQuery(singleCre));

                                    if (numrow == 1) {
                                        jQuery('.row-1').addClass('active');
                                    }
                                }
                                else {
                                    jQuery('#misCreditos .row-' + numrow).append(jQuery(singleCre));
                                }
                            }
                            else {
                                numrow++;
                                jQuery('#misCreditos .carousel-inner').append(jQuery('<div>', {
                                    'class': 'row carousel-item row-' + numrow
                                }));
                                jQuery('#misCreditos .row-' + numrow).append(jQuery(singleCre));
                                if (numrow == 1) {
                                    jQuery('.row-1').addClass('active');
                                }
                            }
                            if(estado === 'pagado'){
                                jQuery(singleCre).find('span.pos').html(i+' ');
                            }
                        })
                    }
                });

            }
            tabHistorial = true;
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////
    function editarInfo() {
        var formEdit = jQuery('form#edit-info'),
                    elementos = formEdit.find('input, textarea, select ')
        if (tabInfo == false) {
                jQuery('#nombre').val(user_name);
                jQuery('#apellido').val(user_lastname);
                jQuery('#tipo_doc option[value="'+document_type+'"]').attr('selected', true);
                jQuery('#doc').val(document_number);
                jQuery('#celular').val(celphone);
                jQuery('#correo').val(email);
                jQuery('#current-pass').val('Escribir Contraseña');
                for (var i = 0; i < elementos.length; i++) {
                    if( jQuery(elementos[i]).val() && (jQuery(elementos[i]).attr('type') != 'submit') ){                            
                        jQuery(elementos[i]).siblings('label').addClass('active');
                    }
                }
            isform();
            activarForm();
            tabInfo = true;
        }
        else {
            activarForm();
        }

        function activarForm() {
            console.log('Validaciones');
        }

    }
}

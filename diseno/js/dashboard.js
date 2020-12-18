var hash = document.location.hash;
var tabSeleccion = false;
var tabHistorial = false;
var tabInfo = false;
var user_name, user_lastname, document_type, document_number, celphone, email, tope, history_url, password;

function getDatos() {
    var URLprotocol = window.location.protocol;
    //console.log(URLprotocol);

    $.ajax({
        url: '',
        crossDomain: true,
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
            $.each(data, function(i){
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
    $('#tope').html(formatNumber.new(tope));
    $('#username').html('Hola '+user_name);
    $(function cargainicial() {
        if (!hash) {
            hash = '#seleccion';
        }
        $('.nav-tabs a[href="' + hash + '"]').tab('show');
    });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
        
        var destino = $(e.target).attr('aria-controls');
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
         $('body, html').animate({scrollTop:0}, '300');
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
            $('.instruccion').hide();
            $('.row-paso').hide();
            $('[data-step="paso2"]').slideDown();
            $('#cantidad').val(calc1);
            $('#r_min_cantidad').html(formatNumber.new(cantidad_min, "$"));
            $('#r_max_cantidad').html(formatNumber.new(cantidad_max, "$"));
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
            var p_carousel = $('<div>', {
                'id': 'carouselPlazos',
                'class': 'col-12 carousel slide',
                'data-ride': 'carousel',
                'data-wrap': 'false' ,
                'data-interval': 'false'
            }).append($('<div>', { 'class': 'row' }).append($('<div>', {
                'class': 'col-12 carousel-inner px-md-0'
            }), $('<a>', {
                'class': 'carousel-control-prev d-md-none',
                'href': '#carouselPlazos',
                'role': 'button',
                'data-slide': 'prev'
            }).append($('<span>', {
                'class': 'carousel-control-prev-icon',
                'aria-hidden': 'true',
                'html': '<i class="fas fa-chevron-left"></i>'
            }), $('<span>', {
                'class': 'sr-only',
                'html': 'Anterior'
            })), $('<a>', {
                'class': 'carousel-control-next d-md-none',
                'href': '#carouselPlazos',
                'role': 'button',
                'data-slide': 'next'
            }).append($('<span>', {
                'class': 'carousel-control-next-icon',
                'aria-hidden': 'true',
                'html': '<i class="fas fa-chevron-right"></i>'
            }), $('<span>', {
                'class': 'sr-only',
                'html': 'Siguiente'
            }))));
            $('.cont-plazos').append($(p_carousel));
            getPlazos();

            function getPlazos(){
                $.each(plazos, function(indice, valor){
                    var option_plazo = $('<div>', {
                                'class': 'cont-plazo col-md-4 justify-content-center px-2',
                                'data-option': valor
                            }).append($('<div>',{
                                'class': 'plazo-col cont-form'
                            }).append(
                                $('<div>',{'class':'disable'}),
                                $('<div>',{'class':'tit-plazo','html':'Plazo '+valor+' meses'}),
                                $('<div>',{
                                    'class': 'descripcion'
                                }).append( 
                                    $('<div>',{'class':'item-desc', 'id': 'tasa_val'}).append(
                                        $('<div>',{'class':'name',
                                        'data-toggle' : 'tooltip',
                                        'data-placement' : 'top',
                                        'title': 'Es el índice utilizado para medir el costo de tu crédito. La tasa de interés cobrada por Linxe en todos tus créditos es del 29,55% E.A. (Efectivo Anual).',
                                        'html':'Tasa mensual <i class="fas fa-info-circle"></i>'
                                        }),
                                         $('<div>',{'class':'valor','html':'0'})
                                        ),
                                    $('<div>',{'class':'item-desc', 'id': 'cuota_mensual'}).append(
                                        $('<div>',{'class':'name',
                                        'data-toggle' : 'tooltip',
                                        'data-placement' : 'top',
                                        'title': 'Corresponde al pago mensual que será descontado directamente de tu nómina e incluye el capital, intereses y seguros de tu crédito.',
                                        'html':'Cuota mensual <i class="fas fa-info-circle"></i>'}),
                                         $('<div>',{'class':'valor','html':'0'})
                                        ),
                                    $('<div>',{'class':'item-desc', 'id': 'cargo_seguros'}).append(
                                        $('<div>',{'class':'name',
                                        'data-toggle' : 'tooltip',
                                        'data-placement' : 'top',
                                        'title': 'Hace referencia a un pago de póliza de aseguramiento sobre el valor del crédito en caso de muerte o incapacidad del titular.',
                                        'html':'Seguros <i class="fas fa-info-circle"></i>'}),
                                         $('<div>',{'class':'valor','html':'0'})
                                        ), 
                                    $('<div>',{'class':'item-desc', 'id': 'cuota_mas_seguros'}).append(
                                        $('<div>',{'class':'name',
                                        'html':'Cuota más seguros'}),
                                         $('<div>',{'class':'valor','html':'0'})
                                        ), 
                                    $('<div>',{'class':'item-desc', 'id': 'cuota_tecnologia'}).append(
                                        $('<div>',{'class':'name',
                                        'data-toggle' : 'tooltip',
                                        'data-placement' : 'top',
                                        'title': 'Estos son cargos asociados al uso de la plataforma, te garantiza la disponibilidad, administración y mantenimiento de la misma, también están relacionados con el manejo de tus créditos y los beneficios que podrás obtener dentro de nuestro sistema. Tendrás acceso a una cuenta desde la cual podrás hacer seguimiento al estado de: tus créditos y al historial crediticio con Linxe.',
                                        'html':'Tecnología <i class="fas fa-info-circle"></i>'}),
                                         $('<div>',{'class':'valor','html':'0'})
                                        ), 
                                    $('<div>',{'class':'item-desc', 'id': 'cargo_iva'}).append(
                                        $('<div>',{'class':'name',
                                        'html':'I.V.A.'}),
                                         $('<div>',{'class':'valor','html':'0'})
                                        ), 
                                    $('<div>',{'class':'item-desc', 'id': 'total_desembolsar'}).append(
                                        $('<div>',{'class':'name',
                                        'html':'*Total a desembolsar'}),
                                         $('<div>',{'class':'valor','html':'0'})
                                        ), 
                                    $('<div>',{'class':'item-desc', 'id': 'total_pagar'}).append(
                                        $('<div>',{'class':'name',
                                        'html':'Total a pagar'}),
                                         $('<div>',{'class':'valor','html':'0'})
                                        )
                                ),
                                 $('<div>',{'class': 'terminos'}).append(
                                     $('<div>',{'class':'form-element custom-check mr-0'}).append(
                                         $('<input>',{
                                             'type': 'checkbox',
                                             'id': 'terminos',
                                             'name': 'terminos',
                                             'class': 'form-check-input',
                                             'value': ''
                                         }),
                                         $('<span>',{'class':'checkmark'}),
                                         $('<label>',{'class':'label', 'for':'terminos'}).append(
                                             $('<a>',{
                                                 'href': '#',
                                                 'class': 'text-left',
                                                 'html': 'Acepto términos<br />y condiciones'
                                             })))),
                                 $('<button>',{
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
                            $('#carouselPlazos .carousel-inner').append($('<div>', {
                                'class': 'row row-' + numrow
                            }));
                            $('#carouselPlazos .row-' + numrow).append($(option_plazo));

                            if (numrow == 1) {
                                $('#carouselPlazos .row-1 .cont-plazo').addClass('active');
                            }
                        }
                        else {
                            $('#carouselPlazos .row-' + numrow).append($(option_plazo));
                        }
                    }
                    else {
                        numrow++;
                        $('#carouselPlazos .carousel-inner').append($('<div>', {
                            'class': 'row carousel-item row-' + numrow
                        }));
                        $('#carouselPlazos .row-' + numrow).append($(option_plazo));
                        if (numrow == 1) {
                            $('#carouselPlazos .row-1').addClass('active');
                        }
                    }
                });         
            }
            $('[data-toggle="tooltip"]').tooltip();
            calcular();
        }
        //ACTIVASELECCION
        function activaSeleccion(){
            $('.btnMas').on('click', changeCantidad);
            $('.btnMenos').on('click', changeCantidad);
            
            function changeCantidad(e){
                var actual = parseInt($('#cantidad').val());
                var nueva;
                switch (e.target.id) {
                    case 'btnMenos':
                        nueva = actual - 50000;
                        break;
                    case 'btnMas':
                        nueva = actual + 50000;
                        break;
                }
                $('#cantidad').val(nueva);
                calcular();
            }
        }
        //CALCULAR
        function calcular(){     
            //$('[data-toggle="tooltip"]').tooltip();
            var cantidad = $('#cantidad').val();
            if( cantidad >= cantidad_max ){
                $('.btnMas').addClass('active');
            }
            else if(cantidad <= cantidad_min ){
                $('.btnMenos').addClass('active');
            }
            else{
                $('.btnMenos').removeClass('active');
                $('.btnMas').removeClass('active');
            }
            //SETPLAZOS
            var cantidadNum = parseFloat(cantidad);
            if( cantidadNum === 800000 ){$('.cont-plazo').removeClass('no-disponible');}
            else{
                if( cantidadNum < 800000 ){
                    $('.cont-plazo[data-option="8"] , .cont-plazo[data-option="10"]').addClass('no-disponible');
                }
                else{
                    $('.cont-plazo[data-option="6"]').addClass('no-disponible');
                    if( cantidadNum > 1000000 ){
                       $('.cont-plazo[data-option="8"]').addClass('no-disponible'); 
                    }
                    else{
                        $('.cont-plazo[data-option="8"]').removeClass('no-disponible');
                    }
                }
            }

            $.each($('.cont-plazo'), function(){
               current_plazo = parseInt($(this).attr('data-option'));
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
                if( !$(this).hasClass('no-disponible') ){
                    $.each($(this).find('.item-desc'), function(){
                        var item = $(this).attr('id');
                        var item_v = eval(item);
                        $(this).find('.valor').html(formatNumber.new(Math.round(item_v), "$"));
                        if(item === 'tasa_val'){
                            $(this).find('.valor').html(tasa+'%');
                        }
                    });
                }else{
                    $(this).find('.valor').html('0');
                }
                $(this).find('.btn-submit').attr('disabled','disabled');
                $(this).find('#terminos').prop('checked', false);
                $(this).find('#terminos').val('');
                
            });
            $('#cantidadVal').html(formatNumber.new($('#cantidad').val()));
            activeButton();
        }

        //ACTIVEBUTTON
        function activeButton() {
            $('.cont-plazo #terminos').on('click', function(){
                if($(this).is(':checked')){
                    $('#carouselPlazos #terminos').not($(this)).prop('checked', false);
                    $('#carouselPlazos #terminos').not($(this)).val('');
                    $('.cont-plazo').removeClass('current');
                    $('.cont-plazo .btn-submit').attr('disabled','disabled');
                    $(this).parents('.cont-plazo').addClass('current');
                    $(this).parents('.cont-plazo').find('.btn-submit').attr('disabled',false);
                }else{
                    $(this).parents('.cont-plazo').removeClass('current');
                    $(this).parents('.cont-plazo').find('.btn-submit').attr('disabled','disabled');
                }
                $('.cont-plazo').find('.btn-submit').on('click', enviarSolicitud);
                
            });

            var setSolicitud = function(){
                var currentCredit = $('.cont-plazo.current');
                var currentOption = $(currentCredit).attr('data-option');
                var datos = $(currentCredit).find('.item-desc');
                var solicitud = [];
                current_plazo = currentOption;
                solicitud.push({'name': 'numero_cuotas', 'value': parseInt(currentOption)});
                datos.each(function (i) {
                    var name = $(datos[i]).attr('id');
                    var valor_txt = $(datos[i]).find('.valor').text();
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
                    $('body').append('<div class="modal fade" role="dialog"></div>');
                    showModal();
                    function showModal() {
                        $('.modal').load('modals/credito-seleccionado.html', function () {
                            $('.modal').modal('show');
                            $('.modal').on('shown.bs.modal', function(){
                                active_modals();
                                $('#firmar').on('click', paso3);
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
            $('.estado-paso.current').removeClass('current');
            $('.estado-paso[data-state="paso2"]').switchClass('check','current');
            $('.progress-bar').css('width','33%');
            $('.progress-bar').attr('aria-valuenow','33%');
            $('#selected').slideUp(function(){
                $('#selected #plazo-selected').html('');
                $('#selected #monto-selected').html('');
                $('#no-selected').slideDown();
            });
        }

        function paso3(){
            $('.progress-bar').css('width','66%');
            $('.progress-bar').attr('aria-valuenow','66%');
            $('.estado-paso.current').addClass('check');
            $('.estado-paso.current').removeClass('current');
            $('.estado-paso[data-state="paso3"]').addClass('current');
            $('[data-step="paso2"]').fadeOut(function(){
                $('#no-selected').slideUp(function(){
                    $('#selected #plazo-selected').html('Plazo: '+current_plazo+' meses');
                    $('#selected #monto-selected').html(formatNumber.new($('#cantidad').val()));
                    $('#selected').slideDown();
                });
                $('[data-step="paso3"]').slideDown(function(){
                    $('.modal').modal('hide');
                });
            });
            $('#regresar').on('click', function(){
                $('[data-step="paso3"]').fadeOut(function(){
                    $('[data-step="paso2"]').slideDown();
                    paso2();
                });
            });
            $('.cont-pdf').slideDown();
            

            $('#confirmar-firma').on('click', function () {
                $('body').append('<div class="modal fade" role="dialog"></div>');
                showModal();
                function showModal() {
                    $('.modal').load('modals/confirmar-credito.html', function () {
                        $('.modal').modal('show');
                        $('.modal').on('shown.bs.modal', function () {
                            isform();
                            $('#celular-conf').html(celphone);
                            $('#correo-conf').html(email);
                            $('input#btn-submit').on('click', paso4);
                        });
                        $('.modal').on('hidden.bs.modal', function () {
                            $('header').removeClass('inBlur');
                            $('main').removeClass('inBlur');
                            $('footer').removeClass('inBlur');
                            $('.modal').remove();
                        });
                    });
                }
                e.stopImmediatePropagation();
            });
        }

        function paso4(){
            $('.progress-bar').css('width','100%');
            $('.progress-bar').attr('aria-valuenow','100%');
            $('.estado-paso.current').addClass('check');
            $('.estado-paso.current').removeClass('current')
            $('.estado-paso[data-state="paso4"]').addClass('check');
            $('[data-step="paso3"]').fadeOut(function(){
                $('[data-step="paso4"]').slideDown(function(){
                    $('.modal').modal('hide');
                });
            });
            $('#felicitacion').html('Felicitaciones '+user_name);
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////
    function historial() {
        if (tabHistorial == false) {
            var numrow = 0;
            var c_carousel = $('<div>', {
                'id': 'misCreditos',
                'class': 'col-12 carousel slide',
                'data-ride': 'carousel',
                'data-wrap': 'false' ,
                'data-interval': 'false'
            }).append($('<div>', { 'class': 'row' }).append($('<div>', {
                'class': 'col-12 carousel-inner'
            }), $('<a>', {
                'class': 'carousel-control-prev',
                'href': '#misCreditos',
                'role': 'button',
                'data-slide': 'prev'
            }).append($('<span>', {
                'class': 'carousel-control-prev-icon',
                'aria-hidden': 'true',
                'html': '<i class="fas fa-chevron-left"></i>'
            }), $('<span>', {
                'class': 'sr-only',
                'html': 'Anterior'
            })), $('<a>', {
                'class': 'carousel-control-next',
                'href': '#misCreditos',
                'role': 'button',
                'data-slide': 'next'
            }).append($('<span>', {
                'class': 'carousel-control-next-icon',
                'aria-hidden': 'true',
                'html': '<i class="fas fa-chevron-right"></i>'
            }), $('<span>', {
                'class': 'sr-only',
                'html': 'Siguiente'
            }))));
            $('.cont-creditos').append($(c_carousel));
            getCreditos();

            function getCreditos() {
                $.ajax({
                    url: ''+history_url,
                    
                    crossDomain: true,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {
                        $.each(data, function (i) {
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

                            var singleCre = $('<div>', {
                                'class': 'col-md-4 d-flex justify-content-center px-2'
                            }).append($('<div>', {
                                'class': 'credito-col ' + estado
                            }).append($('<div>', {
                                'class': 'tit-credito'
                            }).append($('<div>', {
                                'class': 'tit',
                            }).append($('<div>', {
                                'class': 'estado',
                                'html': 'Crédito <span class="pos"></span>'+ estado
                            }),
                                $('<div>', {
                                    'class': 'fecha-desembolso'
                                }).append(
                                    $('<span>', {
                                        'class': 'label',
                                        'html': 'Fecha Desembolso: '
                                    }),
                                    $('<span>', {
                                        'class': 'fecha',
                                        'html': fecha_desembolso
                                    }))), $('<button>', {
                                        'class': 'btn-collapse',
                                        'type': 'button',
                                        'data-toggle': 'collapse',
                                        'data-target': '#credito-' + i,
                                        'aria-expanded': 'false',
                                        'aria-controls': 'credito-' + i,
                                        'html': '<i class="fas fa-chevron-down"></i>'
                                    })), $('<div>', {
                                        'class': 'descripcion collapse',
                                        'id': 'credito-' + i
                                    }).append(
                                        $('<div>', { 'class': 'item-desc' }).append(
                                            $('<div>', { 'class': 'name', 'html': 'Tasa mensual ' }), $('<div>', { 'class': 'valor', 'html': tasa })),
                                        $('<div>', { 'class': 'item-desc' }).append($('<div>', { 'class': 'name', 'html': 'Cuota mensual ' }), $('<div>', { 'class': 'valor', 'html': cuota })),
                                        $('<div>', { 'class': 'item-desc' }).append($('<div>', { 'class': 'name', 'html': 'Seguros ' }), $('<div>', { 'class': 'valor', 'html': seguros })),
                                        $('<div>', { 'class': 'item-desc' }).append($('<div>', { 'class': 'name', 'html': 'Cuota + Seguros ' }), $('<div>', { 'class': 'valor', 'html': cuotaYseguros })),
                                        $('<div>', { 'class': 'item-desc' }).append($('<div>', { 'class': 'name', 'html': 'Tecnología ' }), $('<div>', { 'class': 'valor', 'html': tecnologia })),
                                        $('<div>', { 'class': 'item-desc' }).append($('<div>', { 'class': 'name', 'html': 'I.V.A. ' }), $('<div>', { 'class': 'valor', 'html': iva })),
                                        $('<div>', { 'class': 'item-desc' }).append($('<div>', { 'class': 'name', 'html': '*Total desembolsado ' }), $('<div>', { 'class': 'valor', 'html': totalDesembolso })),
                                        $('<div>', { 'class': 'item-desc' }).append($('<div>', { 'class': 'name', 'html': 'Total '+labelestado }), $('<div>', { 'class': 'valor', 'html': totalPagar })),
                                    ), $('<div>', { 'class': 'info-credito' }).append(
                                        $('<div>', { 'class': 'item-info' }).append($('<div>', { 'class': 'name', 'html': 'Número de Cuotas ' }), $('<div>', { 'class': 'valor', 'html': nCuotas })),
                                        $('<div>', { 'class': 'item-info' }).append($('<div>', { 'class': 'name', 'html': 'Cuotas Pendientes ' }), $('<div>', { 'class': 'valor', 'html': cuotasP })),
                                        $('<div>', { 'class': 'item-info' }).append($('<div>', { 'class': 'name', 'html': 'Saldo ' }), $('<div>', { 'class': 'valor', 'html': saldo }))
                                    )));
                            if (currentDevice == 'desktop') {
                                if (((i + 1) % 3) == 1) {
                                    numrow++;
                                    $('#misCreditos .carousel-inner').append($('<div>', {
                                        'class': 'row carousel-item row-' + numrow
                                    }));
                                    $('#misCreditos .row-' + numrow).append($(singleCre));

                                    if (numrow == 1) {
                                        $('.row-1').addClass('active');
                                    }
                                }
                                else {
                                    $('#misCreditos .row-' + numrow).append($(singleCre));
                                }
                            }
                            else {
                                numrow++;
                                $('#misCreditos .carousel-inner').append($('<div>', {
                                    'class': 'row carousel-item row-' + numrow
                                }));
                                $('#misCreditos .row-' + numrow).append($(singleCre));
                                if (numrow == 1) {
                                    $('.row-1').addClass('active');
                                }
                            }
                            if(estado === 'pagado'){
                                $(singleCre).find('span.pos').html(i+' ');
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
        var formEdit = $('form#edit-info'),
                    elementos = formEdit.find('input, textarea, select ')
        if (tabInfo == false) {
                $('#nombre').val(user_name);
                $('#apellido').val(user_lastname);
                $('#tipo_doc option[value="'+document_type+'"]').attr('selected', true);
                $('#doc').val(document_number);
                $('#celular').val(celphone);
                $('#correo').val(email);
                $('#current-pass').val('Escribir Contraseña');
                for (var i = 0; i < elementos.length; i++) {
                    if( $(elementos[i]).val() && ($(elementos[i]).attr('type') != 'submit') ){                            
                        $(elementos[i]).siblings('label').addClass('active');
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

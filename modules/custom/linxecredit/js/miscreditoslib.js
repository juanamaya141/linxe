

var hash = document.location.hash;
var tabSeleccion = false;
var tabHistorial = false;
var tabInfo = false;
var user_name, user_lastname, document_type, document_number, celphone, email, tope, history_url, password;
var tope = 1000000;
var data;


var m = 0;


(function ($, Drupal, drupalSettings) {
Drupal.behaviors.LinxecreditBehavior = {
  attach: function (context, settings) {
    jQuery(function cargainicial() {
        
            hash = '#creditos';
        
        jQuery('.nav-tabs a[href="' + hash + '"]').tab('show');
        
    });
    
    if (jQuery('body').hasClass('dashboard')) {
        if(m==0)
        {

            data = drupalSettings.linxecredit.librarymiscreditos.misCreditosArray;
            console.log("entro aqui 1",data);
            console.log("entro aqui 1");
            historial();
            
            m=m+1;
        }
    }
    
    
  }
};
})(jQuery, Drupal, drupalSettings);

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
            if(data.length > 0)
            {
                jQuery('.cont-creditos').html("");
            }
            jQuery('.cont-creditos').append(jQuery(c_carousel));
            getCreditos();

            function getCreditos() {
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
                        'html': 'Crédito '+ estado
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
                                    jQuery('<div>', { 'class': 'name', 'html': 'Tasa mensual ' }), jQuery('<div>', { 'class': 'valor', 'html': tasa+"%" })),
                                jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Cuota mensual ' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+cuota })),
                                jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': '*Total desembolsado ' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+totalDesembolso })),
                                jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Total '+labelestado }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+totalPagar })),
                            ), jQuery('<div>', { 'class': 'info-credito' }).append(
                                jQuery('<div>', { 'class': 'item-info' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Número Total de Cuotas ' }), jQuery('<div>', { 'class': 'valor', 'html': nCuotas })),
                                jQuery('<div>', { 'class': 'item-info' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Cuotas Pendientes ' }), jQuery('<div>', { 'class': 'valor', 'html': cuotasP })),
                                jQuery('<div>', { 'class': 'item-info' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Saldo ' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+saldo }))
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
            tabHistorial = true;
        }
    }



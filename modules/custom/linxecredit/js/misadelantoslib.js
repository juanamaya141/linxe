

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

            data = drupalSettings.linxecredit.librarymisadelantos.misAdelantosArray;
            
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
            getAdelantos();

            function getAdelantos() {
                jQuery.each(data, function (i) {
                    var estado = data[i].estado;
                    var tipo_fecha = data[i].tipo_fecha;
                    var fecha_solicitud = data[i].fecha_solicitud;
                    var valor_solicitado = data[i].valor_solicitado;
                    var administracion = data[i].administracion;
                    var seguros = data[i].seguros;
                    var tecnologia = data[i].tecnologia;
                    var iva = data[i].iva;
                    var saldo = data[i].saldo;
                    var totalPagar = data[i].total_pagar;
                    var labelestado;
                    

                    labelestado = estado;

                    if(estado === 'vigente'){ 
                        clase = "vigente"; 
                        labelestado = 'a pagar';
                    }else{ 
                        clase = "pagado"; 
                        labelestado = 'pagado';
                    }

                    var singleCre = jQuery('<div>', {
                        'class': 'col-md-4 d-flex justify-content-center px-2'
                    }).append(jQuery('<div>', {
                        'class': 'credito-col ' + clase
                    }).append(jQuery('<div>', {
                        'class': 'tit-credito'
                    }).append(jQuery('<div>', {
                        'class': 'tit',
                    }).append(jQuery('<div>', {
                        'class': 'estado',
                        'html': 'Adelanto '+ labelestado
                    }),
                        jQuery('<div>', {
                            'class': 'fecha-desembolso'
                        }).append(
                            jQuery('<span>', {
                                'class': 'label',
                                'html': 'Fecha '+tipo_fecha+': '
                            }),
                            jQuery('<span>', {
                                'class': 'fecha',
                                'html': fecha_solicitud
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
                                    jQuery('<div>', { 'class': 'name', 'html': 'Valor Solicitado' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+valor_solicitado })),
                                    jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Administración ' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+administracion })),
                                    jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Seguros ' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+seguros })),
                                    jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Tecnología ' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+tecnologia })),
                                    jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'IVA ' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+iva })),
                                    jQuery('<div>', { 'class': 'item-desc' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Total a Pagar' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+totalPagar })),
                                ), 
                                jQuery('<div>', { 'class': 'info-credito' }).append(
                                    jQuery('<div>', { 'class': 'item-info' }).append(jQuery('<div>', { 'class': 'name', 'html': 'Saldo ' }), jQuery('<div>', { 'class': 'valor', 'html': "$ "+saldo }))
                                )
                            ));
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



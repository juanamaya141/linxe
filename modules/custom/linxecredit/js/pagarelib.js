

var hash = document.location.hash;
var tabSeleccion = false;
var tabHistorial = false;
var tabInfo = false;
var user_name, user_lastname, document_type, document_number,num_pagare,idregistro, celphone, email, tope, history_url, password;
var tope = 1000000;


var m = 0;
var enviandoOTP = false;


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
            document_type = parseInt(drupalSettings.linxecredit.librarypagare.document_type);
            document_number = parseInt(drupalSettings.linxecredit.librarypagare.document_number);
            num_pagare = parseInt(drupalSettings.linxecredit.librarypagare.num_pagare);
            idregistro = parseInt(drupalSettings.linxecredit.librarypagare.idregistro);
            paso3();
            
            m=m+1;
        }
    }
    
    
  }
};
})(jQuery, Drupal, drupalSettings);

////////////////////////////////////////////////////////////////////////////////////////
    function paso3(){
        jQuery('.progress-bar').css('width','66%');
        jQuery('.progress-bar').attr('aria-valuenow','66%');
        jQuery('#selected').slideDown();
        /*
        jQuery('#regresar').on('click', function(){
            jQuery('[data-step="paso3"]').fadeOut(function(){
                jQuery('[data-step="paso2"]').slideDown();
                paso2();
            });
        });*/
        jQuery('.cont-pdf').slideDown();
        jQuery('[data-toggle="tooltip"]').tooltip();
        
        //lanzarOTPConfig();
        

        jQuery('#confirmar-firma').on('click', function () {
            callOTP();
            showModal();

            function showModal() {
                jQuery('#modalFirmaContrato').modal('show');
                jQuery('#modalFirmaContrato').on('shown.bs.modal', function () {
                    
                    /*jQuery('#celular-conf').html(celphone);
                    jQuery('#correo-conf').html(email);
                    jQuery('input#btn-submit').on('click', paso4);*/
                });
                jQuery('#modalFirmaContrato').on('hidden.bs.modal', function () {
                    jQuery('header').removeClass('inBlur');
                    jQuery('main').removeClass('inBlur');
                    jQuery('footer').removeClass('inBlur');
                    //jQuery('.modal').remove();
                });
                isform();
                lanzarOTPConfig();
            }
            //e.stopImmediatePropagation();
        });
    }

    function lanzarOTPConfig(){
        jQuery('#link_lanzar_otp').on('click', function () {
            callOTP();
        }); 
    }

    function callOTP()
    {
        var dataVal = { //Fetch form data
            'idregistro' : idregistro

        };
        if(enviandoOTP==false)
        {
            enviandoOTP=true;
            jQuery.ajax({ //Process the form using $.ajax()
                type      : 'POST', //Method type
                url       : '/servicios/lanzarotpadelanto', //Your form processing file URL
                data      : dataVal, //Forms name
                dataType  : 'json',
                success   : function(response) {
                    enviandoOTP=false;
                    console.log(response);
                }
            });
        }
        
    }

    function hideLoading(){
        jQuery('#loading_image').css("display","none"); 
    }





var hash = document.location.hash;
var tabSeleccion = false;
var tabHistorial = false;
var tabInfo = false;
var user_name, user_lastname, document_type, document_number, celphone, email, tope, history_url, password;
var tope = 1000000;


var m = 0;


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

            paso3();
            isform();
            activarForm();
            m=m+1;
        }
    }
    
    
  }
};
})(jQuery, Drupal, drupalSettings);

////////////////////////////////////////////////////////////////////////////////////////
function activarForm() {
    console.log('Validaciones');
}

function paso3(){
    jQuery('.progress-bar').css('width','100%');
    jQuery('.progress-bar').attr('aria-valuenow','100%');
    jQuery('#selected').slideDown();
    console.log("paso 3 ddd");
    jQuery('#fecha_nacimiento').datepicker({
        format: 'yyyy-mm-dd',
        orientation: "bottom right",
        language: "es",
        disableTouchKeyboard: true,
        Readonly: true
    }).attr("readonly", "readonly");
    console.log("paso 3 ok");
}



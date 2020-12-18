

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

            paso4();
            
            m=m+1;
        }
    }
    
    
  }
};
})(jQuery, Drupal, drupalSettings);

////////////////////////////////////////////////////////////////////////////////////////

    function paso4(){
        jQuery('.progress-bar').css('width','100%');
        jQuery('.progress-bar').attr('aria-valuenow','100%');
        jQuery('#selected').slideDown();
    }





var hash = document.location.hash;
var tabSeleccion = false;
var tabHistorial = false;
var tabInfo = false;
var user_name, user_lastname, document_type, document_number, celphone, email, tope, history_url, password;
var tope = 1000000;
var data;

var nombresTxt="";
var valormontoaprobado="";
var showPreferenciaForm="";
var tipoproducto = "";
var m = 0;


(function ($, Drupal, drupalSettings) {
Drupal.behaviors.LinxecreditBehavior = {
  attach: function (context, settings) {
    jQuery(function cargainicial() {
        
            hash = '#info';
        
        jQuery('.nav-tabs a[href="' + hash + '"]').tab('show');
        
    });
    
    if (jQuery('body').hasClass('dashboard')) {
        if(m==0)
        {
            nombresTxt = drupalSettings.linxecredit.libraryinformacionpersonal.nombres;
            valormontoaprobado = drupalSettings.linxecredit.libraryinformacionpersonal.valormontoaprobado;
            showPreferenciaForm = drupalSettings.linxecredit.libraryinformacionpersonal.showPreferenciaForm;
            tipoproducto = drupalSettings.linxecredit.libraryinformacionpersonal.tipoproducto;
            
            jQuery('#username').html(nombresTxt);
            jQuery('#tope').html(valormontoaprobado);
            
            console.log(nombresTxt);
            console.log(valormontoaprobado);
            console.log(showPreferenciaForm);
            console.log(tipoproducto);

            //if(showPreferenciaForm){
                jQuery('#collapsePreferencias').collapse();
            //}
            editarInfo();
            if(tipoproducto=="adelanto"){
                actualizarLabelsAdelanto();
            }

            m=m+1;
        }
    }
    
    
  }
};
})(jQuery, Drupal, drupalSettings);
////////////////////////////////////////////////////////////////////////////////////////
    function editarInfo() {
        var formEdit = jQuery('form#edit-info'),
                    elementos = formEdit.find('input, textarea, select ')
        if (tabInfo == false) {
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

    function actualizarLabelsAdelanto(){
        jQuery(".tabSeleccionProducto").html("Selecciona tu Adelanto")
        jQuery(".tabSeleccionProducto").attr("href", "/dashboard/adelanto");
        jQuery(".tabMisProductos").html("Mis Adelantos")
        jQuery(".tabMisProductos").attr("href", "/dashboard/misadelantos");
    }

    

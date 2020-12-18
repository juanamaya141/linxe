jQuery( document ).ready(function() {
    jQuery('.cont-respuesta').slideDown();
    if(bemodal === true){active_modals();}
    jQuery('.modal-btn').on('click', btnmodals);
    jQuery('.btnRegresar').on('click', regresar);
    function regresar(){
        jQuery('#cont-'+currentForm).slideDown('slow', function(){
            jQuery(formulario).slideDown(function(){
                jQuery('#cont-'+currentForm).remove();
            });
        });
    }
});
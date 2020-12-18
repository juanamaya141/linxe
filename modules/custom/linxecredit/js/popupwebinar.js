
(function ($, Drupal, drupalSettings) {
Drupal.behaviors.LinxecreditBehavior = {
  attach: function (context, settings) {

    jQuery( ".cerrar-modal" ).click(function() {
        jQuery( ".modal-linxe" ).css("display", "none");
    });

    jQuery( ".show-modal-webinar" ).click(function() {
        jQuery( ".modal-linxe" ).css("display", "block");
    });
    
  }
};
})(jQuery, Drupal, drupalSettings);

    
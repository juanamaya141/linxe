var topBarH = jQuery('.topBar').outerHeight();
var headerHeight = jQuery('header').outerHeight();
var footerHeight = jQuery('footer').outerHeight();
var mainHeight = jQuery(window).height() - footerHeight;
var windowH = jQuery(window).height();
var windowW = jQuery(window).width();
var currentDevice;
var bandeLogin = false;

if (windowW > windowH) {
    currentDevice = 'desktop';
}
else {
    currentDevice = 'mobile';
}

if (jQuery('body').hasClass('home')) {
    jQuery('.container-fluid.banner').css({'padding-top':  headerHeight});
    //simulador();
}else{
    jQuery('main').css({'padding-top':  headerHeight});
    if(bandeLogin==false){
        active_show_password();
        bandeLogin=true;
    }
}

if (jQuery('body').hasClass('message_animation')) {
    console.log("entro ddd");
        jQuery('.cont-respuesta').slideDown();
        //if(bemodal === true){active_modals();}

        jQuery('.singin.modal-btn').on('click', btnLoginModal);
        jQuery('.btnRegresar').on('click', regresar);
        function regresar(){
            jQuery('#cont-'+currentForm).slideDown('slow', function(){
                jQuery(formulario).slideDown(function(){
                    jQuery('#cont-'+currentForm).remove();
                });
            });
        }
        //active_modals_login();
}


if (jQuery('body').hasClass('dashboard')) {
    /*jQuery.getScript('/themes/custom/linxe/js/dashboard.js', function () {        
        getDatos();
    });*/
}
if( jQuery('body').hasClass('nuevo_registro') ){
    isform();
}
if( jQuery('body').hasClass('contacto') ){
    isform();
}

if(currentDevice === 'desktop'){
    jQuery.getScript('/themes/custom/linxe/js/jquery.scrollable.min.js', function () {
        jQuery('.box').scrollable();
        offSetMan();
    });
}else{
    offSetManager();
    window.onscroll = function(e) {
        offSetManager();
    }
}

jQuery('main').css({'min-height': mainHeight });

function offSetMan(){
    var yOffset = 0;
    var contPos = jQuery('.scrollable-area').offset();
    var currYOffSet = parseFloat(contPos.top);
    
    if(yOffset > currYOffSet) {
       jQuery('.topBar').slideUp();
       jQuery('.cont-login').slideUp('fast', function(){
           jQuery('header').addClass('sticky-menu');
           jQuery('nav li.lising').show();
           
       });
    }
    //else if(currYOffSet == yOffset){
    else if(currYOffSet >= -300){
        jQuery('.topBar').slideDown('fast');
        jQuery('.cont-login').show();
        jQuery('nav li.lising').hide();
        
        jQuery('header').removeClass('sticky-menu');
    }
}

function offSetManager(){
    var yOffset = 0;
    var currYOffSet = window.pageYOffset;
    if(yOffset < currYOffSet) {
        jQuery('.topBar').slideUp();
       jQuery('.cont-login').slideUp('fast', function(){
           jQuery('header').addClass('sticky-menu');
           jQuery('nav li.lising').show();
           
       });
    }
    else if(currYOffSet == yOffset){
        jQuery('.topBar').slideDown('fast');
        jQuery('.cont-login').show();
        jQuery('nav li.lising').hide();
        
        jQuery('header').removeClass('sticky-menu');
    }
}

jQuery('#collapsenav').on('show.bs.collapse', function () {
    jQuery('body').addClass('modal-open');
    jQuery('header').after('<div class="layer"></div>');
    jQuery('.btnMenu i').attr('class', 'fas fa-times');
});
jQuery('#collapsenav').on('hidden.bs.collapse', function () {
    jQuery('body').removeClass('modal-open');
    jQuery('.btnMenu i').attr('class', 'fas fa-bars');
    jQuery('.layer').remove();
});

//MODALS

jQuery('.singin.modal-btn').on('click', btnLoginModal);




function btnLoginModal(e){
    e.stopPropagation();
    e.preventDefault();

    //console.log(jQuery(this));
    var destino = jQuery(this).attr('data-modal');

    jQuery('#loginformlayer').modal('show');
    active_modals_login();
    //
    if(bandeLogin==false){
        active_show_password();
        bandeLogin=true;
    }
}

function active_show_password(){
    console.log("active show pass");
    jQuery('.btn-showp').on('click', function(){
        console.log("ssds");
        if( jQuery(this).find("span").hasClass('fa-eye') ){
            jQuery(this).parent().find("input.password").attr('type','text');
            jQuery(this).find("span").attr('class','fa fa-eye-slash icon');
        }else{
            jQuery(this).parent().find("input.password").attr('type','password');
            jQuery(this).find("span").attr('class','fa fa-eye icon');
        }
        
    }); 
}

function btnmodals() {
    console.log(jQuery(this));
    var destino = jQuery(this).attr('data-modal');

    jQuery('body').append('<div class="modal fade" role="dialog"></div>');
    carga();
    function carga() {
        jQuery('.modal').load('modals/' + destino + '.html', function () {
            jQuery('.modal').modal('show');
            active_modals();
        });
    }
}

function active_modals_login() {
    jQuery('header').addClass('inBlur');
    jQuery('main').addClass('inBlur');
    jQuery('footer').addClass('inBlur');
    jQuery('#loginformlayer').on('hidden.bs.modal', function () {
        jQuery('header').removeClass('inBlur');
        jQuery('main').removeClass('inBlur');
        jQuery('footer').removeClass('inBlur');
        //jQuery('.modal').remove();
    });
    jQuery('#ayudaIngresar').on('click', function () {
        jQuery('#loginformlayer').modal('hide');
        jQuery('#rememberformlayer').modal('show');
         active_modals();
    });
    isform();
}

function active_modals() {
    jQuery('header').addClass('inBlur');
    jQuery('main').addClass('inBlur');
    jQuery('footer').addClass('inBlur');
    jQuery('#rememberformlayer').on('hidden.bs.modal', function () {
        jQuery('header').removeClass('inBlur');
        jQuery('main').removeClass('inBlur');
        jQuery('footer').removeClass('inBlur');
        //jQuery('.modal').remove();
    });
    
    isform();
}

function isform(){
    if (jQuery('.formularioin').length) {
        jQuery.getScript('/themes/custom/linxe/js/forms.js', function () {
            console.log('llama form.js');
        });
    }
    if (jQuery('.webform-submission-form').length) {
        jQuery.getScript('/themes/custom/linxe/js/forms.js', function () {
            console.log('llama form.js');
        });
    }
    
}


        

///////////////Format numbers
var formatNumber = {
    separador: ".", // separador para los miles
    sepDecimal: ',', // separador para los decimales
    sepMillon: "'", // separador para millon
    formatear: function (num) {
        num += '';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft + splitRight;
    },
    new: function (num, simbol) {
        this.simbol = simbol || '';
        return this.formatear(num);
    }
}; 


/* finalizar sesion por tiempo de inactividad */
var idleTime = 0;
jQuery(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    //Zero the idle timer on mouse movement.
    jQuery(this).mousemove(function (e) {
        idleTime = 0;
    });
    jQuery(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 10) { // 10 minutes de inactividad
        //alert("Ha superado el tiempo de inactividad, su sesión será cerrada");
        document.location.href="/cerrarsesion"
    }
}
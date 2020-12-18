var topBarH = $('.topBar').outerHeight();
var headerHeight = $('header').outerHeight();
var footerHeight = $('footer').outerHeight();
var mainHeight = $(window).height() - footerHeight;
var windowH = $(window).height();
var windowW = $(window).width();
var currentDevice;

if (windowW > windowH) {
    currentDevice = 'desktop';
}
else {
    currentDevice = 'mobile';
}

if ($('body').hasClass('home')) {
    $('.container-fluid.banner').css({'padding-top':  headerHeight});
    simulador();
}else{
    $('main').css({'padding-top':  headerHeight});
}
if ($('body').hasClass('dashboard')) {
    $.getScript('js/dashboard.js', function () {        
        getDatos();
    });
}
if( $('body').hasClass('nuevo_registro') ){
    isform();
}
if( $('body').hasClass('contacto') ){
    isform();
}
if(currentDevice === 'desktop'){
    $.getScript('js/jquery.scrollable.js', function () {
        $('.box').scrollable();
        offSetMan();
    });
}else{
    offSetManager();
    window.onscroll = function(e) {
        offSetManager();
    }
}

$('main').css({'min-height': mainHeight });

function offSetMan(){
    var yOffset = 0;
    var contPos = $('.scrollable-area').offset();
    var currYOffSet = parseFloat(contPos.top);
    
    if(yOffset > currYOffSet) {
       $('.topBar').slideUp();
       $('.cont-login').slideUp('fast', function(){
           $('header').addClass('sticky-menu');
           $('nav li.lising').slideDown();
       });
    }
    //else if(currYOffSet == yOffset){
    else if(currYOffSet >= -300){
        $('.topBar').slideDown('fast');
        $('.cont-login').show();
        $('nav li.lising').hide();
        $('header').removeClass('sticky-menu');
    }
}

function offSetManager(){
    var yOffset = 0;
    var currYOffSet = window.pageYOffset;
    if(yOffset < currYOffSet) {
        $('.topBar').slideUp();
       $('.cont-login').slideUp('fast', function(){
           $('header').addClass('sticky-menu');
           $('nav li.lising').slideDown();
       });
    }
    else if(currYOffSet == yOffset){
        $('.topBar').slideDown('fast');
        $('.cont-login').show();
        $('nav li.lising').hide();
        $('header').removeClass('sticky-menu');
    }
}

$('#collapsenav').on('show.bs.collapse', function () {
    $('body').addClass('modal-open');
    $('header').after('<div class="layer"></div>');
    $('.btnMenu i').attr('class', 'fas fa-times');
});
$('#collapsenav').on('hidden.bs.collapse', function () {
    $('body').removeClass('modal-open');
    $('.btnMenu i').attr('class', 'fas fa-bars');
    $('.layer').remove();
});

//MODALS
$('.modal-btn').on('click', btnmodals);
function btnmodals() {
    console.log($(this));
    var destino = $(this).attr('data-modal');

    $('body').append('<div class="modal fade" role="dialog"></div>');
    carga();
    function carga() {
        $('.modal').load('modals/' + destino + '.html', function () {
            $('.modal').modal('show');
            active_modals();
        });
    }
}

function active_modals() {
    $('header').addClass('inBlur');
    $('main').addClass('inBlur');
    $('footer').addClass('inBlur');
    $('.modal').on('hidden.bs.modal', function () {
        $('header').removeClass('inBlur');
        $('main').removeClass('inBlur');
        $('footer').removeClass('inBlur');
        $('.modal').remove();
    });
    $('#ayudaIngresar').on('click', function () {
        $('.modal').empty();
        $('.modal').load('modals/ayuda-form.html', function () {
            $('.modal').modal('show');
            active_modals();
        });
    });
    $('#newlogin').on('click', function () {
        $('.modal').empty();
        $('.modal').load('modals/login-form.html', function () {
            $('.modal').modal('show');
            active_modals();
        });
    });
    isform();
}

function isform(){
    if ($('.formularioin').length) {
        $.getScript('js/forms.js', function () {
            console.log('llama form.js');
        });
    }
}

//////////////////SIMULADORES
    function simulador() {
        var cantidad_min = 500000;
        var cantidad_max = 1200000;
        var meses_min = 6;
        var meses_max = 10;
        var tasa = 2.20;
        var seguro = 0.30;
        var cuota;

        $(function () {
            var calc1 = cantidad_min + ((cantidad_max - cantidad_min) / 2);
            var calc2 = meses_min + ((meses_max - meses_min) / 2);
            $('#cantidad').val(calc1);
            $('#r_min_cantidad').html(formatNumber.new(cantidad_min, "$"));
            $('#r_max_cantidad').html(formatNumber.new(cantidad_max, "$"));
            $('#plazo').val(calc2);
            $('#r_min_meses').html(meses_min + ' MESES');
            $('#r_max_meses').html(meses_max + ' MESES');
            calcular();
        });

        function setInputs() {
            $('#catidadVal').html(formatNumber.new($('#cantidad').val()));
            $('#plazoVal').html($('#plazo').val());
            $('#cuotaVal').html(formatNumber.new($('#cuota').val()));
        }

        function calcular() {
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
            var plazo = -($('#plazo').val());
            cuota = ((cantidad / 100) * tasa) / (1 - ((1 * 100 + tasa) / 100) ** plazo) + ((cantidad / 100) * seguro);
            var newcuota = parseInt(cuota);
            $('#cuota').val(newcuota);
            setInputs();
        }
        $('#plazo').on('change', calcular);
        $('.btnMas').on('click', changeCantidad);
        $('.btnMenos').on('click', changeCantidad);

        function changeCantidad(e) {
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
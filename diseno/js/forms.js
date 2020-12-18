$('form').each(function () {
    var formulario = $(this),
        elementos = formulario.find('input, textarea, select ');
    var datos = [];

    //SET INPUTS
    $('#f-ingreso').change(function () { $('#nf-ingreso').val($('#f-ingreso').val()); });

    //-----SET REQUIREDS
    $(formulario).attr('novalidate', 'novalidate');
    $(formulario).addClass('needs-validation');
    for (var i = 0; i < elementos.length; i++) {
        if( elementos[i].name === 'usuario' || elementos[i].name === 'user-password'|| elementos[i].name ==='nombre'||elementos[i].name ==='apellido'||elementos[i].name ==='tipo_doc'||elementos[i].name ==='documento'||elementos[i].name ==='empresa'||elementos[i].name ==='celular'||elementos[i].name ==='correo'||elementos[i].name ==='destino'||elementos[i].name ==='f-ingreso'||elementos[i].name ==='tipo-contrato'||elementos[i].name ==='descuento'||elementos[i].name ==='cargo'||elementos[i].name ==='terminos'||elementos[i].name ==='codigo') {
            $(elementos[i]).attr('required', true);
        }
    }

    //////////////////////////////////////////VALIDACIÓN
    var validarInputs = function () {
        for (var i = 0; i < elementos.length; i++) {
            if( ($(elementos[i]).attr('required') == 'required') ){
                if( elementos[i].checkValidity() === false ){
                    console.log('El campo ' + $(elementos[i]).attr('name') + ' es incorrecto');
                    $('#errorMsj').html($(elementos[i]).attr('data-msj'));
                    $('#errorMsj').slideDown();
                    $(elementos[i]).addClass('error');
                    return false;
                }
                else{
                    $(elementos[i]).removeClass('error');
                }
            }
        }
        return true;
    };

    var enviar = function (e) {
        if (!validarInputs()) {
            console.log('Falto validar los Input');
            e.preventDefault();
        } else {

            var jsonData = $(this).serializeArray()
            .reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
            console.log(jsonData);

            switch ($(formulario).attr('name')){
                case 'form_login':
                    window.location.href = 'dashboard.html';
                break;
                case 'form_help':
                    respuestaForm('reestablecer', true);
                break;
                case 'form_registro':
                    respuestaForm('newregistro', false);
                break;
                case 'edit_info':
                    respuestaForm('editinfo', false);
                break;
                case 'set-preferencias':
                    respuestaForm('fpreferencias', false);
                break;
                case 'form_contacto_personas':
                    respuestaForm('mcontactop', false);
                break;
                case 'form_contacto_empresas':
                    respuestaForm('mcontactoe', false);
                break;
                default:
                console.log($(formulario).attr('name'));
            }
            e.preventDefault();
        }
    };

    var focusInput = function () {
        $(this).siblings('label').attr('class', 'label active');
        $(this).removeClass('error');
        $('#errorMsj').slideUp('fast');
        $('#errorMsj').empty();
    };
    var blurInput = function () {
        if ($(this).val() <= 0) {
            $(this).siblings('label').attr('class', 'label');
            $(this).addClass('error');
        }
        if( ($(this).attr('name') === 'documento') && ( $(this).val().length < 8 ) ){
            $(this).addClass('error');
        }
    };
    var valNum = function () {
        var newVal = $(this).val();
        $(this).val( newVal.replace(/[^0-9\.]/g,'') );
    };
    var checkTerminos = function () {
        if ($(this).is(':checked')) {
            $(this).val('check');
        } else {
            $(this).val('');
        }
    };
    var checkPass = function () {
        if ( this.name === 'current-pass' ) {
            if( !$(this).val() && !$('#new-pass').val() && !$('#new-pass').val() ){
                $(this).val('Escribir Contraseña');
                $(this).removeClass('error');
                $(this).siblings('label').addClass('active');
                $(this).attr('required', false);
            }
            else if ($(this).val()) {
                validarCurrent();
                $(this).attr('required', true);
            }
        }
        else if( (this.name === 'new-pass') || (this.name === 'conf-pass') ){
            if ($('#new-pass').val() === $('#conf-pass').val()) {
                $('#new-pass').removeClass('error');
                $('#conf-pass').removeClass('error');
                if ($('#conf-pass').val() && $('#new-pass').val()) {
                    validarCurrent();
                    $(this).attr('required', true);
                }
            }
            else if ($('#conf-pass').val() != $('#new-pass').val()) {
                if (!$('#new-pass').val()) {
                    $('#new-pass').addClass('error');
                }
                else if ($('#conf-pass').val() && $('#new-pass').val()) {
                    $('#errorMsj').html('Las contraseñas no coinciden');
                    $('#errorMsj').slideDown();
                    $('#conf-pass').addClass('error');
                }
            }
        }
    };
    function validarCurrent() {
        if ($('#current-pass').val() === password) {
            alert('OK');
        }
        else {
            $('#errorMsj').html('Escribe tu contraseña actual');
            $('#errorMsj').slideDown();
            $('#current-pass').addClass('error');
        }
    }

    // --- Eventos ---
    $(formulario).on('submit', enviar);

    for (var i = 0; i < elementos.length; i++) {
        if( $(elementos[i]).val() ){ $(elementos[i]).siblings('label').attr('class', 'label active');}
        if( (elementos[i].type != 'password') && (elementos[i].name != 'user-password') ){
            $(elementos[i]).on('blur', blurInput);
        }
        else if ((elementos[i].type === 'password') && (elementos[i].name != 'user-password')) {
            $(elementos[i]).on('focus', function () {
                $(this).val('');
            });
            $(elementos[i]).on('blur', checkPass);
        }
        $(elementos[i]).on('focus', focusInput);
        $(elementos[i]).on('select', focusInput);
        
       if( $(elementos[i]).attr('type') === 'number' || $(elementos[i]).attr('type') === 'tel' ){
            $(elementos[i]).keyup(valNum);
        }
        if ($(elementos[i]).attr('id') == 'terminos') {
            $(elementos[i]).on('click', checkTerminos);
        }
    }

    
    var respuestaForm = function(currentForm, bemodal){
        console.log($(formulario).attr('name'));
        $(formulario).parent('.cont-form').append('<div class="cont-respuesta" id="cont-'+currentForm+'"></div>');
        
        $('.other').slideUp();
        $(formulario).slideUp('slow',  function(){
            $('#cont-'+currentForm).load('respuestaForms.html #'+currentForm , function(){
                $('#cont-'+currentForm).slideDown();
                if(bemodal === true){active_modals();}
                $('.modal-btn').on('click', btnmodals);
                $('.btnRegresar').on('click', regresar);
                function regresar(){
                    $('#cont-'+currentForm).slideDown('slow', function(){
                        $(formulario).slideDown(function(){
                            $('#cont-'+currentForm).remove();
                        });
                    });
                }
            });
        });
    }
    
    $('.btn-showp').on('click', function(){
        if( $('.btn-showp span').hasClass('fa-eye') ){
            $('#password').attr('type','text');
            $('.btn-showp span').attr('class','fa fa-eye-slash icon');
        }else{
            $('#password').attr('type','password');
            $('.btn-showp span').attr('class','fa fa-eye icon');
        }
        
    });


    
});


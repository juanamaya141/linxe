var enviado = false;
jQuery('form').each(function () {
    var formulario = jQuery(this),
        elementos = formulario.find('input, textarea, select ');
    var datos = [];

    //SET INPUTS
    
    //-----SET REQUIREDS
    jQuery(formulario).attr('novalidate', 'novalidate');
    jQuery(formulario).addClass('needs-validation');
    for (var i = 0; i < elementos.length; i++) {
        if( elementos[i].name === 'usuario' || elementos[i].name === 'user-password'|| elementos[i].name ==='nombre'||elementos[i].name ==='apellido'||elementos[i].name ==='tipo_doc'||elementos[i].name ==='documento'||elementos[i].name ==='empresa'||elementos[i].name ==='celular'||elementos[i].name ==='correo'||elementos[i].name ==='destino'||elementos[i].name ==='fingreso'||elementos[i].name ==='tipo-contrato'||elementos[i].name ==='descuento'||/*elementos[i].name ==='cargo'||*/elementos[i].name ==='terminos'||elementos[i].name ==='codigo') {
            jQuery(elementos[i]).attr('required', true);
        }
    }

    //////////////////////////////////////////VALIDACIÓN
    var validarInputs = function () {
        for (var i = 0; i < elementos.length; i++) {
            if( (jQuery(elementos[i]).attr('required') == 'required') ){
                if( elementos[i].checkValidity() === false ){
                    console.log('El campo ' + jQuery(elementos[i]).attr('name') + ' es incorrecto');
                    jQuery('.errorMsj').html(jQuery(elementos[i]).attr('data-msj'));
                    jQuery('.errorMsj').slideDown();
                    jQuery(elementos[i]).addClass('error');
                    return false;
                }
                else{
                    jQuery(elementos[i]).removeClass('error');
                }
            }
            if(jQuery(elementos[i]).attr('name') === "password2")
            {
                if (jQuery('#edit-password2').val() != jQuery('#edit-password').val()) {
                    console.log("entro 3");
                    jQuery('.errorMsj').html('Las contraseñas no coinciden');
                    jQuery('.errorMsj').slideDown();
                    jQuery('#password2').addClass('error');
                    return false;
                }
            }

        }
        //disabled submit button
        jQuery('input[type="submit"]').attr("disabled", true);

        enviado = true;
        
        return true;
    };

    var enviar = function (e) {
        if(enviado == false)
        {
            if (!validarInputs()) {
                console.log('Falto validar los Input');
                e.preventDefault();
            } else {

                var jsonData = jQuery(this).serializeArray()
                .reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
                console.log(jsonData);

                jQuery(formulario).submit();

                switch (jQuery(formulario).attr('name')){
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
                    console.log(jQuery(formulario).attr('name'));
                }
                e.preventDefault();
            }
        }
        
    };

    var focusInput = function () {
        jQuery(this).siblings('label').attr('class', 'label active');
        jQuery(this).removeClass('error');
        jQuery('.errorMsj').slideUp('fast');
        jQuery('.errorMsj').empty();
    };
    var blurInput = function () {
        if (jQuery(this).val() <= 0) {
            jQuery(this).siblings('label').attr('class', 'label');
            jQuery(this).addClass('error');
        }
        if( (jQuery(this).attr('name') === 'documento') && ( jQuery(this).val().length < 8 ) ){
            jQuery(this).addClass('error');
        }
    };
    var valNum = function () {
        var newVal = jQuery(this).val();
        jQuery(this).val( newVal.replace(/[^0-9\.]/g,'') );
    };
    var checkTerminos = function () {
        if (jQuery(this).is(':checked')) {
            jQuery(this).val('check');
        } else {
            jQuery(this).val('');
        }
    };
    var checkPass = function () {
        console.log("entro checkPass");
        
        if( (this.name === 'password') || (this.name === 'password2') ){
            if (jQuery('#edit-password').val() === jQuery('#edit-password2').val()) {
                console.log("entro 1");
                jQuery('#edit-password').removeClass('error');
                jQuery('#edit-password2').removeClass('error');
                if (jQuery('#edit-password2').val() && jQuery('#edit-password').val()) {
                    console.log("entro 2");
                    jQuery(this).attr('required', true);
                }
            }
            if (jQuery('#edit-password2').val() != jQuery('#edit-password').val()) {
                console.log("entro 3");
                jQuery('.errorMsj').html('Las contraseñas no coinciden');
                jQuery('.errorMsj').slideDown();
                jQuery('#password2').addClass('error');
            }
        }
    };
    function validarCurrent() {
        if (jQuery('#password_old').val() === password) {
            //alert('OK');
            console.log("ok");
        }
        else {
            jQuery('.errorMsj').html('Escribe tu contraseña actual');
            jQuery('.errorMsj').slideDown();
            jQuery('#password_old').addClass('error');
        }
    }

    // --- Eventos ---
    jQuery(formulario).on('submit', enviar);

    for (var i = 0; i < elementos.length; i++) {
        if( jQuery(elementos[i]).val() ){ jQuery(elementos[i]).siblings('label').attr('class', 'label active');}
        if( (elementos[i].type != 'password') && (elementos[i].name == 'password') ){
            jQuery(elementos[i]).on('blur', blurInput);
        }
        if ((elementos[i].type === 'password') && (elementos[i].name == 'password2')) {
            jQuery(elementos[i]).on('focus', function () {
                jQuery(this).val('');
            });
            jQuery(elementos[i]).on('blur', checkPass);
        }
        jQuery(elementos[i]).on('focus', focusInput);
        jQuery(elementos[i]).on('select', focusInput);
        
       if( jQuery(elementos[i]).attr('type') === 'number' || jQuery(elementos[i]).attr('type') === 'tel' ){
            jQuery(elementos[i]).keyup(valNum);
        }
        if (jQuery(elementos[i]).attr('id') == 'terminos') {
            jQuery(elementos[i]).on('click', checkTerminos);
        }
        if (jQuery(elementos[i]).attr('id') == 'edit-terminos') {
            jQuery(elementos[i]).on('click', checkTerminos);
        }
    }

    
    var respuestaForm = function(currentForm, bemodal){
        console.log(jQuery(formulario).attr('name'));
        jQuery(formulario).parent('.cont-form').append('<div class="cont-respuesta" id="cont-'+currentForm+'"></div>');
        
        jQuery('.other').slideUp();
        jQuery(formulario).slideUp('slow',  function(){
            jQuery('#cont-'+currentForm).load('respuestaForms.html #'+currentForm , function(){
                jQuery('#cont-'+currentForm).slideDown();
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
        });
    }
    
    


    
});


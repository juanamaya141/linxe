// javascript inversionistas
var gl_cantidad_min = 500000;
var gl_cantidad_max = 1200000;
var gl_meses_min = 6;
var gl_meses_max = 10;
var gl_tasa = 2.20;
var gl_seguro = 0.30;
var gl_cuota = 0;

var i = 0;
var islogin = false;
var simuAdelantoBande = false;

var valor_min_an = 0;
var valor_med_default_an = 0;
var valor_max_an = 0;
var valor_adelanto_default_an = 0;
var adelantonomina_rangos = 0;
var adelantonomina_montos_adelanto = 0;
var adelantonomina_montos_salario = 0;

var arrayRangos = [];
var arrayAdelantos = [];
var arraySalarios = [];
var arrayJsonTiposProducto = [];

(function($, Drupal, drupalSettings) {
    Drupal.behaviors.LinxecreditBehavior = {
        attach: function(context, settings) {

            if (drupalSettings.linxecredit !== undefined) {
                if (drupalSettings.linxecredit.linxecreditstyles.islogin !== null) {
                    islogin = drupalSettings.linxecredit.linxecreditstyles.islogin;
                    activarBotones();
                }
                if (jQuery('body').hasClass('home')) {
                    // Simulador Libranza;

                    gl_cantidad_min = parseInt(drupalSettings.linxecredit.linxecreditstyles.gl_cantidad_min);
                    gl_cantidad_max = parseInt(drupalSettings.linxecredit.linxecreditstyles.gl_cantidad_max);
                    gl_meses_min = parseInt(drupalSettings.linxecredit.linxecreditstyles.gl_meses_min);
                    gl_meses_max = parseInt(drupalSettings.linxecredit.linxecreditstyles.gl_meses_max);
                    gl_tasa = drupalSettings.linxecredit.linxecreditstyles.gl_tasa;
                    gl_seguro = drupalSettings.linxecredit.linxecreditstyles.gl_seguro;
                    gl_cuota = drupalSettings.linxecredit.linxecreditstyles.gl_cuota;
                    simulador();

                    // Simulador Adelanto;

                    valor_min_an = parseInt(drupalSettings.linxecredit.linxecreditstyles.valor_min_an);
                    valor_med_default_an = parseInt(drupalSettings.linxecredit.linxecreditstyles.valor_med_default_an);
                    valor_max_an = parseInt(drupalSettings.linxecredit.linxecreditstyles.valor_max_an);
                    valor_adelanto_default_an = parseInt(drupalSettings.linxecredit.linxecreditstyles.valor_adelanto_default_an);
                    adelantonomina_rangos = drupalSettings.linxecredit.linxecreditstyles.adelantonomina_rangos;
                    adelantonomina_montos_adelanto = drupalSettings.linxecredit.linxecreditstyles.adelantonomina_montos_adelanto;
                    adelantonomina_montos_salario = drupalSettings.linxecredit.linxecreditstyles.adelantonomina_montos_salario;
                    simuladorAdelanto();


                }

            }

            if (i == 0) {

                /*
                $('#feex').datepicker({
                    dateFormat: 'yy-mm-dd'
                }).attr('readonly','readonly');
                $('#fingreso').datepicker({
                    dateFormat: 'yy-mm-dd'
                }).attr('readonly','readonly');
                */
                $.fn.datepicker.dates['es'] = {
                    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                    daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                    monthsShort: ["Ene", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                    today: "Hoy",
                    clear: "Borrar",
                    format: "mm/dd/yyyy",
                    titleFormat: "MM yyyy",
                    /* Leverages same syntax as 'format' */
                    weekStart: 0
                };
                $('#feex').datepicker({
                    format: 'yyyy-mm-dd',
                    orientation: "bottom right",
                    language: "es",
                    disableTouchKeyboard: true,
                    Readonly: true
                }).attr("readonly", "readonly");
                $('#fingreso').datepicker({
                    format: 'yyyy-mm-dd',
                    orientation: "bottom right",
                    language: "es",
                    disableTouchKeyboard: true,
                    Readonly: true
                }).attr("readonly", "readonly");

                //focus
                $('.formularioin input, .formularioin select').on('focus', focusInput);
                $('.formularioin input, .formularioin select').on('select', focusInput);

                $('.formularioin input, .formularioin select').on('blur', blurInput);

            }
            /*jQuery('#edit-destino').select2({
              theme: "classic",
              minimumResultsForSearch: -1
            });*/


            stylingDropdowns();
            dropDownRegistro();




        }
    };
})(jQuery, Drupal, drupalSettings);


function focusInput() {
    jQuery(this).siblings('label').attr('class', 'label active');
    jQuery(this).removeClass('error');
    jQuery('.errorMsj').slideUp('fast');
    jQuery('.errorMsj').empty();
};

function blurInput() {
    if (jQuery(this).val() <= 0) {
        jQuery(this).siblings('label').attr('class', 'label');
        jQuery(this).addClass('error');
    }
    if ((jQuery(this).attr('name') === 'documento') && (jQuery(this).val().length < 6)) {
        jQuery(this).addClass('error');
    }
};


function activarBotones() {

    if (islogin == true) {
        jQuery(".without-login").hide();
        jQuery(".with-login").show();
    } else {
        jQuery(".with-login").hide();
        jQuery(".without-login").show();


    }
    jQuery(document).ready(function() {

        jQuery('.singin.modal-btn').on('click', btnLoginModal);

    });


}

function stylingDropdowns() {
    if (i == 0) {
        jQuery('label[for="edit-afp-adelanto"]').addClass('active');
        jQuery('label[for="edit-eps-adelanto"]').addClass('active');
        jQuery('label[for="edit-empresa"]').addClass('active');

        // Iterate over each select element
        jQuery('#edit-tipo-doc,#edit-actividadeconomica,#edit-tipoproducto,#edit-destino,#edit-tipocontrato,#edit-rango-salario-adelanto').each(function() {

            // Cache the number of options
            var $this = jQuery(this),
                numberOfOptions = jQuery(this).children('option').length;

            // Hides the select element
            $this.addClass('s-hidden');

            // Wrap the select element in a div
            $this.wrap('<div class="select"></div>');

            // Insert a styled div to sit over the top of the hidden select element
            $this.after('<div class="styledSelect"></div>');

            // Cache the styled div
            var $styledSelect = $this.next('div.styledSelect');

            // Show the first select option in the styled div
            $styledSelect.text($this.children('option').eq(0).text());

            // Insert an unordered list after the styled div and also cache the list
            var $list = jQuery('<ul />', {
                'class': 'options'
            }).insertAfter($styledSelect);

            // Insert a list item into the unordered list for each select option
            for (var i = 0; i < numberOfOptions; i++) {
                jQuery('<li />', {
                    text: $this.children('option').eq(i).text(),
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
            }

            // Cache the list items
            var $listItems = $list.children('li');

            // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
            $styledSelect.click(function(e) {
                e.stopPropagation();
                jQuery(this).parent().siblings('label').attr('class', 'label active');

                jQuery('div.styledSelect.active').each(function() {
                    jQuery(this).removeClass('active').next('ul.options').hide();
                });
                jQuery(this).toggleClass('active').next('ul.options').toggle();
            });

            // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
            // Updates the select element to have the value of the equivalent option
            $listItems.click(function(e) {
                e.stopPropagation();
                $styledSelect.text(jQuery(this).text()).removeClass('active');
                $this.val(jQuery(this).attr('rel'));
                $list.hide();
                //alert($this.val()); // Uncomment this for demonstration! 
                $this.trigger("change");
            });

            // Hides the unordered list when clicking outside of it
            jQuery(document).click(function() {
                $styledSelect.removeClass('active');
                $list.hide();
            });

        });
    }

}

function dropDownRegistro() {
    if (i == 0) {

        jQuery(function() {
            jQuery("#edit-actividadeconomica").change(function() {

                var actividadCurrent = jQuery(this).children("option:selected").val();

                jQuery.ajax({
                    url: '/servicios/getempresabyactividad/' + actividadCurrent,
                    type: "POST",
                    dataType: "json",
                    success: function(data) {

                        var json = jQuery.parseJSON(data);
                        //console.log(json);
                        loadEmpresas(json.empresas);
                    },
                    error: function() {
                        console.log('There was some error performing the AJAX call!');
                    }
                });

            });

            jQuery("#edit-empresa").change(function() {

                var idemp = jQuery(this).children("option:selected").val();
                var res = "";
                var tipoProd = "";
                if (idemp == "Otra")
                    tipoProd = "adelanto"
                else {
                    res = idemp.split("|");
                    tipoProd = res[2]; //tipo de producto
                }

                console.log(tipoProd);

                if (tipoProd == "libranza") {

                    jQuery('#edit-cargo').attr('required', 'required');
                    jQuery('#edit-tipocontrato').attr('required', 'required');

                    jQuery.ajax({
                        url: '/servicios/cargosytiposcontrato/' + res[0],
                        success: function(data) {

                            loadTiposContrato(data.TiposContrato);
                            loadCargos(data.Cargos);
                        },
                        error: function() {
                            console.log('There was some error performing the AJAX call!');
                        }
                    });
                } else if (tipoProd == "adelanto" || idemp == "Otra") {
                    var $list = jQuery("#edit-tipocontrato").parent().children('ul.options');
                    $list.html("");
                    var $styledSelect = jQuery("#edit-tipocontrato").parent().children('.styledSelect');
                    $styledSelect.html("");

                    jQuery("#edit-tipocontrato").empty();
                    jQuery("#edit-tipocontrato").append('<option value=""></option>');

                    var $list = jQuery("#edit-cargo").parent().children('ul.options');
                    $list.html("");
                    var $styledSelect = jQuery("#edit-cargo").parent().children('.styledSelect');
                    $styledSelect.html("");

                    console.log("not required");
                    jQuery('#edit-cargo').removeAttr('required');
                    jQuery('#edit-tipocontrato').removeAttr('required');

                    jQuery("#edit-cargo").empty();
                    jQuery("#edit-cargo").append('<option value=""></option>');

                }

                changeTipoProducto(tipoProd);


            });
        });
        jQuery('#fingreso').change(function() { jQuery('#nf-ingreso').val(jQuery('#fingreso').val()); });
        jQuery('#feex').change(function() { jQuery('#nf-expedicion').val(jQuery('#feex').val()); });


        jQuery('#edit-tipoproducto').change(function() {
            switch (jQuery(this).val()) {
                case "libranza":
                    enDisableOtro(false)
                    enDisableAdelanto(false);
                    enDisableLibranza(true);
                    break;
                case "adelanto":
                    enDisableOtro(false)
                    enDisableAdelanto(true);
                    enDisableLibranza(false);
                    break;
                default:
                    enDisableOtro(true)
                    enDisableAdelanto(false);
                    enDisableLibranza(false);
            }
        });

    }
    i++;



}

function enDisableOtro(bandeEnable) {
    if (bandeEnable) {
        jQuery("#layerOtro").show();
        //add fields
        jQuery("#edit-nombre-empresa").attr("required", "required");
        jQuery("#edit-nombre-contacto-rh").attr("required", "required");
        jQuery("#edit-email-contacto-rh").attr("required", "required");
        jQuery("#edit-telefono-contacto-rh").attr("required", "required");
    } else {
        jQuery("#layerOtro").hide();
        //remove fields
        jQuery("#edit-nombre-empresa").removeAttr("required");
        jQuery("#edit-nombre-contacto-rh").removeAttr("required");
        jQuery("#edit-email-contacto-rh").removeAttr("required");
        jQuery("#edit-telefono-contacto-rh").removeAttr("required");
    }
}

function enDisableAdelanto(bandeEnable) {
    if (bandeEnable) {
        jQuery("#layerAdelanto").show();
        //add fields
        jQuery("#edit-eps-adelanto").attr("required", "required");
        jQuery("#edit-afp-adelanto").attr("required", "required");
        //jQuery("#edit-razon-social-empleador-adelanto").attr("required","required");
        jQuery("#edit-rango-salario-adelanto").attr("required", "required");

    } else {
        jQuery("#layerAdelanto").hide();
        //remove fields
        jQuery("#edit-eps-adelanto").removeAttr("required");
        jQuery("#edit-afp-adelanto").removeAttr("required");
        //jQuery("#edit-razon-social-empleador-adelanto").removeAttr("required");
        jQuery("#edit-rango-salario-adelanto").removeAttr("required");
    }
}

function enDisableLibranza(bandeEnable) {
    if (bandeEnable) {
        jQuery("#layerLibranza").show();
        //add fields
        jQuery("#edit-empresa").attr("required", "required");
        jQuery("#edit-fingreso").attr("required", "required");
        jQuery("#edit-tipocontrato").attr("required", "required");
        jQuery("#edit-cargo").attr("required", "required");

    } else {
        jQuery("#layerLibranza").hide();
        //remove fields
        jQuery("#edit-empresa").removeAttr("required");
        jQuery("#edit-fingreso").removeAttr("required");
        jQuery("#edit-tipocontrato").removeAttr("required");
        jQuery("#edit-cargo").removeAttr("required");
    }
}

function changeTipoProducto(tipoProducto) {
    jQuery("#edit-tipoproducto").empty();
    jQuery("#edit-tipoproducto").append('<option value=""></option>');
    var dataTipoProd = [];
    switch (tipoProducto) {
        case "adelanto":
            dataTipoProd["adelanto"] = "Adelanto de Salario";
            //dataTipoProd["otro"] = "Otro";
            break;
        case "libranza":
            dataTipoProd["libranza"] = "Crédito Libranza";
            break;

        default:
            dataTipoProd["adelanto"] = "Adelanto de Salario";

    }
    console.log(dataTipoProd);
    for (tipo in dataTipoProd) {
        jQuery("#edit-tipoproducto").append('<option value="' + tipo + '">' + dataTipoProd[tipo] + '</option>');
    }


    var $this = jQuery("#edit-tipoproducto"),
        numberOfOptions = jQuery("#edit-tipoproducto").children('option').length;
    // Insert an unordered list after the styled div and also cache the list
    var $list = jQuery("#edit-tipoproducto").parent().children('ul.options');
    $list.html("");
    var $styledSelect = jQuery("#edit-tipoproducto").parent().children('.styledSelect');
    $styledSelect.html("");

    // Insert a list item into the unordered list for each select option
    for (var i = 0; i < numberOfOptions; i++) {
        jQuery('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    // Cache the list items
    var $listItems = $list.children('li');

    var $styledSelect = $this.next('div.styledSelect');
    // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
    $styledSelect.click(function(e) {
        e.stopPropagation();
        jQuery(this).parent().siblings('label').attr('class', 'label active');

        jQuery('div.styledSelect.active').each(function() {
            jQuery(this).removeClass('active').next('ul.options').hide();
        });
        jQuery(this).toggleClass('active').next('ul.options').toggle();
    });

    // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
    // Updates the select element to have the value of the equivalent option
    $listItems.click(function(e) {
        e.stopPropagation();
        $styledSelect.text(jQuery(this).text()).removeClass('active');
        $this.val(jQuery(this).attr('rel'));
        $list.hide();
        //alert($this.val()); // Uncomment this for demonstration! 
        $this.trigger("change");
    });

    // Hides the unordered list when clicking outside of it
    jQuery(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
    });


}

function loadTiposContrato(dataTipos) {
    jQuery("#edit-tipocontrato").empty();
    jQuery("#edit-tipocontrato").append('<option value=""></option>');
    jQuery.each(dataTipos, function(id, tipo) {
        jQuery("#edit-tipocontrato").append('<option value="' + tipo.Nombre + '">' + tipo.Nombre + '</option>');
    });
    var $this = jQuery("#edit-tipocontrato"),
        numberOfOptions = jQuery("#edit-tipocontrato").children('option').length;


    // Insert an unordered list after the styled div and also cache the list
    var $list = jQuery("#edit-tipocontrato").parent().children('ul.options');
    $list.html("");
    var $styledSelect = jQuery("#edit-tipocontrato").parent().children('.styledSelect');
    $styledSelect.html("");

    // Insert a list item into the unordered list for each select option
    for (var i = 0; i < numberOfOptions; i++) {
        jQuery('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    // Cache the list items
    var $listItems = $list.children('li');

    var $styledSelect = $this.next('div.styledSelect');
    // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
    $styledSelect.click(function(e) {
        e.stopPropagation();
        jQuery(this).parent().siblings('label').attr('class', 'label active');
        console.log("ddd")
        jQuery('div.styledSelect.active').each(function() {
            jQuery(this).removeClass('active').next('ul.options').hide();
        });
        jQuery(this).toggleClass('active').next('ul.options').toggle();
    });

    // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
    // Updates the select element to have the value of the equivalent option
    $listItems.click(function(e) {
        e.stopPropagation();
        $styledSelect.text(jQuery(this).text()).removeClass('active');
        $this.val(jQuery(this).attr('rel'));
        $list.hide();
        /*alert($this.val()); // Uncomment this for demonstration! */
    });

    // Hides the unordered list when clicking outside of it
    jQuery(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
    });
}

function loadCargos(dataCargos) {
    jQuery("#edit-cargo").empty();
    jQuery("#edit-cargo").append('<option value=""></option>');
    jQuery.each(dataCargos, function(id, cargo) {
        jQuery("#edit-cargo").append('<option value="' + cargo.Nombre + '">' + cargo.Nombre + '</option>');


    });
    var $this = jQuery("#edit-cargo"),
        numberOfOptions = jQuery("#edit-cargo").children('option').length;

    console.log("dfsfdsf :" + numberOfOptions)



    // Insert an unordered list after the styled div and also cache the list
    var $list = jQuery("#edit-cargo").parent().children('ul.options');
    $list.html("");
    var $styledSelect = jQuery("#edit-cargo").parent().children('.styledSelect');
    $styledSelect.html("");
    // Insert a list item into the unordered list for each select option
    for (var i = 0; i < numberOfOptions; i++) {
        jQuery('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    // Cache the list items
    var $listItems = $list.children('li');

    var $styledSelect = $this.next('div.styledSelect');
    // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
    $styledSelect.click(function(e) {
        e.stopPropagation();
        jQuery(this).parent().siblings('label').attr('class', 'label active');
        console.log("ddd")
        jQuery('div.styledSelect.active').each(function() {
            jQuery(this).removeClass('active').next('ul.options').hide();
        });
        jQuery(this).toggleClass('active').next('ul.options').toggle();
    });

    // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
    // Updates the select element to have the value of the equivalent option
    $listItems.click(function(e) {
        e.stopPropagation();
        $styledSelect.text(jQuery(this).text()).removeClass('active');
        $this.val(jQuery(this).attr('rel'));
        $list.hide();
        /*alert($this.val()); // Uncomment this for demonstration! */
    });

    // Hides the unordered list when clicking outside of it
    jQuery(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
    });
}

function loadEmpresas(dataEmpresas) {
    jQuery("#edit-empresa").empty();
    jQuery("#edit-empresa").append('<option value=""></option>');
    jQuery.each(dataEmpresas, function(id, empresa) {
        var valueText = empresa.td + "|" + empresa.identificacion + "|" + empresa.convenio_tipoproducto;
        jQuery("#edit-empresa").append('<option value="' + valueText + '">' + empresa.razon_social + '</option>');
    });
    jQuery("#edit-empresa").append('<option value="Otra">Otra</option>');
    var $this = jQuery("#edit-empresa"),
        numberOfOptions = jQuery("#edit-empresa").children('option').length;


    // Insert an unordered list after the styled div and also cache the list
    var $list = jQuery("#edit-empresa").parent().children('ul.options');
    $list.html("");
    var $styledSelect = jQuery("#edit-empresa").parent().children('.styledSelect');
    $styledSelect.html("");

    // Insert a list item into the unordered list for each select option
    for (var i = 0; i < numberOfOptions; i++) {
        jQuery('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    // Cache the list items
    var $listItems = $list.children('li');

    var $styledSelect = $this.next('div.styledSelect');
    // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
    $styledSelect.click(function(e) {
        e.stopPropagation();
        jQuery(this).parent().siblings('label').attr('class', 'label active');
        jQuery('div.styledSelect.active').each(function() {
            jQuery(this).removeClass('active').next('ul.options').hide();
        });
        jQuery(this).toggleClass('active').next('ul.options').toggle();
    });

    // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
    // Updates the select element to have the value of the equivalent option
    $listItems.click(function(e) {
        e.stopPropagation();
        $styledSelect.text(jQuery(this).text()).removeClass('active');
        $this.val(jQuery(this).attr('rel'));
        $list.hide();
        //alert($this.val()); // Uncomment this for demonstration! 
        $this.trigger("change");
    });

    // Hides the unordered list when clicking outside of it
    jQuery(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
    });
}

//////////////////SIMULADORES
function simulador() {

    var cantidad_min = parseInt(gl_cantidad_min);
    var cantidad_max = parseInt(gl_cantidad_max);
    var meses_min = parseInt(gl_meses_min);
    var meses_max = parseInt(gl_meses_max);
    var tasa = gl_tasa * 1;
    var seguro = gl_seguro * 1;
    var cuota = gl_cuota;

    if (i == 0) {
        jQuery(function() {
            var calc1 = cantidad_min + ((cantidad_max - cantidad_min) / 2);
            var calc2 = meses_min + ((meses_max - meses_min) / 2);
            jQuery('#cantidad').val(calc1);
            jQuery('#r_min_cantidad').html(formatNumber.new(cantidad_min, "$"));
            jQuery('#r_max_cantidad').html(formatNumber.new(cantidad_max, "$"));
            jQuery('#plazo').val(calc2);
            //jQuery('#r_min_meses').html(meses_min + ' MESES');
            //jQuery('#r_max_meses').html(meses_max + ' MESES');
            calcular();
        });
        jQuery('#plazo').on('change', calcular);
        jQuery('#btnMas').on('click', changeCantidad);
        jQuery('#btnMenos').on('click', changeCantidad);
    }
    i++;



    function setInputs() {
        jQuery('#catidadVal').html(formatNumber.new(jQuery('#cantidad').val()));
        jQuery('#plazoVal').html(jQuery('#plazo').val());
        jQuery('#cuotaVal').html(formatNumber.new(jQuery('#cuota').val()));
    }

    function calcular() {
        var cantidad = parseInt(jQuery('#cantidad').val());
        console.log("cantidad", cantidad);
        if (cantidad >= cantidad_max) {
            jQuery('#btnMas').addClass('active');
        } else if (cantidad <= cantidad_min) {
            jQuery('#btnMenos').addClass('active');
        } else {
            jQuery('#btnMenos').removeClass('active');
            jQuery('#btnMas').removeClass('active');
        }
        var plazo = -(jQuery('#plazo').val());
        cuota = ((cantidad / 100) * tasa) / (1 - ((1 * 100 + tasa) / 100) ** plazo);
        var newcuota = parseInt(cuota);
        jQuery('#cuota').val(newcuota);
        setInputs();
    }


    function changeCantidad(e) {
        console.log(e)
        var actual = parseInt(jQuery('#cantidad').val());
        var nueva;
        console.log(actual);

        switch (e.target.id) {
            case 'btnMenos':
                nueva = actual - 50000;
                break;
            case 'btnMas':
                nueva = actual + 50000;
                break;
        }
        console.log(nueva);
        jQuery('#cantidad').val(nueva);
        calcular();
    }
}
//////////////////SIMULADOR ADELANTO
function simuladorAdelanto() {
    if (simuAdelantoBande == false) {
        arrayRangos = adelantonomina_rangos.split(",");
        arrayAdelantos = adelantonomina_montos_adelanto.split(",");
        arraySalarios = adelantonomina_montos_salario.split(",");


        jQuery('#cantidadVal_an').html(formatNumber.new(jQuery('#cantidad_an').val()))
        jQuery('#btnMas_an').on('click', changeCantidad_AdelantoNomina);
        jQuery('#btnMenos_an').on('click', changeCantidad_AdelantoNomina);
        simuAdelantoBande = true;
    }
}

function changeCantidad_AdelantoNomina(e) {
    console.log(e)
    var actual = parseInt(jQuery('#cantidad_an').val());
    var nueva;
    console.log(actual);

    switch (e.target.id) {
        case 'btnMenos_an':
            nueva = obtenerNuevaCantidad(actual, "menos");
            break;
        case 'btnMas_an':
            nueva = obtenerNuevaCantidad(actual, "mas");
            break;
    }
    console.log(nueva);
    jQuery('#cantidad_an').val(nueva)
    jQuery('#cantidadVal_an').html(formatNumber.new(nueva));
    calcularAdelanto();
}

function obtenerNuevaCantidad(actual, accion) {
    var index = 0;
    for (var i = 0; i < arraySalarios.length; i++) {
        if (arraySalarios[i] == actual) {
            index = i;
            break;
        }
    }
    if (accion == "menos") {
        index -= 1;
        if (index > 0) {

            jQuery('#btnMenos_an').removeClass('active');
            jQuery('#btnMas_an').removeClass('active');
        } else {
            index = 0;
            jQuery('#btnMenos_an').addClass('active');
        }
    } else {
        index += 1;
        if (index < arraySalarios.length - 1) {
            jQuery('#btnMenos_an').removeClass('active');
            jQuery('#btnMas_an').removeClass('active');
        } else {
            index = arraySalarios.length - 1;
            jQuery('#btnMas_an').addClass('active');
        }
    }

    return arraySalarios[index];
}

function calcularAdelanto() {
    var valorActual = parseInt(jQuery('#cantidad_an').val());
    var valorinicial = 0;
    var index = 0;
    var bande = false;
    var valorAdelanto = 0;
    console.log("valorActual:", valorActual);
    for (var i = 0; i < arrayRangos.length; i++) {
        var rangoActual = parseInt(arrayRangos[i]);
        console.log("valorinicial:", valorinicial);
        console.log("arrayRangos[" + i + "] : " + rangoActual);

        if (valorActual >= valorinicial && valorActual < rangoActual) {
            bande = true;
            index = i;
            break;
        }
        valorinicial = parseInt(rangoActual);
    }

    if (bande) {
        valorAdelanto = arrayAdelantos[index];
    } else {
        valorAdelanto = arrayAdelantos[arrayAdelantos.length - 1];
    }

    jQuery('#valor_an').html(formatNumber.new(valorAdelanto));

}
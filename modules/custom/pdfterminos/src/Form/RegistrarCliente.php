<?php
namespace Drupal\pdfterminos\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Dompdf\Dompdf;

/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class RegistrarCliente extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'terminos_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['tipo_identificacion'] = array(
        '#type' => 'select',
        '#title' => ('Tipo de identificación:'),
        '#required' => TRUE,
        '#options' => array(
            'NIT' => t('NIT'),
            'Cédula de Ciudadania' => t('Cédula de Ciudadania'),
        ),
    );

    $form['identificacion'] = array(
        '#type' => 'textfield',
        '#title' => t('Número de identificacion:'),
        '#required' => TRUE,
    );

    $form['razon_social'] = array(
        '#type' => 'textfield',
        '#title' => t('Razón Social:'),
        '#required' => TRUE,
    );

    $form['contacto_nombres'] = array(
        '#type' => 'textfield',
        '#title' => t('Nombres del contacto de la empresa:'),
        '#required' => TRUE,
    );

    $form['contacto_apellidos'] = array(
        '#type' => 'textfield',
        '#title' => t('Apellidos del contacto de la empresa:'),
        '#required' => TRUE,
    );

    $form['tipo_producto'] = array(
        '#type' => 'textfield',
        '#title' => t('Tipo de Producto Solicitado:'),
        '#required' => TRUE,
    );

    $form['valor_solicitado'] = array(
        '#type' => 'textfield',
        '#title' => t('Valor Solicitado:'),
        '#required' => TRUE,
    );

    $form['valor_cuota'] = array(
        '#type' => 'textfield',
        '#title' => t('Valor Cuota:'),
        '#required' => TRUE,
    );

    $form['tipo_tasa'] = array(
        '#type' => 'textfield',
        '#title' => t('Tipo de Tasa de Interés:'),
        '#required' => TRUE,
    );

    $form['tasa_mora'] = array(
        '#type' => 'textfield',
        '#title' => t('Tasa Mora:'),
        '#required' => TRUE,
    );

    $form['costo_linxe'] = array(
        '#type' => 'textfield',
        '#title' => t('Costo Administración Linxe:'),
        '#required' => TRUE,
    );

    $form['tecnologia'] = array(
        '#type' => 'textfield',
        '#title' => t('Tecnologia:'),
        '#required' => TRUE,
    );

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'Guardar',
        //'#value' => t('Submit'),
    ];
    $form['#cache'] = [
        'max-age' => 0
    ];
  

    return $form;

  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

        $tipo_identificacion = $form_state->getValue('tipo_identificacion');
        if($tipo_identificacion == "") {
             $form_state->setErrorByName('tipo_identificacion', $this->t('Selecciona un tipo de identificación'));
        }

        $identificacion = $form_state->getValue('identificacion');
        if($identificacion == "") {
             $form_state->setErrorByName('identificacion', $this->t('Ingresa tu número de identificacion.'));
        }
    
        if(!is_numeric($identificacion)) {
             $form_state->setErrorByName('identificacion', $this->t('El número de identificación debe ser numérico.'));
        }
       
        $razon_social = $form_state->getValue('razon_social');
        if($razon_social == "") {
             $form_state->setErrorByName('razon_social', $this->t('Escribe la razón social'));
        }


        $contacto_nombres = $form_state->getValue('contacto_nombres');
        if($contacto_nombres == "") {
             $form_state->setErrorByName('contacto_nombres', $this->t('Escriba los nombres'));
        }

        $contacto_apellidos = $form_state->getValue('contacto_apellidos');
        if($contacto_apellidos == "") {
             $form_state->setErrorByName('contacto_apellidos', $this->t('Escriba los apellidos'));
        }

        $tipo_producto = $form_state->getValue('tipo_producto');
        if($tipo_producto == "") {
             $form_state->setErrorByName('tipo_producto', $this->t('Escriba el tipo de producto'));
        }

        $tipo_tasa = $form_state->getValue('tipo_tasa');
        if($tipo_tasa == "") {
             $form_state->setErrorByName('tipo_tasa', $this->t('Escriba el tipo de tasa'));
        }

        $tasa_mora = $form_state->getValue('tasa_mora');
        if($tasa_mora == "") {
             $form_state->setErrorByName('tasa_mora', $this->t('Escriba la tasa de mora'));
        }

        $costo_linxe = $form_state->getValue('costo_linxe');
        if($costo_linxe == "") {
             $form_state->setErrorByName('costo_linxe', $this->t('Escriba el valor.'));
        }
    
        if(!is_numeric($costo_linxe)) {
             $form_state->setErrorByName('costo_linxe', $this->t('El valor debe ser numérico.'));
        }

        $tecnologia = $form_state->getValue('tecnologia');
        if($tecnologia == "") {
             $form_state->setErrorByName('tecnologia', $this->t('Escriba el valor.'));
        }
    
        if(!is_numeric($tecnologia)) {
             $form_state->setErrorByName('tecnologia', $this->t('El valor debe ser numérico.'));
        }

        $valor_solicitado = $form_state->getValue('valor_solicitado');
        if($valor_solicitado == "") {
             $form_state->setErrorByName('valor_solicitado', $this->t('Escriba el valor.'));
        }
    
        if(!is_numeric($valor_solicitado)) {
             $form_state->setErrorByName('valor_solicitado', $this->t('El valor debe ser numérico.'));
        }

        $valor_cuota = $form_state->getValue('valor_cuota');
        if($valor_cuota == "") {
             $form_state->setErrorByName('valor_cuota', $this->t('Escriba el valor.'));
        }
    
        if(!is_numeric($valor_cuota)) {
             $form_state->setErrorByName('valor_cuota', $this->t('El valor debe ser numérico.'));
        }

        parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();
    $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <HTML>
    
    <HEAD>
        <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <META name="generator" content="BCL easyConverter SDK 5.0.210">
        <STYLE type="text/css">
            body {
                margin-top: 0px;
                margin-left: 0px;
                background-image: url(themes/custom/linxe/images/image.png);
            }    
            
            #page_1 {
                position: relative;
                overflow: hidden;
                margin: 0px 0px 12px 0px;
                padding: 0px;
                border: none;
                width: 816px;
            }
            
            #page_1 #p1dimg1 {
                position: absolute;
                top: 0px;
                left: 0px;
                z-index: -1;
                width: 816px;
                height: 1044px;
            }
            
            #page_1 #p1dimg1 #p1img1 {
                width: 816px;
                height: 1044px;
            }
            
            #page_2 {
                position: relative;
                overflow: hidden;
                margin: 0px 0px 12px 0px;
                padding: 0px;
                border: none;
                width: 816px;
            }
            
            #page_2 #p2dimg1 {
                position: absolute;
                top: 0px;
                left: 0px;
                z-index: -1;
                width: 816px;
                height: 1044px;
            }
            
            #page_2 #p2dimg1 #p2img1 {
                width: 816px;
                height: 1044px;
            }
            
            .dclr {
                clear: both;
                float: none;
                height: 1px;
                margin: 0px;
                padding: 0px;
                overflow: hidden;
            }
            
            .ft0 {
                font: bold 19px;
                line-height: 22px;
            }
            
            .ft1 {
                font: bold 16px;
                line-height: 19px;
            }
            
            .ft2 {
                font: 16px;
                line-height: 18px;
            }
            
            .ft3 {
                font: 1px;
                line-height: 1px;
            }
            
            .ft4 {
                font: 13px;
                line-height: 16px;
            }
            
            .ft5 {
                font: 11px;
                line-height: 14px;
            }
            
            .ft6 {
                font: 13px;
                margin-left: 15px;
                line-height: 16px;
            }
            
            .ft7 {
                font: 13px;
                margin-left: 13px;
                line-height: 16px;
            }
            
            .ft8 {
                font: 8px;
                line-height: 10px;
            }
            
            .ft9 {
                font: 13px;
                margin-left: 12px;
                line-height: 16px;
            }
            
            .p0 {
                text-align: left;
                padding-left: 133px;
                margin-top: 119px;
                margin-bottom: 0px;
            }
            
            .p1 {
                text-align: left;
                margin-top: 0px;
                margin-bottom: 0px;
                white-space: nowrap;
            }
            
            .p2 {
                text-align: left;
                padding-left: 288px;
                margin-top: 24px;
                margin-bottom: 0px;
            }
            
            .p3 {
                text-align: justify;
                padding-left: 106px;
                padding-right: 106px;
                margin-top: 20px;
                margin-bottom: 0px;
            }
            
            .p4 {
                text-align: left;
                padding-left: 298px;
                margin-top: 38px;
                margin-bottom: 0px;
            }
            
            .p5 {
                text-align: justify;
                padding-left: 154px;
                padding-right: 106px;
                margin-top: 20px;
                margin-bottom: 0px;
                text-indent: -24px;
            }
            
            .p6 {
                text-align: justify;
                padding-left: 154px;
                padding-right: 106px;
                margin-top: 1px;
                margin-bottom: 0px;
                text-indent: -24px;
            }
            
            .p7 {
                text-align: left;
                padding-left: 272px;
                margin-top: 128px;
                margin-bottom: 0px;
            }
            
            .p8 {
                text-align: justify;
                padding-left: 320px;
                padding-right: 106px;
                margin-top: 96px;
                margin-bottom: 0px;
            }
            
            .p9 {
                text-align: justify;
                padding-left: 154px;
                padding-right: 105px;
                margin-top: 0px;
                margin-bottom: 0px;
            }
            
            .p10 {
                text-align: justify;
                padding-left: 154px;
                padding-right: 106px;
                margin-top: 18px;
                margin-bottom: 0px;
                text-indent: -24px;
            }
            
            .p11 {
                text-align: justify;
                padding-left: 154px;
                padding-right: 106px;
                margin-top: 0px;
                margin-bottom: 0px;
                text-indent: -24px;
            }
            
            .p12 {
                text-align: justify;
                padding-left: 154px;
                padding-right: 106px;
                margin-top: 17px;
                margin-bottom: 0px;
                text-indent: -24px;
            }
            
            .p13 {
                text-align: left;
                padding-left: 196px;
                margin-top: 32px;
                margin-bottom: 0px;
            }
            
            .p14 {
                text-align: justify;
                padding-left: 106px;
                padding-right: 106px;
                margin-top: 17px;
                margin-bottom: 0px;
            }
            
            .p15 {
                text-align: justify;
                padding-left: 106px;
                padding-right: 106px;
                margin-top: 33px;
                margin-bottom: 0px;
            }
            
            .p16 {
                text-align: left;
                padding-left: 272px;
                margin-top: 289px;
                margin-bottom: 0px;
            }
            
            .td0 {
                padding: 0px;
                margin: 0px;
                width: 302px;
                vertical-align: bottom;
            }
            
            .td1 {
                padding: 0px;
                margin: 0px;
                width: 289px;
                vertical-align: bottom;
            }
            
            .tr0 {
                height: 23px;
            }
            
            .tr1 {
                height: 20px;
            }
            
            .tr2 {
                height: 25px;
            }
            
            .tr3 {
                height: 19px;
            }
            
            .tr4 {
                height: 22px;
            }
            
            .tr5 {
                height: 27px;
            }
            
            .t0 {
                width: 591px;
                margin-left: 113px;
                margin-top: 19px;
                font: 16px;
            }

            
            @page {
                margin: 0px 0px 0px 0px !important;
                padding: 0px 0px 0px 0px !important;
            }


        </STYLE>
    </HEAD>
    
    <BODY>
        <DIV id="page_1">
            <DIV id="p1dimg1">
                <IMG src="" id="p1dimg1">
            </DIV>
    
    
            <DIV class="dclr"></DIV>
            <P class="p0 ft0">TÉRMINOS Y CONDICIONES DEL PRODUCTO SOLICITADO</P>
            <TABLE cellpadding=0 cellspacing=0 class="t0">
                <TR>
                    <TD class="tr0 td0">
                        <P class="p1 ft1">Nombre del Cliente:</P>
                    </TD>
                    <TD class="tr0 td1">
                        <P class="p1 ft1">Tipo Documento:</P>
                    </TD>
                </TR>
                <TR>
                    <TD class="tr1 td0">
                        <P class="p1 ft2">'.$field['contacto_nombres'].' '.$field['contacto_apellidos'].'</P>
                    </TD>
                    <TD class="tr1 td1">
                        <P class="p1 ft2">'.$field['tipo_identificacion'].'</P>
                    </TD>
                </TR>
                <TR>
                    <TD class="tr2 td0">
                        <P class="p1 ft1">Número de Documento:</P>
                    </TD>
                    <TD class="tr2 td1">
                        <P class="p1 ft1">Tipo de Producto Solicitado:</P>
                    </TD>
                </TR>
                <TR>
                    <TD class="tr3 td0">
                        <P class="p1 ft2">'.$field['identificacion'].'</P>
                    </TD>
                    <TD class="tr3 td1">
                        <P class="p1 ft2">'.$field['tipo_producto'].'</P>
                    </TD>
                </TR>
                <TR>
                    <TD class="tr2 td0">
                        <P class="p1 ft2">
                        <SPAN class="ft1">Valor Solicitado: </SPAN>$ '.number_format($field['valor_solicitado'],0,",",".").'</P>
                    </TD>
                    <TD class="tr2 td1">
                        <P class="p1 ft2">
                        <SPAN class="ft1">Valor Cuota: </SPAN>$ '.number_format($field['valor_cuota'],0,",",".").'</P>
                    </TD>
                </TR>
                <TR>
                    <TD class="tr4 td0">
                        <P class="p1 ft2">
                        <SPAN class="ft1">Tipo Tasa de Interés: </SPAN>'.$field['tipo_tasa'].'</P>
                    </TD>
                    <TD class="tr4 td1">
                        <P class="p1 ft2">
                            <SPAN class="ft1">Tasa Mora: </SPAN>'.$field['tasa_mora'].'</P>
                    </TD>
                </TR>
                <TR>
                    <TD class="tr1 td0">
                        <P class="p1 ft1">Costo Administración Linxe:</P>
                    </TD>
                </TR>
                <TR>
                    <TD class="tr3 td0">
                        <P class="p1 ft2">$'.number_format($field['costo_linxe'],0,",",".").'</P>
                    </TD>
                    <TD class="tr3 td1">
                        <P class="p1 ft2">
                        <SPAN class="ft1">Tecnologia: </SPAN>$ '.number_format($field['tecnologia'],0,",",".").'</P>

                    </TD>
                </TR>
                <TR>
                    <TD class="tr5 td0">
                        <P class="p1 ft1">Nombre Empresa Pagadora
                            <SPAN class="ft2">:</SPAN>
                        </P>
                    </TD>
                    <TD class="tr5 td1">
                        <P class="p1 ft3">&nbsp;</P>
                    </TD>
                </TR>
                <TR>
                    <TD class="tr1 td0">
                        <P class="p1 ft2">'.$field['razon_social'].'</P>
                    </TD>
                    <TD class="tr1 td1">
                        <P class="p1 ft3">&nbsp;</P>
                    </TD>
                </TR>
            </TABLE>
            <P class="p2 ft1">Acuerdo de Firma Electrónica</P>
            <P class="p3 ft4">En adelante acepto realizar transacciones, firmar contratos, pactos, documentos, títulos valores y acuerdos con la entidad de forma electrónica. El método de firma electrónica que utilizaré podrá ser un nombre de usuario y una contraseña. Luego
                de haberme registrado en la plataforma web de la entidad, todo lo que acepte se entenderá consentido y firmado electrónica y/o digitalmente; de igual forma la entidad podrá identificarme mediante preguntas de seguridad, un código enviado mediante
                un mensaje a mi teléfono móvil registrado en la entidad, un código enviado a mi correo electrónico registrado en la entidad, a través de mi ubicación, mi dirección IP, los datos de mi ordenador, los datos de mi teléfono móvil, o mediante un
                cálculo sobre cualquiera de mis datos, un clic en una casilla o la mezcla de dos o más de ellas.</P>
            <P class="p4 ft1">Autorización de Descuento</P>
            <P class="p5 ft4">
                <SPAN class="ft5">1.</SPAN>
                <SPAN class="ft6">Autorizo a la Empresa Pagadora para que cuando terminen los contratos que tenga con ella, de la liquidación correspondiente se descuenten los saldos pendientes a favor de Firmus S.A.S., con Nit. </SPAN>
                <NOBR>901.260.610–6</NOBR> (en adelante, “FIRMUS”).</P>
            <P class="p6 ft4">
                <SPAN class="ft4">2.</SPAN>
                <SPAN class="ft7">Autorizo expresa e irrevocablemente para descontar de mi salario, prestaciones, cesantías, pensiones y/o cualquier pago derivado de la relación contractual laboral, de servicios o cualquier otra índole las cuotas mensuales de las obligaciones generadas y/o cualquier suma que adeude a FIRMUS, aún en el evento, si es del caso, de encontrarme disfrutando de vacaciones o licencias.</SPAN>
            </P>
            <P class="p6 ft4">
                <SPAN class="ft4">3.</SPAN>
                <SPAN class="ft7">Autorizo expresa e irrevocablemente a la Empresa Pagadora para que las sumas descontadas mensualmente en los términos aquí establecidos, sean giradas directamente y entregadas a FIRMUS, o a cualquier persona que se encuentre legitimada para recibir dichos pagos, dentro del término fijado para tal efecto. Si la Empresa Pagadora no descuenta y no paga a FIRMUS el valor de la(s) cuota(s) mensual(es) del(los) respectivo(s) producto(s), a través de los medios transaccionales de FIRMUS, no quedo exonerado de la responsabilidad por el pago de las mismas y los intereses de mora a que haya lugar.</SPAN>
            </P>
            <br>
            <br>
            <br>
            <br>

        </DIV>
        <DIV id="page_2">
            <DIV id="p2dimg1">
                <IMG src="" id="p2img1">
            </DIV>
    
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>

            <DIV class="dclr"></DIV>
            <P class="p10 ft4">
                <SPAN class="ft4">4.</SPAN>
                <SPAN class="ft7">Autorizo irrevocablemente a FIRMUS o a quien represente sus derechos u ostente en el futuro la calidad de acreedor para reportar la suscripción de la presente obligación a los bancos de información financiera, crediticia, comercial y de servicios, de acuerdo con lo establecido en la Ley 1266 de 2008, y las normas que la adicionen, sustituyan o modifiquen.</SPAN>
            </P>
            <P class="p10 ft4">
                <SPAN class="ft4">5.</SPAN>
                <SPAN class="ft7">Declaro que, en caso de mora o incumplimiento de las obligaciones a mi cargo, FIRMUS me hará exigible su cumplimiento de manera inmediata, sin necesidad de requerimiento judicial o extrajudicial para su cumplimiento, es decir, renuncio al requerimiento para constituirme en mora.</SPAN>
            </P>
            <P class="p11 ft4">
                <SPAN class="ft4">6.</SPAN>
                <SPAN class="ft9">Declaro que conozco que el producto que he adquirido no es un crédito ni puede ser entendido como tal, razón por la cual no me es cobrado ningún tipo de interés corriente y en su lugar solo se cobran una serie de contraprestaciones por administración del producto, entre otras.</SPAN>
            </P>
            <P class="p12 ft4">
                <SPAN class="ft5">7.</SPAN>
                <SPAN class="ft6">Expresamente declaro que la presente Autorización de Libranza o Descuento Directo no perderá su validez y permanecerá vigente mientras existan saldos a favor de FIRMUS, aún en el evento de cambio de entidad pagadora, toda vez que la simple autorización de descuento por mí dada, facultará a FIRMUS para solicitar a cualquier entidad pagadora con la que yo mantenga una relación laboral o contractual, el giro correspondiente de los recursos a que tenga derecho, para la debida atención de la(s) obligación(es) adquiridas bajo la modalidad de libranza o descuento directo; en cuyo caso, me obligo con FIRMUS a informar sobre dicha situación de manera inmediata al momento que se produzca el cambio.</SPAN>
            </P>
            <P class="p12 ft4">
                <SPAN class="ft5">8.</SPAN>
                <SPAN class="ft6">El Valor a Pagar se descontará en las cuotas de acuerdo a la periodicidad de pago definida por la Empresa Pagadora.</SPAN>
            </P>

            <P class="p13 ft1">Mérito ejecutivo de las obligaciones aquí contenidas</P>
            <P class="p14 ft4">Entiendo y acepto de manera expresa e irrevocable que el presente acuerdo prestará mérito ejecutivo por contener una obligación clara, expresa y exigible, y por ende, se me podrá exigir el cumplimientos de todas las obligaciones descritas en este
                documento ante cualquier juez de la república.</P>
            <P class="p15 ft4">Entiendo que debo pagar los intereses de mora correspondientes a los días adicionales que se causen para realizar el pago del Valor Solicitado, a la tasa moratoria máxima permitida en Colombia.</P>
            <P class="p15 ft4">Adicionalmente, declaro que conozco y acepto que a partir del quinto día de mora debo cancelar los gastos de cobranza, que podrán causarse hasta por un 20% sobre el valor total adeudado. Este valor me será cargado según las gestiones de cobro
                realizadas con el fin de cubrir los costos en que incurra FIRMUS por la realización de la gestión de cobranza a través de firmas externas especializadas y contratadas para tal fin.</P>
            <P class="p6 ft4"></p>

        </DIV>
    </BODY>
    
    </HTML>';

        $dompdf = new Dompdf();
        $dompdf->load_html($html);
        $dompdf->render();
        $pdfoutput = $dompdf->output();
        $filename = 'terminos-condiciones-'.$field['identificacion'].'.pdf';
        $filepath = 'sites/default/files/pdfterminos/terminos-condiciones-'.$field['identificacion'].'.pdf';
        $fp = fopen($filepath, "w+");
        fwrite($fp, $pdfoutput);
        fclose($fp);

        drupal_set_message("Terminos guardados correctamente");
        $response = new RedirectResponse("/admin/pdf/terminos-condiciones");
        $response->send();
    }
}
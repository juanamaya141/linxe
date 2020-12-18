<?php

namespace Drupal\linxecredit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;

use Drupal\linxecredit\Libs\AdelantoLibrary as AdelantoLibrary;
/**
 * Class CodeGenForm.
 *
 * @package Drupal\linxecredit\Form
 */
class RegisterForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxeregister_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $session = \Drupal::request()->getSession();

    $database = \Drupal::database();

    $session->remove('tkn_access');
    $session->remove('tkn_expiresin');

    $linxelib = new LinxeLibrary();


    //verificar si esta logueado la persona no mostrar el formulario de registro
    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
     //$form_state->setRedirect('linxecredit.dashboard-seleccion');
      $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-seleccion')->toString();
      return new RedirectResponse($url);
    }
    //


    $arrayEmpresa[""] = "" ;
    /*$objEmpresas = $linxelib->getEmpresas();
    

    foreach ($objEmpresas->SpResult->Table as $key => $value) {
      $key = $value->Id."|".$value->Nit;
      $arrayEmpresa[$key] = $value->NombreEmpresa;
    }

    $adelantolib = new AdelantoLibrary();

    foreach ($result as $record) {
      $key = $record->td."|".$record->identificacion."|".$record->convenio_tipoproducto;
      $arrayEmpresa[$key] = $record->razon_social;
    }
    
    $objEmpresas = $linxelib->getEmpresas();
    $arrayEmpresa["Otra"] = "Otra";
    */
    


    $form['nombre'] = array(
      '#type' => 'textfield',
      '#title' => t('Primer Nombre:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Nombre, es un dato obligatorio"),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['apellido'] = array(
      '#type' => 'textfield',
      '#title' => t('Primer Apellido:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Apellido, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['apellidos'] = array(
      '#type' => 'textfield',
      '#title' => t('Segundo Apellido:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Segundo Apellido, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    
    $arrayTipoDocs[""] = "" ;
    $arrayTipoDocs["1"] = "Cédula de ciudadanía" ;
    //$arrayTipoDocs["2"] = "N.I.T." ;
    $arrayTipoDocs["3"] = "Cédula de extranjería" ;
    $arrayTipoDocs["4"] = "Tarjeta de identidad" ;


    $form['tipo_doc'] = array(
      '#type' => 'select',
      '#title' => t('Tipo de Documento:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayTipoDocs,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Tipo de documento, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['documento'] = array(
      '#type' => 'number',
      '#title' => t('Número de Documento:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Número de documento, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['feex'] = array(
      '#type' => 'textfield',
      '#title' => t('Fecha de Expedición:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#prefix' => '',
      '#suffix' => '',
      '#id' => 'feex',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Fecha de Expedición, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['celular'] = array(
      '#type' => 'tel',
      '#title' => t('Celular:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#mask' => '+7(999)999-9999',
      '#size' => 10,
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Celular, es un dato obligatorio"),
        'maxlength' => 10
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
        'max' => 10,
      )
    );

    $form['correo'] = array(
      '#type' => 'email',
      '#title' => t('Correo Electrónico:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Correo electrónico, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );


    $arrayActividadEconomica[""] = "" ;
    $arrayActividadEconomica["empleado"] = "Empleado" ;
    $arrayActividadEconomica["pensionado"] = "Pensionado" ;

    $form['actividadEconomica'] = array(
      '#type' => 'select',
      '#title' => t('Actividad Económica:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayActividadEconomica,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Actividad económica, es un campo obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $arrayDestinos[""] = "" ;
    $arrayDestinos["Vacaciones"] = "Vacaciones" ;
    $arrayDestinos["Mejoras hogar"] = "Mejoras hogar" ;
    $arrayDestinos["Pagar deudas"] = "Pagar deudas" ;
    $arrayDestinos["Servicios médicos"] = "Servicios médicos" ;
    $arrayDestinos["Electrodomésticos"] = "Electrodomésticos" ;
    $arrayDestinos["Educación"] = "Educación" ;
    $arrayDestinos["Servicios odontológicos"] = "Servicios odontológicos" ;
    $arrayDestinos["Para Moto"] = "Para Moto" ;
    $arrayDestinos["Para Vehículo"] = "Para Vehículo" ;
    $arrayDestinos["Libre inversión"] = "Libre inversión" ;



    $form['destino'] = array(
      '#type' => 'select',
      '#title' => t('Destino de tu préstamo:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayDestinos,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Destino de tu préstamo, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $arrayProducto[""] = "" ;
    

    $form['tipoproducto'] = array(
      '#type' => 'select',
      '#title' => t('Tipo de Producto:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#validated' => TRUE, // este parametro es para que no se cause un error de opción invalida ya que se cargan dinámicamente por ajax sus datos
      '#options' => $arrayProducto,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Selecciona el tipo de producto en el cual estás interesado")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    /*
    ***   CAMPOS OTRO (NO SELECCIÓN DE PRODUCTO)
    */
    $form['nombre_empresa'] = array(
      '#type' => 'textfield',
      '#title' => t('Nombre de la empresa:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t("Nombre de la empresa, es un dato obligatorio"),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    $form['nombre_contacto_rh'] = array(
      '#type' => 'textfield',
      '#title' => t('Nombre de contacto RH:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t("Nombre de contacto en recursos humanos, es un dato obligatorio"),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    $form['email_contacto_rh'] = array(
      '#type' => 'textfield',
      '#title' => t('Correo electrónico RH:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t("Correo electrónico de Recursos Humanos, es un dato obligatorio"),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    $form['telefono_contacto_rh'] = array(
      '#type' => 'tel',
      '#title' => t('Teléfono RH:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#mask' => '+7(999)999-9999',
      '#size' => 10,
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t("Teléfono de Recursos Humanos, es un dato obligatorio"),
        'maxlength' => 10
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
        'max' => 10,
      )
    );

    
    /*
    ***   CAMPOS LIBRANZA
    */


    $form['empresa'] = array(
      '#type' => 'select2',
      '#title' => t('Selecciona tu empresa:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#validated' => TRUE, // este parametro es para que no se cause un error de opción invalida ya que se cargan dinámicamente por ajax sus datos
      '#options' => $arrayEmpresa,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Empresa, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['fingreso'] = array(
      '#type' => 'textfield',
      '#title' => t('Fecha de Ingreso:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#prefix' => '',
      '#suffix' => '',
      '#id' => 'fingreso',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Fecha de ingreso, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $arrayTipoContrato[""] = "" ;

    $form['tipocontrato'] = array(
      '#type' => 'select',
      '#title' => t('Tipo de Contrato:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayTipoContrato,
      '#validated' => TRUE, // este parametro es para que no se cause un error de opción invalida ya que se cargan dinámicamente por ajax sus datos
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Tipo de Contrato, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );



    $arrayDescuentoAdicional["si"] = "Sí" ;
    $arrayDescuentoAdicional["no"] = "No" ;

    $form['descuento1'] = array(
      '#type' => 'radio',
      '#name' => 'descuento',
      '#default_value' => 'si',
      '#label_display' =>'after',
      '#suffix' => '<span class="checkmark"></span>',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'class' => array("form-check-input")
      ),
      '#return_value' => 'si',
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );


    $form['descuento2'] = array(
      '#type' => 'radio',
      '#name' => 'descuento',
      '#label_display' =>'after',
      '#suffix' => '<span class="checkmark"></span>',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'class' => array("form-check-input")
      ),
      '#return_value' => 'no',
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    
    $arrayCargo[""] = "" ;


    $form['cargo'] = array(
      '#type' => 'select',
      '#title' => t('¿Cuál es tu cargo actual?:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayCargo,
      '#validated' => TRUE, // este parametro es para que no se cause un error de opción invalida ya que se cargan dinámicamente por ajax sus datos
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Debes seleccionar tu cargo actual")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    /*
    ***   CAMPOS ADELANTO DE NOMINA
    */

    // list eps
    $arrayFinalEPS[""] = "";
    $query = $database->select('linxe_seguridadsocial', 'iss');
    $resultISS = $query->condition('iss.tipo_entidad', "eps")
                    ->condition('iss.estado', 1 )
                    ->fields('iss')
                    ->orderBy('iss.nombre', 'ASC')
                    ->execute();
    
    foreach ($resultISS as $key=>$record) {
      $arrayFinalEPS[$record->nombre]=$record->nombre;
    }

    $form['eps_adelanto'] = array(
      '#type' => 'select2',
      '#title' => t('Seleccione su EPS:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayFinalEPS,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Selecciona su Entidad Promotora de Salud (EPS)")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    // list afp
    $arrayFinalAFP[""] = "";
    $query = $database->select('linxe_seguridadsocial', 'iss');
    $resultISS = $query->condition('iss.tipo_entidad', "afp")
                    ->condition('iss.estado', 1 )
                    ->fields('iss')
                    ->orderBy('iss.nombre', 'ASC')
                    ->execute();
    
    foreach ($resultISS as $key=>$record) {
      $arrayFinalAFP[$record->nombre]=$record->nombre;
    }

    $form['afp_adelanto'] = array(
      '#type' => 'select2',
      '#title' => t('Seleccione su AFP:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayFinalAFP,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Selecciona su Administradora de Fondos y Pensiones (AFP)")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['registra_pagos_6_meses1'] = array(
      '#type' => 'radio',
      '#name' => 'registra_pagos_6_meses',
      '#default_value' => 'si',
      '#label_display' =>'after',
      '#suffix' => '<span class="checkmark"></span>',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'class' => array("form-check-input")
      ),
      '#return_value' => 'si',
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );


    $form['registra_pagos_6_meses2'] = array(
      '#type' => 'radio',
      '#name' => 'registra_pagos_6_meses',
      
      '#label_display' =>'after',
      '#suffix' => '<span class="checkmark"></span>',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'class' => array("form-check-input")
      ),
      '#return_value' => 'no',
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $arrayRangoSalario[""] = "" ;
    //$arrayRangoSalario["0-828116"] = "$0 - $828.116" ;
    $arrayRangoSalario["828116-1316705"] = "$828.116 - $1'316.705" ;
    $arrayRangoSalario["1316705-1755606"] = "$1'316.705 - $1'755.606" ;
    $arrayRangoSalario["1755606-2633409"] = "$1'755.606 - $2'633.409" ;
    $arrayRangoSalario["2633409-3511212"] = "$2'633.409 - $3'511.212" ;
    $arrayRangoSalario["3511212-4389015"] = "$3'511.212 - $4'389.015" ;
    $arrayRangoSalario["4389015"] = "Mayor a $4'389.015" ;

    $form['rango_salario_adelanto'] = array(
      '#type' => 'select',
      '#title' => t('Su salario está entre:'),
      '#required' => FALSE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayRangoSalario,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Seleccione el rango donde se encuentra su salario")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );



    /**
     * 
     */
    $form['empresas_array'] = array(
      '#type' => 'hidden',
      '#required' => TRUE,
      '#default_value' => json_encode($arrayEmpresa),
      '#title_display' => "none",
    );

    $form['terminos'] = array(
      '#type' => 'checkbox',
      '#required' => TRUE,
      '#default_value' => '',
      '#id' => 'terminos',
      '#title' => 'Al hacer clic en esta casilla y el botón de INGRESAR A LINXE, usted acepta que ha leído y entendido los  <a href="/terminos-y-condiciones" target="_blank">Términos y Condiciones de Uso de LINXE</a> y también está autorizando expresamente el <a href="/autorizacion-tratamiento-datos-generales" target="_blank">Tratamiento de Datos Generales</a> y el <a href="/autorizacion-tratamiento-datos-sensibles" target="_blank">Tratamiento de Datos Personales Sensibles</a>',
      '#attributes' => array(
        'class' => array("form-check-input"),
        'data-msj' => t("Debes aceptar los términos y condiciones")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    
    
    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Ingresar a Linxe'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'linxeregistertheme_form';

    $form['#attached'] = [
          'library' => [
            'linxecredit/linxecreditstyles', //include our custom library for this response
          ]
        ];
    
    //$form['#attributes']['class'][] = 'formularioin';
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $nombre = $form_state->getValue('nombre');
    if($nombre == "") {
         $form_state->setErrorByName('nombre', $this->t('Escribe tu nombre.'));
    }
    $apellido = $form_state->getValue('apellido');
    if($apellido == "") {
         $form_state->setErrorByName('apellido', $this->t('Escribe tu apellido.'));
    }
    $segapellido = $form_state->getValue('apellidos');
    if($segapellido == "") {
         $form_state->setErrorByName('apellidos', $this->t('Escribe tu segundo apellido.'));
    }

    $tipo_doc = $form_state->getValue('tipo_doc');
    if($tipo_doc == "") {
         $form_state->setErrorByName('tipo_doc', $this->t('Selecciona el tipo de documento.'));
    }

   

    $documento = $form_state->getValue('documento');
    if($documento == "") {
         $form_state->setErrorByName('documento', $this->t('Ingresa tu número de documento.'));
    }

    if(!is_numeric($documento)) {
         $form_state->setErrorByName('documento', $this->t('El documento debe ser numérico.'));
    }

    $feex = $form_state->getValue('feex');
    if($feex == "") {
         $form_state->setErrorByName('feex', $this->t('Selecciona la fecha de expedición del documento.'));
    }

    $celular = $form_state->getValue('celular');
    if($celular == "") {
         $form_state->setErrorByName('celular', $this->t('Ingresa tu número de celular.'));
    }

    if(!is_numeric($celular)) {
         $form_state->setErrorByName('celular', $this->t('El celular debe ser numérico.'));
    }

    $correo = $form_state->getValue('correo');
    if($correo == "") {
         $form_state->setErrorByName('correo', $this->t('Digita tu correo electrónico.'));
    }

    if( !filter_var($correo, FILTER_VALIDATE_EMAIL) )
    {
      $form_state->setErrorByName('correo', $this->t('El correo electrónico no es válido'));
    }

    $actividadEconomica = $form_state->getValue('actividadEconomica');
    if($actividadEconomica == "") {
      $form_state->setErrorByName('actividadEconomica', $this->t('Selecciona tu actividad económica.'));
    }


    $destino = $form_state->getValue('destino');
    if($destino == "") {
         $form_state->setErrorByName('destino', $this->t('Selecciona el destino de tu prestamo.'));
    }

    $tipoproducto = $form_state->getValue('tipoproducto');
    if($tipoproducto == "") {
      $form_state->setErrorByName('tipoproducto', $this->t('Selecciona el tipo de producto que te interesa.'));
    }

    

    if($tipoproducto == "libranza")
    {
      
      $empresa = $form_state->getValue('empresa');
      if($empresa == "") {
          $form_state->setErrorByName('empresa', $this->t('Selecciona la empresa a la que perteneces.'));
      }else{
        $fingreso = $form_state->getValue('fingreso');
        if($fingreso == "") {
            $form_state->setErrorByName('fingreso', $this->t('Selecciona la fecha de ingreso a la empresa.'));
        }

        $tipocontrato = $form_state->getValue('tipocontrato');
        if($tipocontrato == "") {
            $form_state->setErrorByName('tipocontrato', $this->t('Selecciona el tipo de contrato que tienes con la empresa.'));
        }

        $cargo = $form_state->getValue('cargo');
        if($cargo == "") {
            $form_state->setErrorByName('cargo', $this->t('Selecciona el cargo que desempeñas en la empresa.'));
        }
      } 
    }else if($tipoproducto == "adelanto"){
      $eps_adelanto = $form_state->getValue('eps_adelanto');
      if($eps_adelanto == "") {
          $form_state->setErrorByName('eps_adelanto', $this->t('Selecciona la EPS a la que perteneces.'));
      }
      $afp_adelanto = $form_state->getValue('afp_adelanto');
      if($afp_adelanto == "") {
          $form_state->setErrorByName('afp_adelanto', $this->t('Selecciona la AFP a la que perteneces.'));
      }
      /*$razon_social_empleador_adelanto = $form_state->getValue('razon_social_empleador_adelanto');
      if($razon_social_empleador_adelanto == "") {
          $form_state->setErrorByName('razon_social_empleador_adelanto', $this->t('Ingresa la Razón Social de tu empleador.'));
      }*/
      $rango_salario_adelanto = $form_state->getValue('rango_salario_adelanto');
      if($rango_salario_adelanto == "") {
          $form_state->setErrorByName('rango_salario_adelanto', $this->t('Selecciona tu rango salarial.'));
      }
    }else{
      $nombre_empresa = $form_state->getValue('nombre_empresa');
      if($nombre_empresa == "") {
          $form_state->setErrorByName('nombre_empresa', $this->t('Ingresa el nombre de la empresa a la que perteneces.'));
      }
      $nombre_contacto_rh = $form_state->getValue('nombre_contacto_rh');
      if($nombre_contacto_rh == "") {
          $form_state->setErrorByName('nombre_contacto_rh', $this->t('Ingresa el nombre del contacto del area de Recursos Humanos.'));
      }
      $email_contacto_rh = $form_state->getValue('email_contacto_rh');
      if($email_contacto_rh == "") {
          $form_state->setErrorByName('email_contacto_rh', $this->t('Ingresa el email del contacto del area de Recursos Humanos.'));
      }
      $telefono_contacto_rh = $form_state->getValue('telefono_contacto_rh');
      if($telefono_contacto_rh == "") {
          $form_state->setErrorByName('telefono_contacto_rh', $this->t('Ingresa el teléfono de contacto del area de Recursos Humanos.'));
      }
    }

    $terminos = $form_state->getValue('terminos');
    if($terminos == "") {
         $form_state->setErrorByName('terminos', $this->t('Debes aceptar los términos y condiciones.'));
    }
    
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = \Drupal::config('linxecredit.settings');
    $adelantonomina_rangos = $config->get('linxecredit.adelantonomina_rangos');
    $adelantonomina_montos_adelanto = $config->get('linxecredit.adelantonomina_montos_adelanto');
    $adelantonomina_montos_salario = $config->get('linxecredit.adelantonomina_montos_salario');
    $arrayOne = explode(",",$adelantonomina_rangos);
    $arrayDos = explode(",",$adelantonomina_montos_adelanto);

    $arrayTres = explode(",",$adelantonomina_montos_salario);
    $salarioMinimo = $arrayOne[0];

    //var configuracion
    $minMesesCotizados = $config->get('linxecredit.minimo_meses_cotizados');

    
    //
    $session = \Drupal::request()->getSession();
    $field=$form_state->getValues();


    if($field["tipoproducto"]=="otro")
    {
      $arrayFields["tipodocumento"] = $field["tipo_doc"];
      $arrayFields["documento"] = $field["documento"];
      $arrayFields["fecha_expedicion"] = $field["feex"];
      $arrayFields["nombre"] = $field["nombre"];
      $arrayFields["primer_apellido"] = $field["apellido"];
      $arrayFields["segundo_apellido"] = $field["apellidos"];
      $arrayFields["celular"] = $field["celular"];
      $arrayFields["email"] = $field["correo"];
      $arrayFields["destino_prestamo"] = $field["destino"];
      $arrayFields["actividad_economica"] = $field["actividadEconomica"];
      $arrayFields["empresa"] = $field["nombre_empresa"];
      
      $arrayFields["contacto_rh"] = $field["nombre_contacto_rh"];
      $arrayFields["email_contacto_rh"] = $field["email_contacto_rh"];
      $arrayFields["telefono_contacto_rh"] = $field["telefono_contacto_rh"];
      /*
      $arrayFields["fecha_ingreso"] = $field["fingreso"];
      $arrayFields["tipo_contrato"] = $field["tipocontrato"];
      $arrayFields["descuento_nomina"] = $_POST["descuento"];
      $arrayFields["cargo"] = $field["cargo"];
      */
      $arrayFields["acepto_terminos"] = $field["terminos"];

      $arrayFields["registerdate"] = date("Y-m-d H:i:s");
      
      $query = \Drupal::database();
      $query ->insert('interesadosRegistro')
      ->fields($arrayFields)
      ->execute();
      $session->set('titlemsg',"Gracias.");
      $session->set('message',"Tu usuario no esta asociado con alguna de las empresas que tienen convenio con nuestra plataforma, te vamos a contactar para poder iniciar con el proceso de vinculación con tu empresa. Gracias por interesarte en nuestros servicios.");
      $session->set('labelbutton',"Aceptar");
      $form_state->setRedirect('linxecredit.showmessage');
    }else{
      $arrayFields["tipodocumento"] = $field["tipo_doc"];
      $arrayFields["documento"] = $field["documento"];
      $arrayFields["fecha_expedicion"] = $field["feex"];
      $arrayFields["nombre"] = $field["nombre"];
      $arrayFields["primer_apellido"] = $field["apellido"];
      $arrayFields["segundo_apellido"] = $field["apellidos"];
      $arrayFields["celular"] = $field["celular"];
      $arrayFields["email"] = $field["correo"];
      $arrayFields["destino_prestamo"] = $field["destino"];
      $arrayFields["actividad_economica"] = $field["actividadEconomica"];
      $arrayFields["eps"] = $field["eps_adelanto"];
      $arrayFields["afp"] = $field["afp_adelanto"];
      $arrayFields["registra_pagos_6_meses"] = $_POST["registra_pagos_6_meses"];
      $arrayFields["rango_salarial"] = $field["rango_salario_adelanto"];
      $arrayFields["acepto_terminos"] = $field["terminos"];
      $arrayFields["registerdate"] = date("Y-m-d H:i:s");
      $bandeLibranza = false;
      //verificar que la empresa del usuario no corresponda con alguna 
      if($field["tipoproducto"]=="libranza"){
        $bandeLibranza = true;
      }else{
        $adelantolib = new AdelantoLibrary();
        //hacer la petición a mareigua
        $arrayEmpresas = json_decode($field["empresas_array"]);
        $objMareigua = $adelantolib->mareiguaAnalyticsIE($arrayFields);
        $bandeLibranza = $adelantolib->validarEmpresaExistenteLibranza($objMareigua);
      }

      if($bandeLibranza)
      {
        $dataField["TxtPrimerNombre"] = $field["nombre"];
        $dataField["TxtPrimerApellido"] = $field["apellido"];
        $dataField["TxtSegundoApellido"] = $field["segundo_apellido"];
        $dataField["CmbTipodeidentificacion"] = $field["tipo_doc"];
        $dataField["TxtNoIdentificacion"] = $field["documento"];
        $dataField["TxtTelefonoCelular"] = $field["celular"];
        $dataField["TxtEmail"] = $field["correo"];
        $dataField["CmbDestino"] = $field["destino"];
        $vecEmpresa = explode("|", $field["empresa"]);
        $dataField["CmbSeleccionempresa"] = $vecEmpresa[1];
        $newDate = date("d/m/Y", strtotime($field["fingreso"]));
        $dataField["TxtFechadeingreso"] = $newDate;
        $dataField["CmbTipodecontrato"] = $field["tipocontrato"];
        $dataField["CmbDescuentoLey"] =  $_POST["descuento"];
        $dataField["Cmbcargoactual"] = $field["cargo"];
        $dataField["OpcAcepterminosycondiciones"] = $field["terminos"];
        
        
        
        //nuevos campos 
        $dataField["TxtSegundoApellido"] = $field["apellidos"];
        $newDate2 = date("d/m/Y", strtotime($field["feex"]));
        //$dataField["TxtFechadeexpedicion"] = $newDate2;
        $dataField["FechaExp"] = $newDate2;
        $dataField["Acteconomic"] = $field["actividadEconomica"];

        $linxelib = new LinxeLibrary();
        //print_r($dataField);
        //registro
        $objRegister = $linxelib->getRegister($dataField);
        //print_r($objRegister);
        //exit();
        
        if(array_key_exists('Success', $objRegister))
        {
          if($objRegister->Success==true){
            if($objRegister->SpResult == "")
            {
              $mensaje = json_encode($objRegister);
              \Drupal::logger('linxecredit')->notice($mensaje);

              $session->set('message',"En este momento la conexión con la empresa está fallando, vuelve a intentar mas tarde.");
              $session->set('labelbutton',"Aceptar");
              $form_state->setRedirect('linxecredit.showmessage');
            }else{
              $varexplode = explode(";",$objRegister->SpResult);
              if($varexplode[0]==1) //el registro se realizo sin ningún problema (1 - Registro exitoso 2 - Error Sotfware nomina 3 - Otro tipo de error 4 - Usuario ya se encuentra creado)
              {
                $url = Url::fromRoute('/registro/confirmacion');
                $form_state->setRedirect('entity.node.canonical',
                  array('node' => 12),
                  array()
                );
              }else{
                $session->set('message',$varexplode[1]);
                $session->set('labelbutton',"Aceptar");
                $form_state->setRedirect('linxecredit.showmessage');
              }
            }

          }else{
            $varexplode = explode(";",$objRegister->SpResult);
            if($varexplode[1]!="") 
            {
              $valor = $varexplode[1];
            }else if($varexplode[0]!="") {
              $valor = $objRegister->SpResult;
            }else{
              $valor = "En este momento la conexión con la empresa está fallando, vuelve a intentar mas tarde.";
            }
            $mensaje = "Libranza: ".json_encode($dataField);
            \Drupal::logger('linxecredit')->notice($mensaje);

            $mensaje = json_encode($objRegister);
            \Drupal::logger('linxecredit')->notice($mensaje);

            $session->set('message',$valor);
            $session->set('labelbutton',"Aceptar");
            $form_state->setRedirect('linxecredit.showmessage');
          }
        }else{
          $session->set('message',"Se presento un error de conexión, inténtelo más tarde");
          $session->set('labelbutton',"Aceptar");
          $form_state->setRedirect('linxecredit.showmessage');
        }

      }else{ 
        //ADELANTO DE NÓMINA
        //Validar que no sea un pensionado ya que este tipo de producto no está disponible para ese tipo de usuarios
        //print_r($objMareigua);
        //exit();

        if($field["documento"]=="11312372")
        {
          print_r($objMareigua);
          //exit();
        }
        if($objMareigua->respuesta_id!=4)
        {
          $session->set('titlemsg',"HA OCURRIDO UN ERROR");
          $session->set('message',"No hemos podido validar tu información, inténtalo más tarde");
          $session->set('labelbutton',"Aceptar");

          $mensajeError = "No hemos podido validar tu información, inténtalo más tarde";
          $titleMsg = "HA OCURRIDO UN ERROR";
          $emailto = $field["correo"];
          $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

          $form_state->setRedirect('linxecredit.showmessage');
        }else{

        
          if($arrayFields["actividad_economica"]=="empleado")
          {
            if($field["empresa"] == "Otra")
            {
              $empresaSel = $adelantolib->seleccionarMejorEmpresa($objMareigua);
            }else{ //cuando se selecciona una empresa con convenio de adelanto de nomina existente
              $vecEmpresa = explode("|", $field["empresa"]);
              $nitCurrent = $vecEmpresa[1];
              //hay que meter la funcion de seleccionarMejorEmpresa en donde le envíe el nit de la empresa 
              //y esta funcion verifique que efectivamente en mareigua si aparezca esta empresa con ese nit como aportante
              //print_r($objMareigua);
              $empresaSel = $adelantolib->seleccionarMareiguaEmpresaByNIT($objMareigua,$nitCurrent);
              if($field["documento"]=="11312372")
              {
                print_r($empresaSel);
                //exit();
              }
            }
            if($empresaSel == false)
            {
              
              $session->set('titlemsg',"HA OCURRIDO UN ERROR");
              $session->set('message',"Lo sentimos, la empresa seleccionada no fue encontrada en la validación interna que realizamos");
              $session->set('labelbutton',"Aceptar");

              $mensajeError = "Lo sentimos, la empresa seleccionada no fue encontrada en la validación interna que realizamos";
              $titleMsg = "HA OCURRIDO UN ERROR";
              $emailto = $field["correo"];
              $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

              $form_state->setRedirect('linxecredit.showmessage');
              
            }else{
              //hacemos la validacion de las reglas para ver si el usuario cumple con las condiciones para adelanto de nómina
              $rangoSalario = $field["rango_salario_adelanto"];
              $registraPagos6Meses = $arrayFields["registra_pagos_6_meses"];
              $eps_sel = $field["eps_adelanto"];
              $afp_sel = $field["afp_adelanto"];

              
              


              $objValidacion = json_decode($adelantolib->validationRules($empresaSel,$salarioMinimo,$minMesesCotizados,$rangoSalario,$registraPagos6Meses,$eps_sel,$afp_sel));
              if($objValidacion->status == "accepted")
              {
                
                /*
                *
                * DATASCORING
                */ 
                $linxelib = new LinxeLibrary();

                //validacion si la empresa existe con convenio adelanto de nomina y que no sea rechazada, si no existe se crea en la tabla de empresas
                $crearEmpresaAN = $adelantolib->validarCrearEmpresaAN($empresaSel);
                
                if($crearEmpresaAN["status"] == "fail" && $crearEmpresaAN["msg"] == "convenio_rechazado")
                {
                  $session->set('titlemsg',"LO SENTIMOS");
                  $session->set('message',"Hola ".$field["nombre"]." ".$field["apellido"].", no hemos podido firmar el convenio con tu empresa, sin este requisito no podemos hacer el desembolso, por favor debes contactarte con el área de recursos humanos paara que nos permitan firmar el convenio, solo así podremos continuar con el proceso. Si tu empresa quiere firmar el convenio con Linxe, nos puedes informar vía mail para que los volvamos a contactar.");
                  $session->set('labelbutton',"Aceptar");

                  $mensajeError = "Hola ".$field["nombre"]." ".$field["apellido"].", no hemos podido firmar el convenio con tu empresa, sin este requisito no podemos hacer el desembolso, por favor debes contactarte con el área de recursos humanos paara que nos permitan firmar el convenio, solo así podremos continuar con el proceso. Si tu empresa quiere firmar el convenio con Linxe, nos puedes informar vía mail para que los volvamos a contactar.";
                  $titleMsg = "LO SENTIMOS";
                  $emailto = $field["correo"];
                  $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);
              

                  $form_state->setRedirect('linxecredit.showmessage');
                }elseif($crearEmpresaAN["status"] == "fail"){
                  $session->set('titlemsg',"HA OCURRIDO UN ERROR");
                  $session->set('message',"Lo sentimos, la empresa no pudo ser creada en nuestra plataforma");
                  $session->set('labelbutton',"Aceptar");
                  $form_state->setRedirect('linxecredit.showmessage');
                }else{
                  //creacion de la empresa seleccionada en datascoring
                  $dataFieldEmpresa = [];
                  $dataFieldEmpresa["NitEmpresa"] = $empresaSel["numero_identificacion_aportante"];
                  $dataFieldEmpresa["RazonSocial"] = $empresaSel["razón_social_aportante"];
                  $objEmpresaDatascoring = $linxelib->createEmpresaDatascoring($dataFieldEmpresa);

                  if($field["documento"]=="11312372")
                  {
                    print_r($objEmpresaDatascoring);
                    //exit();
                  }
                  //print_r($objEmpresaDatascoring);
                  //exit();
                  //creamos la empresa en datascoring
                  //$bande = true;
                  //if($bande)
                  if(array_key_exists('Success', $objEmpresaDatascoring))
                  {
                    $arraySpResultEmpresa = explode(";",$objEmpresaDatascoring->SpResult);
                    //$objEmpresaDatascoring->Success=true;
                    //if($bande)
                    if($objEmpresaDatascoring->Success==true && ($arraySpResultEmpresa[0]==1 || $arraySpResultEmpresa[0]==4) )//1->creada satisfactorimente ;  4-> La empresa ya se encuentra registrada
                    {
                      $dataField = [];
                      $dataField["PrimerNombre"] = $field["nombre"];
                      $dataField["PrimerApellido"] = $field["apellido"];
                      $dataField["SegundoApellido"] = $field["segundo_apellido"];
                      $dataField["TipoId"] = $field["tipo_doc"];
                      $dataField["Numid"] = $field["documento"];
                      $dataField["Celular"] = $field["celular"];
                      $dataField["Mail"] = $field["correo"];
                      $newDate2 = date("d/m/Y", strtotime($field["feex"]));
                      $dataField["FechaExp"] = $newDate2;
                      /*$newDate = date("d/m/Y", strtotime($field["fingreso"]));
                      $dataField["TxtFechadeingreso"] = $newDate;
                      $dataField["CmbTipodecontrato"] = $field["tipocontrato"];
                      $dataField["CmbDescuentoLey"] =  $_POST["descuento"];
                      $dataField["Cmbcargoactual"] = $field["cargo"];*/
                      $dataField["Acteconomic"] = $field["actividadEconomica"];
                      $dataField["NitEmp"] = $empresaSel["numero_identificacion_aportante"];
                      $dataField["Origen"] = "Página Web";

                      //print_r($dataField);
                      //echo "<br>";
                      $objRegisterDatascoring = $linxelib->getRegisterWithoutValidation($dataField);
                      //print_r($objRegisterDatascoring);
                      //exit();
                      //$bande = true;
                      if($field["documento"]=="11312372")
                      {
                        print_r($objRegisterDatascoring);
                        //exit();
                      }
                      if(array_key_exists('Success', $objRegisterDatascoring))
                      //if($bande)
                      {
                        $arraySpResultEmpleado = explode(";",$objRegisterDatascoring->SpResult);
                        //$objRegisterDatascoring->Success=true; 
                        //if($bande)
                        if($objRegisterDatascoring->Success==true && ($arraySpResultEmpleado[0]==1 || $arraySpResultEmpleado[0]==4) ) //1->creada satisfactorimente ;  4-> El empleado ya se había creado
                        {  //agregamos campos adicionales de la empresa para el registro adelanto nómina
                          if($crearEmpresaAN["msg"] == "ya_creada_convenio"){
                            $arrayFields["convenio_empresa"] = "si";
                            $arrayFields["nit_empresa"] = $empresaSel["numero_identificacion_aportante"];
                            $arrayFields["idempresa"] = $crearEmpresaAN["idempresa"];
                          }else{
                            $arrayFields["convenio_empresa"] = "no";
                            $arrayFields["nit_empresa"] = $empresaSel["numero_identificacion_aportante"];
                            $arrayFields["idempresa"] = $crearEmpresaAN["idempresa"];
                          }
                          
                          //registro
                          $objRegister = json_decode($adelantolib->registerAdelantoNomina($arrayFields));
                          if($objRegister->status=="ok")
                          {
                            $idregistro = $objRegister->id;
                            //creamos una solicitud o retomamos una que ya estaba pendiente
                            $montoMaximoAprobado = $adelantolib->getMontoMaximo($empresaSel["salario_cotizado"]);
                            $objSolicitud = json_decode($adelantolib->crearRegistroSolicitud($idregistro,$arrayFields,$objMareigua,$montoMaximoAprobado,$empresaSel));
                            
                            if($objSolicitud->status=="ok")
                            {
                              $titleMsg = "FELICITACIONES";
                              $mensaje = "<p>Estimad@ ".$arrayFields["nombre"]." ".$arrayFields["primer_apellido"]."</p><br/>";
                              $mensaje .="<p>En este momento tienes aprobado un <b>adelanto de salario</b> por valor de  $ ".number_format($montoMaximoAprobado,0,",",".")." el cual podrás utilizar cuando nos confirmes el valor que prefieras. Si quieres aceptar el adelanto haz click acá: <a href='https://linxe.com' target='_blank'>https://linxe.com</a></p><br/>";
                              $mensaje .= "<p>Nos interesa seguir mejorando, cuéntanos cómo podemos hacerlo ingresando acá: <a href='https://linxe.com/form/nps' target='_blank'>https://linxe.com/form/nps</a></p>";
                              $emailto = $arrayFields["email"];
                              $resultmail = $adelantolib->sendMail_success($titleMsg,$mensaje,$emailto);
                            }


                            if($objRegister->operation=="updated" )
                            {
                              $session->set('titlemsg',"Gracias.");
                              $session->set('message',"Ya te encontrabas registrado en nuestra plataforma para el mismo producto, ingresa con tu usuario y contraseña que te fue enviado al correo electrónico");
                              $session->set('labelbutton',"Aceptar");
                              $form_state->setRedirect('linxecredit.showmessage');  
                            }elseif($objRegister->operation=="create" ){
                              
                              //mensaje de confirmación
                              $url = Url::fromRoute('/registro/confirmacion');
                              $form_state->setRedirect('entity.node.canonical',
                                array('node' => 12),
                                array()
                              );
                              /*
                              //creamos el usuario en el sistema dentro de Drupal 
                              if($adelantolib->createUser($idregistro, $arrayFields))
                              {
                                
                                
                              }else{
                                $session->set('titlemsg',"HA OCURRIDO UN ERROR");
                                $session->set('message',"El usuario no ha podido generarse en la plataforma");
                                $session->set('labelbutton',"Aceptar");

                                $mensajeError = "El usuario no ha podido generarse en la plataforma";
                                $titleMsg = "HA OCURRIDO UN ERROR";
                                $emailto = $field["correo"];
                                $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

                                $form_state->setRedirect('linxecredit.showmessage'); 
                              }*/
                              
                            }
                            
                          }else{
                            $session->set('message',$objValidacion->msg);
                            $session->set('labelbutton',"Aceptar");
                            
                            $mensajeError = $objValidacion->msg;
                            $titleMsg = "HA OCURRIDO UN ERROR";
                            $emailto = $field["correo"];
                            $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

                            $form_state->setRedirect('linxecredit.showmessage');
                          }
                          

                        }else{
                          $varexplode = explode(";",$objRegisterDatascoring->SpResult);
                          if($varexplode[1]!="") 
                          {
                            $valor = $varexplode[1];
                          }else if($varexplode[0]!="") {
                            $valor = $objRegister->SpResult;
                          }else{
                            $valor = "En este momento la conexión con la empresa está fallando, vuelve a intentar mas tarde.";
                          }
                          $mensaje = "Adelanto: ".json_encode($dataField);
                          \Drupal::logger('linxecredit')->notice($mensaje);

                          $mensaje = json_encode($objRegisterDatascoring);
                          \Drupal::logger('linxecredit')->notice($mensaje);

                          $session->set('message',$valor);
                          $session->set('labelbutton',"Aceptar");

                          $mensajeError = $valor;
                          $titleMsg = "HA OCURRIDO UN ERROR";
                          $emailto = $field["correo"];
                          $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);
              

                          $form_state->setRedirect('linxecredit.showmessage');
                        }
                      }else{
                        $session->set('message',"Se presento un error de conexión, inténtelo más tarde 1");
                        $session->set('labelbutton',"Aceptar");

                        $mensajeError = "Se presento un error de conexión, inténtelo más tarde";
                        $titleMsg = "HA OCURRIDO UN ERROR";
                        $emailto = $field["correo"];
                        $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

                        $form_state->setRedirect('linxecredit.showmessage');
                      }
                    }else{
                      $session->set('message',"No se pudo crear la empresa, inténtalo más adelante");
                      $session->set('labelbutton',"Aceptar");

                      $mensajeError = "No se pudo crear la empresa, inténtalo más adelante";
                      $titleMsg = "HA OCURRIDO UN ERROR";
                      $emailto = $field["correo"];
                      $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

                      $form_state->setRedirect('linxecredit.showmessage');
                    }
                  }else{
                    $session->set('message',"Se presento un error de conexión, inténtelo más tarde 0");
                    $session->set('labelbutton',"Aceptar");

                    $mensajeError = "Se presento un error de conexión, inténtelo más tarde";
                    $titleMsg = "HA OCURRIDO UN ERROR";
                    $emailto = $field["correo"];
                    $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

                    $form_state->setRedirect('linxecredit.showmessage');
                  }
                }

                
                
              }else{
                $mensajeError = "Hola ".$field["nombre"]." ".$field["apellido"].", ".$objValidacion->msg;
                $titleMsg = $objValidacion->title;
                $emailto = $field["correo"];
                $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

                $session->set('titlemsg',$titleMsg);
                $session->set('message',$mensajeError);
                $session->set('labelbutton',"Aceptar");

                
                $form_state->setRedirect('linxecredit.showmessage');
              }
            }

            

            
            
            
            
            
          }else{
            $session->set('titlemsg',"HA OCURRIDO UN ERROR");
            $session->set('message',"Lo sentimos, este producto no aplica para pensionados, déjanos los datos de tu empresa en contactos.");
            $session->set('labelbutton',"Aceptar");

            $mensajeError = "Lo sentimos, este producto no aplica para pensionados, déjanos los datos de tu empresa en contactos.";
            $titleMsg = "HA OCURRIDO UN ERROR";
            $emailto = $field["correo"];
            $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

            $form_state->setRedirect('linxecredit.showmessage');
          }

        
        }
        
        
        //
      }
 
    }

    

  }

}

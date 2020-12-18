<?php

namespace Drupal\linxecredit\Form;

use Drupal\Core\Form\ConfigFormBase; 
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Drupal\file\Entity\File;

/**
 * Class LinxeModuleConfigForm.
 *
 * @package Drupal\zonapersonasregistro\Form
 */
class LinxeModuleConfigForm extends ConfigFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxecreditconfig_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
 
    $config = $this->config('linxecredit.settings');

    $form['urlws_backend'] = array(
      '#type' => 'textfield',
      '#title' => t('URL WEB SERVICE BACKEND'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.urlws_backend'),
      
    );

    $form['cantidad_min'] = array(
      '#type' => 'textfield',
      '#title' => t('Cantidad mínima crédito'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.cantidad_min'),
      
    );
    $form['cantidad_max'] = array(
      '#type' => 'textfield',
      '#title' => t('Cantidad máxima crédito'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.cantidad_max'),
      
    );
    $form['meses_min'] = array(
      '#type' => 'textfield',
      '#title' => t('Cantidad de meses mínima del crédito'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.meses_min'),
      
    );
    $form['meses_max'] = array(
      '#type' => 'textfield',
      '#title' => t('Cantidad de meses máxima del crédito'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.meses_max'),
      
    );
    $form['tasa'] = array(
      '#type' => 'textfield',
      '#title' => t('Tasa crédito'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.tasa'),
      
    );
    $form['tasamora'] = array(
      '#type' => 'textfield',
      '#title' => t('Tasa mora'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.tasamora'),
      
    );
    $form['seguro'] = array(
      '#type' => 'textfield',
      '#title' => t('Porcentaje seguro crédito'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.seguro'),
      
    );

    $form['iva'] = array(
      '#type' => 'textfield',
      '#title' => t('Porcentaje iva '),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.iva'),
      
    );

    $form['cargo_tecnologia'] = array(
      '#type' => 'textfield',
      '#title' => t('Porcentaje cargo tecnología '),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.cargo_tecnologia'),
      
    );

    $form['plazos'] = array(
      '#type' => 'textfield',
      '#title' => t('Plazos '),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.plazos'),
      
    );

    $form['rangomontos'] = array(
      '#type' => 'textfield',
      '#title' => t('Rangos Montos '),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.rangomontos'),
      
    );

    $form['urlws_token'] = array(
      '#type' => 'textfield',
      '#title' => t('URL WEB SERVICE GET TOKEN'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.urlws_token'),
      
    );
    $form['username_datascoring'] = array(
      '#type' => 'textfield',
      '#title' => t('USER NAME DATASCORING'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.username_datascoring'),
      
    );
    $form['password_datascoring'] = array(
      '#type' => 'textfield',
      '#title' => t('PASSWORD NAME DATASCORING'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.password_datascoring'),
      
    );

    $form['applicationid_datascoring'] = array(
      '#type' => 'textfield',
      '#title' => t('APPLICATIONID DATASCORING'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.applicationid_datascoring'),
      
    );
    $form['projectid_datascoring'] = array(
      '#type' => 'textfield',
      '#title' => t('PROJECTID DATASCORING'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.projectid_datascoring'),
      
    );

    $form['url_pdf_contrato'] = [
      '#type' => 'managed_file',
      '#title' => t('URL PDF Contrato/Pagare'),
      '#description'   => t("Ingrese el pdf para el paso 3 de contrato / pagaré."),
      '#upload_validators' => [
        'file_validate_extensions' => ['pdf'],
        'file_validate_size' => [25600000],
      ],
      '#upload_location' => 'public://',
      '#required' => FALSE,
      '#default_value' => $config->get('linxecredit.url_pdf_contrato'),
    ];


    $form['endtitlemsg_con_validacion_empresa'] = array(
      '#type' => 'textfield',
      '#title' => t('TÍTULO MENSAJE FINAL CON VALIDACIÓN EMPRESA - NÓMINA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.endtitlemsg_con_validacion_empresa'),
      
    );

    $form['endmsg_con_validacion_empresa'] = array(
      '#type' => 'textarea',
      '#title' => t('MENSAJE FINAL CON VALIDACIÓN EMPRESA - NÓMINA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.endmsg_con_validacion_empresa'),
      
    );

    $form['endtitlemsg_sin_validacion_empresa'] = array(
      '#type' => 'textfield',
      '#title' => t('TÍTULO MENSAJE FINAL SIN VALIDACIÓN EMPRESA - NÓMINA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.endtitlemsg_sin_validacion_empresa'),
      
    );

    $form['endmsg_sin_validacion_empresa'] = array(
      '#type' => 'textarea',
      '#title' => t('MENSAJE FINAL SIN VALIDACIÓN EMPRESA - NÓMINA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.endmsg_sin_validacion_empresa'),
      
    );

    $form['endtitlemsg_con_validacion_empresa_movi'] = array(
      '#type' => 'textfield',
      '#title' => t('TÍTULO MENSAJE FINAL CON VALIDACIÓN EMPRESA - MOVII'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.endtitlemsg_con_validacion_empresa_movi'),
      
    );

    $form['endmsg_con_validacion_empresa_movi'] = array(
      '#type' => 'textarea',
      '#title' => t('MENSAJE FINAL CON VALIDACIÓN EMPRESA - MOVII'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.endmsg_con_validacion_empresa_movi'),
      
    );

    $form['endtitlemsg_sin_validacion_empresa_movi'] = array(
      '#type' => 'textfield',
      '#title' => t('TÍTULO MENSAJE FINAL SIN VALIDACIÓN EMPRESA - MOVII'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.endtitlemsg_sin_validacion_empresa_movi'),
      
    );

    $form['endmsg_sin_validacion_empresa_movi'] = array(
      '#type' => 'textarea',
      '#title' => t('MENSAJE FINAL SIN VALIDACIÓN EMPRESA - MOVII'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.endmsg_sin_validacion_empresa_movi'),
      
    );

    $form['tooltip_text_nomina'] = array(
      '#type' => 'textarea',
      '#title' => t('TEXTO TOOLTIP PARA OPCIÓN DE PAGO POR NÓMINA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.tooltip_text_nomina'),
      
    );

    $form['tooltip_text_movi'] = array(
      '#type' => 'textarea',
      '#title' => t('TEXTO TOOLTIP PARA OPCIÓN DE PAGO POR MOVII'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.tooltip_text_movi'),
      
    );

    $form['urlws_querywebinar'] = array(
      '#type' => 'textarea',
      '#title' => t('URL WEB SERVICE QUERY WEBINAR'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.urlws_querywebinar'),      
    );

    $form['urlws_webinar'] = array(
      '#type' => 'textarea',
      '#title' => t('URL WEB SERVICE WEBINAR'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.urlws_webinar'),      
    );

    $form['webinar_apikey'] = array(
      '#type' => 'textfield',
      '#title' => t('API KEY WEB SERVICE WEBINAR'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.webinar_apikey'),
    );
    /* Nuevo producto Adelanto de Nómina */

    $form['adelantonomina_rangos'] = array(
      '#type' => 'textfield',
      '#title' => t('Rangos Adelanto de Nómina'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.adelantonomina_rangos'),
    );

    $form['adelantonomina_montos_adelanto'] = array(
      '#type' => 'textfield',
      '#title' => t('Montos para adelanto por Rango de salario en el producto Adelanto de Nómina'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.adelantonomina_montos_adelanto'),
    );

    $form['adelantonomina_montos_salario'] = array(
      '#type' => 'textfield',
      '#title' => t('Montos de Salario que se mostrarán en el simulador del home Adelanto de Nómina'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.adelantonomina_montos_salario'),
    );

    $form['minimo_meses_cotizados'] = array(
      '#type' => 'textfield',
      '#title' => t('Número mínimo de meses requerido cotizados a seguridad social'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.minimo_meses_cotizados'),
    );

    $form['urlws_mareigua'] = array(
      '#type' => 'textfield',
      '#title' => t('URL WEB SERVICE MAREIGUA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.urlws_mareigua'),
      
    );
    $form['username_mareigua'] = array(
      '#type' => 'textfield',
      '#title' => t('USER NAME MAREIGUA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.username_mareigua'),
      
    );
    $form['password_mareigua'] = array(
      '#type' => 'textfield',
      '#title' => t('PASSWORD NAME MAREIGUA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.password_mareigua'),
      
    );
    $form['cargo_administracion_adelanto'] = array(
      '#type' => 'textfield',
      '#title' => t('Cargo administracion Adelanto Nómina'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.cargo_administracion_adelanto'),
      
    );
    $form['cargo_tecnologia_adelanto'] = array(
      '#type' => 'textfield',
      '#title' => t('Cargo tecnología Adelanto Nómina'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.cargo_tecnologia_adelanto'),
      
    );
    $form['seguro_adelanto'] = array(
      '#type' => 'textfield',
      '#title' => t('Porcentaje seguro crédito'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.seguro_adelanto'),
      
    );

    $form['iva_adelanto'] = array(
      '#type' => 'textfield',
      '#title' => t('Porcentaje iva '),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.iva_adelanto'),
      
    );

    $form['urlws_deceval'] = array(
      '#type' => 'textfield',
      '#title' => t('URL WEB SOAP PROXY DECEVAL'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.urlws_deceval'),
      
    );

    $form['deceval_codigodepositante'] = array(
      '#type' => 'textfield',
      '#title' => t('DECEVAL CÓDIGO DEPOSITANTE'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.deceval_codigodepositante'),
      
    );

    $form['deceval_usuario'] = array(
      '#type' => 'textfield',
      '#title' => t('DECEVAL USUARIO'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.deceval_usuario'),
      
    );

    $form['deceval_identificacionemisor'] = array(
      '#type' => 'textfield',
      '#title' => t('DECEVAL IDENTIFICACIÓN EMISOR'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.deceval_identificacionemisor'),
      
    );

    $form['deceval_idclasedocumento'] = array(
      '#type' => 'textfield',
      '#title' => t('DECEVAL ID CLASE DOCUMENTO'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.deceval_idclasedocumento'),
      
    );

    $form['twilio_sid'] = array(
      '#type' => 'textfield',
      '#title' => t('TWILIO OTP SID'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.twilio_sid'),      
    );

    $form['twilio_token'] = array(
      '#type' => 'textfield',
      '#title' => t('TWILIO OTP TOKEN'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.twilio_token'),      
    );

    $form['twilio_phonenumber'] = array(
      '#type' => 'textfield',
      '#title' => t('TWILIO PHONE NUMBER'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.twilio_phonenumber'),      
    );


    $form['adelanto_dias_recordatorio_notificacion'] = array(
      '#type' => 'textfield',
      '#title' => t('ADELANTO NÓMINA: DÍAS RECORDATORIO TÉRMINOS Y CONDICIONES'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.adelanto_dias_recordatorio_notificacion'),      
    );

    $form['adelanto_dias_eliminacion_solicitud'] = array(
      '#type' => 'textfield',
      '#title' => t('ADELANTO NÓMINA: DÍAS ELIMINACIÓN AUTOMÁTICA SOLICITUD SIN TYC'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.adelanto_dias_eliminacion_solicitud'),      
    );

    $form['adelanto_intereses_mora'] = array(
      '#type' => 'textfield',
      '#title' => t('ADELANTO NÓMINA: INTERESES DE MORA'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.adelanto_intereses_mora'),      
    );

    $form['adelanto_dias_anteriores_corte_para_facturacion'] = array(
      '#type' => 'textfield',
      '#title' => t('ADELANTO NÓMINA: DÍAS ANTERIORES AL CORTE PARA GENERAR LA FACTURACIÓN'),
      '#required' => TRUE,
      '#default_value' => $config->get('linxecredit.adelanto_dias_anteriores_corte_para_facturacion'),      
    );
    
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
 
  public function submitForm(array &$form, FormStateInterface $form_state) {
 
    $config = $this->config('linxecredit.settings');

    $archivo = $form_state->getValue('url_pdf_contrato');
    // Load the object of the file by its fid. 
    $file = File::load($archivo[0]);
    // Set the status flag permanent of the file object.
    if (!empty($file)) {
      $file->setPermanent();
      // Save the file in the database.
      $file->save();
      $file_usage = \Drupal::service('file.usage'); 
      $file_usage->add($file, 'linxecredit', 'linxecredit', \Drupal::currentUser()->id());
    }


    $config->set('linxecredit.urlws_backend', $form_state->getValue('urlws_backend'));
    
    $config->set('linxecredit.cantidad_min', $form_state->getValue('cantidad_min'));
    $config->set('linxecredit.cantidad_max', $form_state->getValue('cantidad_max'));
    $config->set('linxecredit.meses_min', $form_state->getValue('meses_min'));
    $config->set('linxecredit.meses_max', $form_state->getValue('meses_max'));
    $config->set('linxecredit.tasa', $form_state->getValue('tasa'));
    $config->set('linxecredit.tasamora', $form_state->getValue('tasamora'));
    $config->set('linxecredit.seguro', $form_state->getValue('seguro'));

    $config->set('linxecredit.iva', $form_state->getValue('iva'));
    $config->set('linxecredit.cargo_tecnologia', $form_state->getValue('cargo_tecnologia'));
    $config->set('linxecredit.plazos', $form_state->getValue('plazos'));
    $config->set('linxecredit.rangomontos', $form_state->getValue('rangomontos'));

    $config->set('linxecredit.urlws_token', $form_state->getValue('urlws_token'));
    $config->set('linxecredit.username_datascoring', $form_state->getValue('username_datascoring'));
    $config->set('linxecredit.password_datascoring', $form_state->getValue('password_datascoring'));
    $config->set('linxecredit.applicationid_datascoring', $form_state->getValue('applicationid_datascoring'));
    $config->set('linxecredit.projectid_datascoring', $form_state->getValue('projectid_datascoring'));
    $config->set('linxecredit.url_pdf_contrato', $form_state->getValue('url_pdf_contrato'));

    $config->set('linxecredit.endtitlemsg_con_validacion_empresa', $form_state->getValue('endtitlemsg_con_validacion_empresa'));
    $config->set('linxecredit.endmsg_con_validacion_empresa', $form_state->getValue('endmsg_con_validacion_empresa'));
    $config->set('linxecredit.endtitlemsg_sin_validacion_empresa', $form_state->getValue('endtitlemsg_sin_validacion_empresa'));
    $config->set('linxecredit.endmsg_sin_validacion_empresa', $form_state->getValue('endmsg_sin_validacion_empresa'));

    $config->set('linxecredit.endtitlemsg_con_validacion_empresa_movi', $form_state->getValue('endtitlemsg_con_validacion_empresa_movi'));
    $config->set('linxecredit.endmsg_con_validacion_empresa_movi', $form_state->getValue('endmsg_con_validacion_empresa_movi'));
    $config->set('linxecredit.endtitlemsg_sin_validacion_empresa_movi', $form_state->getValue('endtitlemsg_sin_validacion_empresa_movi'));
    $config->set('linxecredit.endmsg_sin_validacion_empresa_movi', $form_state->getValue('endmsg_sin_validacion_empresa_movi'));

    $config->set('linxecredit.tooltip_text_nomina', $form_state->getValue('tooltip_text_nomina'));
    $config->set('linxecredit.tooltip_text_movi', $form_state->getValue('tooltip_text_movi'));

    $config->set('linxecredit.urlws_webinar', $form_state->getValue('urlws_webinar'));
    $config->set('linxecredit.urlws_querywebinar', $form_state->getValue('urlws_querywebinar'));
    
    $config->set('linxecredit.webinar_apikey', $form_state->getValue('webinar_apikey'));

    $config->set('linxecredit.adelantonomina_rangos', $form_state->getValue('adelantonomina_rangos'));
    $config->set('linxecredit.adelantonomina_montos_adelanto', $form_state->getValue('adelantonomina_montos_adelanto'));
    $config->set('linxecredit.adelantonomina_montos_salario', $form_state->getValue('adelantonomina_montos_salario'));
    $config->set('linxecredit.minimo_meses_cotizados', $form_state->getValue('minimo_meses_cotizados'));
    
    //MAREIGUA:
    $config->set('linxecredit.urlws_mareigua', $form_state->getValue('urlws_mareigua'));
    $config->set('linxecredit.username_mareigua', $form_state->getValue('username_mareigua'));
    $config->set('linxecredit.password_mareigua', $form_state->getValue('password_mareigua'));

    //PORCENTAJES ADELANTO DE NÓMINA:
    $config->set('linxecredit.cargo_administracion_adelanto', $form_state->getValue('cargo_administracion_adelanto'));
    $config->set('linxecredit.cargo_tecnologia_adelanto', $form_state->getValue('cargo_tecnologia_adelanto'));
    $config->set('linxecredit.seguro_adelanto', $form_state->getValue('seguro_adelanto'));
    $config->set('linxecredit.iva_adelanto', $form_state->getValue('iva_adelanto'));

    //DECEVAL:
    $config->set('linxecredit.urlws_deceval', $form_state->getValue('urlws_deceval'));
    $config->set('linxecredit.deceval_codigodepositante', $form_state->getValue('deceval_codigodepositante'));
    $config->set('linxecredit.deceval_usuario', $form_state->getValue('deceval_usuario'));
    $config->set('linxecredit.deceval_identificacionemisor', $form_state->getValue('deceval_identificacionemisor'));
    $config->set('linxecredit.deceval_idclasedocumento', $form_state->getValue('deceval_idclasedocumento'));
    
    //TWILIO:
    $config->set('linxecredit.twilio_sid', $form_state->getValue('twilio_sid'));
    $config->set('linxecredit.twilio_token', $form_state->getValue('twilio_token'));
    $config->set('linxecredit.twilio_phonenumber', $form_state->getValue('twilio_phonenumber'));

    //DIAS ADELANTO NÓMINA:
    $config->set('linxecredit.adelanto_dias_recordatorio_notificacion', $form_state->getValue('adelanto_dias_recordatorio_notificacion'));
    $config->set('linxecredit.adelanto_dias_eliminacion_solicitud', $form_state->getValue('adelanto_dias_eliminacion_solicitud'));

    //FACTURACIÓN ADELANTO DE NÓMINA:
    $config->set('linxecredit.adelanto_intereses_mora', $form_state->getValue('adelanto_intereses_mora'));
    $config->set('linxecredit.adelanto_dias_anteriores_corte_para_facturacion', $form_state->getValue('adelanto_dias_anteriores_corte_para_facturacion'));
    
    $config->save();
    return parent::submitForm($form, $form_state);
 
  }
 
  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'linxecredit.settings',
    ];
  }

}

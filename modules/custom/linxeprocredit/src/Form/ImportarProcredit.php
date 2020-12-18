<?php
namespace Drupal\linxeprocredit\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Database\Query;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\Routing;

/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class ImportarProcredit extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'importar_linxeprocredit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $format = 'Y-m-d';
    $date = date('Y-m-d');
    $form['fecha'] = [
      '#title' => t('Fecha de carga'),
      '#default_value' => $date,
      '#required' => TRUE,
      '#type' => 'date',
      '#date_format' => $format,
    ];

    
    $form['csv_upload'] = array(
        '#type' => 'file',
        '#title' => t('Selecciona un archivo csv para importar'),
        '#size' => 50,
        '#upload_validators' => array('file_clean_name' => array()),
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Guardar',
    );

     $form['#cache'] = [
        'max-age' => 0
    ];
  

    return $form;

  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    parent::validateForm($form, $form_state);

    $validators = array(
        'file_validate_extensions' => array("csv"),
        'file_validate_size' => array(file_upload_max_size()),
    );

    // Save the file as a temporary file.
    $file = file_save_upload('csv_upload', $validators, FALSE, 0, FILE_EXISTS_REPLACE);
    if ($file === FALSE) {
        $form_state->setErrorByName('csv_upload', $this->t('Failed to upload the file'));
    }
    elseif ($file !== NULL) {

        $form_state->setValue("csv_upload", $file->toArray());
    } 

    $fecha = $form_state->getValue('fecha');
    if($fecha == "") {
         $form_state->setErrorByName('fecha', $this->t('Selecciona la fecha de carga'));
    }
  }  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $file = $form_state->getValue('csv_upload');
    $fecha = $form_state->getValue('fecha');

    //var_dump($file);die();

    $destination = $file['uri'][0]['value'];

    $file = fopen($destination, "r");
    $i = 0;
    // read the first line and ignore it
    fgets($file); 
    $headerLine = true;
    while (($credit = fgetcsv($file, 1000, ";")) !== FALSE) {

        $field  = array(
            'tipo_registro' => $credit[$i],
            'tipo_novedad' => $credit[$i = $i+1],
            'refinacion_reestructuracion' => $credit[$i = $i+1],
            'fecha_corte_informacion' => $credit[$i = $i+1],
            'seccional_afiliado' => $credit[$i = $i+1],
            'consecutivo_afiliado' => $credit[$i = $i+1],
            'codigo_sucursal_viejo' => $credit[$i = $i+1],
            'tipo_documento_afiliado' => $credit[$i = $i+1],
            'numero_documento_afiliado' => $credit[$i = $i+1],
            'codigo_sucursal_nuevo' => $credit[$i = $i+1],
            'tipo_garante' => $credit[$i = $i+1],
            'tipo_documento_cliente' => $credit[$i = $i+1],
            'numero_documento_cliente' => $credit[$i = $i+1],
            'primernombre_cliente_razonsocial' => $credit[$i = $i+1],
            'segundonombre_cliente' => $credit[$i = $i+1],
            'primerapellido_cliente' => $credit[$i = $i+1],
            'segundoapellido_cliente' => $credit[$i = $i+1],
            'nombre_comercial' => $credit[$i = $i+1],
            'pais' => $credit[$i = $i+1],
            'departamento' => $credit[$i = $i+1],
            'ciudad' => $credit[$i = $i+1],
            'tipo_direccion' => $credit[$i = $i+1],
            'direccion' => $credit[$i = $i+1],
            'tipo_telefono' => $credit[$i = $i+1],
            'telefono' => $credit[$i = $i+1],
            'extension' => $credit[$i = $i+1],
            'tipo_ubicacion_electronica' => $credit[$i = $i+1],
            'ubicacion_electronica' => $credit[$i = $i+1],
            'cupototal_aprobado_credito' => $credit[$i = $i+1],
            'cupo_utilizado' => $credit[$i = $i+1],
            'tipo_obligacion' => $credit[$i = $i+1],
            'tipo_contrato' => $credit[$i = $i+1],
            'numero_obligacion' => $credit[$i = $i+1],
            'fecha_obligacion' => $credit[$i = $i+1],
            'periodicidad_pago' => $credit[$i = $i+1],
            'termino_contrato' => $credit[$i = $i+1],
            'meses_celebrados' => $credit[$i = $i+1],
            'meses_clausula_permanencia' => $credit[$i = $i+1],
            'valor_obligacion' => $credit[$i = $i+1],
            'cargo_fijo' => $credit[$i = $i+1],
            'saldos_fecha_corte' => $credit[$i = $i+1],
            'saldos_mora_fecha_corte' => $credit[$i = $i+1],
            'cuotas_pactadas' => $credit[$i = $i+1],
            'cuotas_pagadas' => $credit[$i = $i+1],
            'cuotas_mora' => $credit[$i = $i+1],
            'motivo_pago' => $credit[$i = $i+1],
            'situacion_estado_titular' => $credit[$i = $i+1],
            'tipo_documento_soporte_obligacion_referenciado' => $credit[$i = $i+1],
            'numero_obligacion_referenciada' => $credit[$i = $i+1],
            'fecha_carga' => $fecha,
            'proveniente_de' => 'Datascoring',
            'tipo_producto' => 'Libranza'

        
        );
        $i = 0;
        $query = \Drupal::database();
        $query->insert('linxeprocredit')
            ->fields($field)
            ->execute();    
    }
    drupal_set_message('Importacion realizada correctamente!');
    $url = \Drupal\Core\Url::fromRoute('linxeprocredit.ver_procredit');
    $form_state->setRedirectUrl($url); 



  }
}
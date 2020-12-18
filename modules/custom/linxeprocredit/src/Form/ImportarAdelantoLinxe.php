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
class ImportarAdelantoLinxe extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'importar_adelantolinxe_form';
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

    
    $format = 'Y-m-d';
    $date = date('Y-m-d');
    $form['desde'] = [
      '#title' => t('Desde'),
      '#default_value' => $date,
      '#required' => TRUE,
      '#type' => 'date',
      '#date_format' => $format,
    ];

    $form['hasta'] = [
        '#title' => t('Hasta'),
        '#default_value' => $date,
        '#required' => TRUE,
        '#type' => 'date',
        '#date_format' => $format,
    ];

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Importar Adelantos de Nómina',
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

    $fecha = $form_state->getValue('fecha');
    if($fecha == "") {
         $form_state->setErrorByName('fecha', $this->t('Selecciona la fecha de carga'));
    }
  }  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $fecha = $form_state->getValue('fecha');
    $desde = $form_state->getValue('desde');
    $hasta = $form_state->getValue('hasta');
    $desde = $desde.' 00:00:00';
    $hasta = $hasta.' 23:59:59';

    $liquidacion = db_select('registrados_an', 'r') ;               
    $liquidacion->join('adelantos_nomina', 'an', 'an.idregistro = r.idregistro');
    $liquidacion->join('empresas', 'e', 'r.idempresa = e.idempresa');
    $liquidacion->fields('e',['tipo_identificacion','identificacion','razon_social','direccion','telefono','email']);
    $liquidacion->fields('an',["idadelanto","estado_general_solicitud","fecha_solicitud",'monto_maximo_aprobado',"cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
    $liquidacion->fields('r',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
    $liquidacion->condition('an.estado_general_solicitud','en_proceso_liquidacion');
    $liquidacion->condition('an.fecha_hora_liquidacion',[$desde,$hasta],'BETWEEN');

    $liquidar = $liquidacion->execute()->fetchAll();

    
    $desembolso = db_select('registrados_an', 'r') ;               
    $desembolso->join('adelantos_nomina', 'an', 'an.idregistro = r.idregistro');
    $desembolso->join('empresas', 'e', 'r.idempresa = e.idempresa');
    $desembolso->fields('e',['tipo_identificacion','identificacion','razon_social','direccion','telefono','email']);
    $desembolso->fields('an',["idadelanto","estado_general_solicitud","fecha_solicitud",'monto_maximo_aprobado',"cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
    $desembolso->fields('r',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
    $desembolso->condition('an.estado_general_solicitud','desembolsado');
    $desembolso->condition('an.fecha_hora_desembolso',[$desde,$hasta],'BETWEEN');

    $results = $desembolso->execute()->fetchAll();

    foreach($results as $row){
        $select = db_select('linxeprocredit', 'lp')
                ->condition('lp.idcarga', $row->idadelanto)
                ->fields('lp')->execute();
        $select->allowRowCount = TRUE;
        $count = $select->rowCount();

        $field  = array(
            'fecha_corte_informacion' => date("dmY",strtotime($row->fecha_solicitud)),//AN.FECHA_SOLICITUD
            'tipo_documento_afiliado' => $row->tipodocumento,//R.TIPODOCUMENTO
            'numero_documento_afiliado' => $row->documento,//R.DOCUMENTO
            'tipo_documento_cliente' => $row->tipo_identificacion,//E.TIPO_IDENTIFICACION
            'numero_documento_cliente' => $row->identificacion,//E.IDENTIFICACION
            'primernombre_cliente_razonsocial' => $row->nombre,//R.NOMBRE
            'primerapellido_cliente' => $row->primer_apellido,//R.PRIMER_APELLIDO
            'segundoapellido_cliente' => $row->segundo_apellido,//R.SEGUNDO_APELLIDO
            'nombre_comercial' => $row->razon_social,//E.RAZON_SOCIAL
            'pais' => '57',
            'direccion' => $row->direccion,//E.DIRECCION
            'telefono' => $row->telefono,//E.TELEFONO
            'ubicacion_electronica' => $row->email,//E.EMAIL
            'cupototal_aprobado_credito' => $row->valor_solicitado + $row->administracion + $row->seguros + $row->tecnologia + $row->iva,//liq.valor
            'cupo_utilizado' => $row->saldo_pendiente,//A.SALDO_PENDIENTE
            'fecha_obligacion' => date("dmY",strtotime($row->fecha_hora_desembolso)),//A.FECHA_HORA_DESEMBOLSO
            'periodicidad_pago' => '30',
            'valor_obligacion' => $row->valor_solicitado + $row->administracion + $row->seguros + $row->tecnologia + $row->iva,//liq.valor_pagado
            'cargo_fijo' => '0',
            'saldos_fecha_corte' => $row->saldo_pendiente,//a.saldo_pendiente
            'cuotas_pactadas' => $row->cuotas_pactadas,
            'cuotas_pagadas' => $row->cuota_actual,
            'fecha_carga' => $fecha,
            'proveniente_de' => 'Linxe',
            'idcarga' => $row->idadelanto,
            'tipo_producto' => 'Adelanto'
        
        );
        if($count > 0){
            $query = \Drupal::database();
            $query->update('linxeprocredit')
                ->condition('idcarga', $row->idadelanto)
                ->fields($field)
                ->execute();    

        }else{
            $query = \Drupal::database();
            $query->insert('linxeprocredit')
                ->fields($field)
                ->execute();    
    
        }
    
    }

    foreach($liquidar as $row){
        $select2 = db_select('linxeprocredit', 'lp')
                ->condition('lp.idcarga', $row->idadelanto)
                ->fields('lp')->execute();
        $select2->allowRowCount = TRUE;
        $count2 = $select2->rowCount();

        $field  = array(
            'fecha_corte_informacion' => date("dmY",strtotime($row->fecha_solicitud)),//AN.FECHA_SOLICITUD
            'tipo_documento_afiliado' => $row->tipodocumento,//R.TIPODOCUMENTO
            'numero_documento_afiliado' => $row->documento,//R.DOCUMENTO
            'tipo_documento_cliente' => $row->tipo_identificacion,//E.TIPO_IDENTIFICACION
            'numero_documento_cliente' => $row->identificacion,//E.IDENTIFICACION
            'primernombre_cliente_razonsocial' => $row->nombre,//R.NOMBRE
            'primerapellido_cliente' => $row->primer_apellido,//R.PRIMER_APELLIDO
            'segundoapellido_cliente' => $row->segundo_apellido,//R.SEGUNDO_APELLIDO
            'nombre_comercial' => $row->razon_social,//E.RAZON_SOCIAL
            'pais' => 'COLOMBIA',
            'direccion' => 'V CLLE 116 12 20',//E.DIRECCION
            'tipo_telefono' => '7328963',//E.DIRECCION
            'telefono' => $row->telefono,//E.TELEFONO
            'ubicacion_electronica' => $row->email,//E.EMAIL
            'cupototal_aprobado_credito' => $row->valor_solicitado + $row->administracion + $row->seguros + $row->tecnologia + $row->iva,//liq.valor
            'cupo_utilizado' => $row->saldo_pendiente,//A.SALDO_PENDIENTE
            'fecha_obligacion' => date("dmY",strtotime($row->fecha_hora_desembolso)),//A.FECHA_HORA_DESEMBOLSO
            'periodicidad_pago' => '30',
            'valor_obligacion' => $row->valor_solicitado + $row->administracion + $row->seguros + $row->tecnologia + $row->iva,//liq.valor_pagado
            'cargo_fijo' => '0',
            'saldos_fecha_corte' => $row->saldo_pendiente,//a.saldo_pendiente
            'cuotas_pactadas' => $row->cuotas_pactadas,
            'cuotas_pagadas' => $row->cuota_actual,
            'fecha_carga' => $fecha,
            'proveniente_de' => 'Linxe',
            'idcarga' => $row->idadelanto,
            'tipo_producto' => 'Adelanto',
            'tipo_obligacion' => '12',
            'tipo_garante' =>'1',
            'tipo_contrato' =>'4',
            'termino_contrato' =>'0',
            'situacion_estado_titular' => '1',
            'departamento' => 'DISTRITO CAPITAL',
            'ciudad' => 'BOGOTA DISTRITO CAPITAL',
            'tipo_direccion' => '5',


        
        );

        if($count > 0){
            $query = \Drupal::database();
            $query->update('linxeprocredit')
                ->condition('idcarga', $row->idadelanto)
                ->fields($field)
                ->execute();    

        }else{
            $query = \Drupal::database();
            $query->insert('linxeprocredit')
                ->fields($field)
                ->execute();    
    
        }
    
    }

    drupal_set_message('Importacion realizada correctamente!');
    $url = \Drupal\Core\Url::fromRoute('linxeprocredit.ver_procredit');
    $form_state->setRedirectUrl($url); 



  }
}
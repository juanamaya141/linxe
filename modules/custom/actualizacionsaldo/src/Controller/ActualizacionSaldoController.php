<?php

namespace Drupal\linxeprocredit\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;


class ProcreditController extends ControllerBase {

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function adelantos_data() {
    $proveniente = \Drupal::request()->query->get('proveniente');
    $desde = \Drupal::request()->query->get('desde');
    $hasta = \Drupal::request()->query->get('hasta');

    $form['importar'] = array(
        '#type' => 'submit',
        '#value' => t('IMPORTAR'),
        '#attributes' => array('onclick' => 'window.location.href="/admin/linxeprocredit/importar";'),
    );  
    $form['adelantos_nomina'] = array(
        '#type' => 'submit',
        '#value' => t('Generar registros desde Adelantos de Nómina'),
        '#attributes' => array('onclick' => 'window.location.href="/admin/linxeprocredit/importar-adelantos-nomina";'),
    );  

    $form['delete_procredit'] = array(
        '#type' => 'submit',
        '#value' => t('Vaciar Registros de Tabla'),
        '#attributes' => array('onclick' => 'window.location.href="/admin/linxeprocredit/delete-procredit";'),
    );  


    
    //====load filter controller
    $form['form'] = $this->formBuilder()->getForm('Drupal\linxeprocredit\Form\ProcreditFiltrar');
    /*
    */
    //====load filter controller
    $form['form_2'] = $this->formBuilder()->getForm('Drupal\linxeprocredit\Form\ExportProcredit');

    //create table header
    $header_table = array(
        'tipo_registro' => t('Tipo de Registro'),
        'tipo_novedad' => t('Tipo de Novedad'),
        'refinacion_reestructuracion' => t('Refinanciacion / Reestructuracion'),
        'fecha_corte_informacion' => t('Fecha de Corte Informacion'),
        'seccional_afiliado' => t('Seccional del Afiliado'),
        'consecutivo_afiliado' => t('Consecutivo del Afiliado'),
        'codigo_sucursal_viejo' => t('Codigo Sucursal Viejo'),
        'tipo_documento_afiliado' => t('Tipo de Documento Afiliado'),
        'numero_documento_afiliado' => t('Numero de Documento Afiliado'),
        'codigo_sucursal_nuevo' => t('Codigo Sucursal Nuevo'),
        'tipo_garante' => t('Tipo de Garante'),
        'tipo_documento_cliente' => t('Tipo de Documento Cliente'),
        'numero_documento_cliente' => t('Numero de Documento Cliente'),
        'primernombre_cliente_razonsocial' => t('Primer Nombre Cliente / Razon Social'),
        'segundonombre_cliente' => t('Segundo Nombre Cliente'),
        'primerapellido_cliente' => t('Primer Apellido Cliente'),
        'segundoapellido_cliente' => t('Segundo Apellido Cliente'),
        'nombre_comercial' => t('Nombre Comercial'),
        'pais' => t('Pais'),
        'departamento' => t('Departamento'),
        'ciudad' => t('Ciudad'),
        'tipo_direccion' => t('Tipo de Direccion'),
        'direccion' => t('Direccion'),
        'tipo_telefono' => t('Tipo Telefono'),
        'telefono' => t('Telefono'),
        'extension' => t('Extension'),
        'tipo_ubicacion_electronica' => t('Tipo Ubicacion Electronica'),
        'ubicacion_electronica' => t('Ubicacion Electronica'),
        'cupototal_aprobado_credito' => t('Cupo Total Aprobado / Cupo Credito'),
        'cupo_utilizado' => t('Cupo Utilizado'),
        'tipo_obligacion' => t('Tipo Obligacion'),
        'tipo_contrato' => t('Tipo de Contrato'),
        'numero_obligacion' => t('Numero Obligacion'),
        'fecha_obligacion' => t('Fecha Obligacion'),
        'periodicidad_pago' => t('Periodicidad de Pago'),
        'termino_contrato' => t('Termino del Contrato'),
        'meses_celebrados' => t('Meses Celebrados'),
        'meses_clausula_permanencia' => t('Meses de Clausula de Permanencia'),
        'valor_obligacion' => t('Valor Obligacion'),
        'cargo_fijo' => t('Cargo Fijo'),
        'saldos_fecha_corte' => t('Saldos a Fecha de Corte'),
        'saldos_mora_fecha_corte' => t('Saldo en Mora a Fecha de Corte'),
        'cuotas_pactadas' => t('Cuotas Pactadas'),
        'cuotas_pagadas' => t('Cuotas Pagadas'),
        'cuotas_mora' => t('Cuotas en Mora'),
        'motivo_pago' => t('Motivo de Pago'),
        'situacion_estado_titular' => t('Situacion o Estado del Titular'),
        'tipo_documento_soporte_obligacion_referenciado' => t('Tipo de Documento Soporte de la Obligacion Referenciado'),
        'numero_obligacion_referenciada' => t('Numero Obligacion Referenciada'),
        'fecha_carga' => t('Fecha de Carga'),
        'proveniente_de' => t('Proveniente de')
    );

    if($proveniente == "" && $desde == "" && $hasta == "" ){
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_procredit("All","","",""),
        '#empty' => $this->t('No se encontraron registros'),
        ];
    }else{
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_procredit("",$proveniente,$desde,$hasta),
        '#empty' => $this->t('No se encontraron registros'),
        ];
    }

    $form['pager'] = [
        '#type' => 'pager'
    ];

    return $form;

  }

}

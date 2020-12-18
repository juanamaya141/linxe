<?php

namespace Drupal\reporteadelanto\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\reporteadelanto\Controller
 */
class TablaController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'reporteadelanto_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function show() {
    /**return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: display with parameter(s): $name'),
    ];*/
    $empresas = \Drupal::request()->query->get('empresas');
    $estado = \Drupal::request()->query->get('estado');
    $desde = \Drupal::request()->query->get('desde');
    $hasta = \Drupal::request()->query->get('hasta');


    //====load filter controller
    $form['form'] = $this->formBuilder()->getForm('Drupal\reporteadelanto\Form\Filtros');

    //====load filter controller
    $form['form_2'] = $this->formBuilder()->getForm('Drupal\reporteadelanto\Form\Descarga');

    //create table header
    $header_table = array(
        'idadelanto'=>    t('Número de adelanto'),
        'tipodocumento' => t('Tipo de documento'),
        'documento' => t('Documento'),
        'nombreCompleto' => t('Nombre Completo'),
        'valorAdelanto' => t('Valor Adelanto de nómina'),
        'saldo_pendiente' => t('Saldo al corte'),
        'num_cuota' => t('Número de cuota'),
        'saldo_mora' => t('Saldo en mora'),
        'intereses_mora' => t('Interés mora'),
        'valor' => t('Valor Facturado'),
        'fecha_hora_facturacion' => t('Fecha hora facturación'),
        'valor_pagado' => t('Valor Pagado'),
        'fecha_hora_pago' => t('Fecha hora pago'),
        'valor_conciliado' => t('Valor Conciliado'),
        'fecha_hora_conciliacion' => t('Fecha hora conciliación'),
        'estado' => t('Estado')
    );

    if($empresas == "" && $estado == "" && $desde == "" && $hasta == "" ){
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_reporteadelanto("All","","","",""),
        '#empty' => $this->t('No se encontraron registros'),
        ];
    }else{
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_reporteadelanto("",$empresas,$estado,$desde,$hasta),
        '#empty' => $this->t('No se encontraron registros'),
        ];
    }

    $form['pager'] = [
        '#type' => 'pager'
    ];

    return $form;

  }

}

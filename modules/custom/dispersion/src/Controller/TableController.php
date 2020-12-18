<?php

namespace Drupal\dispersion\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;


class TableController extends ControllerBase {


  public function getContent() {
    $build = [
      'description' => [
        '#theme' => 'dispersion_description',
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
  public function mostrar() {

    $id = \Drupal::request()->query->get('id');

    //create table header
    $header_table = array(
        'referencia'=>    t('Referencia de Adelanto'),
        'nombres' => t('Nombres'),
        'apellidos' => t('Apellidos'),
        'tipoDocumento' => t('Tipo Documento'),
        'documento' => t('Documento'),
        'banco' => t('Banco'),
        'numeroProducto' => t('Numero de producto o servicio'),
        'estadoDesembolso' => t('Estado de desembolso'),
        'fechaDesembolso' => t('Fecha y hora del desembolso'),
        'saldo' => t('Saldo pendiente'),
        'estadoSolicitud' => t('Estado general de solicitud')
    );

    $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_results($id),
        '#empty' => $this->t('No se encontraron registros'),
    ];

    $form['pager'] = [
        '#type' => 'pager'
    ];

    return $form;

  }

}

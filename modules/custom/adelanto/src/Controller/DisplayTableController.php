<?php

namespace Drupal\adelanto\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\adelanto\Controller
 */
class DisplayTableController extends ControllerBase
{
  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'adelanto_description',
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
  public function display() {
    /**return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: display with parameter(s): $name'),
    ];*/
    $filtro = \Drupal::request()->query->get('filtro');

    //====load filter controller
    $form['form'] = $this->formBuilder()->getForm('Drupal\adelanto\Form\Filtrar');

    //====load filter controller
    $form['form_2'] = $this->formBuilder()->getForm('Drupal\adelanto\Form\Descargar');

    //create table header
    $header_table = array(
        'nombres'=>    t('Nombres'),
        'primerApellido' => t('Primer Apellido'),
        'segundoApellido' => t('Segundo Apellido'),
        'tipoDocumento' => t('Tipo Documento'),
        'documento' => t('Documento'),
        'fechaAdelantoNomina' => t('Fecha solicitud Adelanto nómina'),
        'nit' => t('Nit Empresa'),
        'nombreEmpresa' => t('Nombre Empresa'),
        'maximoMonto' => t('Máximo Monto Aprobado '),
        'valor' => t('Valor Solicitado'),
        'administracion' => t('Administración'),
        'seguros' => t('Seguros'),
        'tecnologia' => t('Tecnologia'),
        'iva' => t('IVA'),
        'tipoCuenta' => t('Tipo de cuenta'),
        'numeroCuenta' => t('Número de cuenta'),
        'banco' => t('Banco'),
        'estadoSolicitud' => t('Estado Solicitud')
    );

    if($filtro == "" ){
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_adelanto("All",""),
        '#empty' => $this->t('No se encontraron registros'),
        ];
    }else{
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_adelanto("",$filtro),
        '#empty' => $this->t('No se encontraron registros'),
        ];
    }

    $form['pager'] = [
        '#type' => 'pager'
    ];

    return $form;

  }

}

<?php

namespace Drupal\linxeseguridadsocial\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\linxeseguridadsocial\Controller
 */
class SeguridadController extends ControllerBase {


  public function getTheme() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'linxeseguridadsocial_description',
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
    public function seguridad() {
    
        $form['crear'] = array(
            '#type' => 'submit',
            '#value' => t('CREAR'),
            '#attributes' => array('onclick' => 'window.location.href="/admin/linxeseguridadsocial/registrar-seguridad";'),
        );        
        
        //create table header
        $header_table = array(
            'iden'=>  t('Id'),
            'tipo_entidad' => t('Tipo de Entidad'),
            'nombre' => t('Nombre'),
            'alias' => t('Alias'),
            'estado' => t('Estado'),
            'editar' => t('Editar'),
            'eliminar' => t('Eliminar')
        );

        $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => get_linxeseguridadsocial(),
            '#empty' => $this->t('No se encontraron registros'),
        ];

        $form['pager'] = [
            '#type' => 'pager'
        ];

        return $form;

    }

}

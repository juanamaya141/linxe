<?php

namespace Drupal\confirmacionempresas\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\node\Entity\Node;
use Drupal\UtilitiesModule\Controller\UtilitiesController;

 
class ConfirmacionController extends ControllerBase {
  public function index() {

    $uid = \Drupal::currentUser()->id();  //obtenemos el uid del usuario logueado

    $header_table = array(
      'nombre'=>    t('Nombre'),
      'primer_apellido' => t('Primer Apellido'),
      'segundo_apellido' => t('Segundo Apellido'),
      'tipo_documento' => t('Tipo Documento'),
      'documento' => t('Documento'),
      'fecha_solicitud' => t('Fecha Solicitud'),
      'valor_solicitado' => t('Valor Solicitado'),
      'cuenta_bancaria' => t('Cuenta Bancaria Nómina'),
      'opt' => t('Editar Cuenta Bancaria'),
      'opt1' => t('Rechazar Adelanto Salario'),
    );
 
    $db = \Drupal::database();
    $query = $db->select('adelantos_nomina','an');
    $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
    $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
    $query->fields('an');
    $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
    $query->condition('an.estado_general_solicitud',"validacion_desembolso");
    $query->condition('emp.iduser',$uid);
    
    // The actual action of sorting the rows is here.
    $table_sort = $query->extend('Drupal\Core\Database\Query\TableSortExtender')
                        ->orderByHeader($header_table);
    // Limit the rows to 20 for each page.
    $pager = $table_sort->extend('Drupal\Core\Database\Query\PagerSelectExtender')
                        ->limit(20);
    $result = $pager->execute();
 
    // Populate the rows.
    $rows = array();
    

    foreach($result as $data){

      $rechazar = Url::fromUserInput('/admin/confirmacionempresas/rechazar/'.$data->idadelanto);
      $edit   = Url::fromUserInput('/admin/confirmacionempresas/editarcuenta/'.$data->idadelanto);

      $rows[] = array(
        'nombre'=>    $data->nombre,
        'primer_apellido' => $data->primer_apellido,
        'segundo_apellido' => $data->segundo_apellido,
        'tipo_documento' => $data->tipodocumento,
        'documento' => $data->documento,
        'fecha_solicitud' => $data->fecha_solicitud,
        'valor_solicitado' => $data->valor_solicitado,
        'cuenta_bancaria' => $data->banco."   ".$data->tipo_cuenta." # ".$data->numero_cuenta."",
        \Drupal::l('Editar Cuenta', $edit),
        \Drupal::l('Rechazar Cuenta', $rechazar),
      );

    }
 
    // The table description.
    $build = array(
      //'#markup' => t('Listar todas')
    );
 
    // Generate the table.
    $urlaprobar = Url::fromUserInput('/admin/confirmacionempresas/aprobar', array('attributes' => array('class' => 'button')));
    $build['config_table'] = array(
      '#theme' => 'table',
      '#prefix' => '<div id="people">'.\Drupal::l("Aprobar Adelantos de Salario", $urlaprobar),
      '#suffix' => '</div>',
      '#header' => $header_table,
      '#rows' => $rows,
    );
 
    // Finally add the pager.
    $build['pager'] = array(
      '#type' => 'pager'
    );
 
    return $build;
  }

  
}
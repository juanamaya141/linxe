<?php
namespace Drupal\reporteadelanto\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;

/**
 * Provides the form for filter Students.
 */
class Filtros extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'reporteadelanto_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['empresas']) && isset($_GET['estado'])) {
        $query = $conn->select('liquidaciones_an', 'liq');
        $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
        $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
        $query->fields('emp');
        $query->fields('liq');
        $query->fields('an')
                ->condition('emp.razon_social', $_GET['empresas'])
                ->condition('liq.estado', $_GET['estado']);
        $record = $query->execute()->fetchAssoc();
    }
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    $uid = \Drupal::currentUser()->id();  //obtenemos el uid del usuario logueado

    $arrayEmpresas = array();
    $query = $conn->select('empresas', 'e');
    $query->fields('e')
            ->condition('e.convenio_tipoproducto', 'adelanto')
            ->condition('e.estado_convenio', 'aceptado');
    if($roles[1] == "empresa_linxe"){
        $query->condition('e.iduser',$uid);
    }

    $arrayEmpresas = $query->execute()->fetchAll();

    $empresas = ['' => 'Seleccione'];
    foreach ($arrayEmpresas as $results) {
        $empresas[$results->razon_social] = t($results->razon_social);
    }


    $form['filters'] = [
        '#type'  => 'fieldset',
        '#title' => $this->t('Filtrar'),
        '#open'  => true,
    ];

    $form['filters']['empresas'] = [
        '#type' => 'select',
        '#title' => ('Seleccione una empresa'),
        '#default_value' => ($_GET['empresas']) ? $_GET['empresas']:'',
        '#required' => TRUE,
        '#options' => $empresas,
    ];

    $form['filters']['estado'] = [
        '#type' => 'select',
        '#title' => ('Seleccione un estado'),
        '#required' => TRUE,
        '#default_value' => ($_GET['estado']) ? $_GET['estado']:'',
        '#options' => array(
            '' => t('Seleccione'),
            'facturado' => t('Facturado'),
            'pagado' => t('Pagado'),
            'conciliado' => t('Conciliado'),
        ),
    ];

    $format = 'Y-m-d';
    $date = date('Y-m-d');
    $form['filters']['desde'] = [
      '#title' => t('Desde'),
      '#default_value' => ($_GET['desde']) ? $_GET['desde']:$date,
      '#type' => 'date',
      '#date_format' => $format,
    ];

    $form['filters']['hasta'] = [
        '#title' => t('Hasta'),
        '#default_value' => ($_GET['hasta']) ? $_GET['hasta']:$date,
        '#type' => 'date',
        '#date_format' => $format,
    ];

    $form['filters']['actions'] = [
        '#type'       => 'actions'
    ];

    $form['filters']['actions']['submit'] = [
        '#type'  => 'submit',
        '#value' => $this->t('Filtrar')
		
    ];
   
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
     
    if ( $form_state->getValue('empresas') == "") {
        $form_state->setErrorByName('empresas', $this->t('Debes seleccionar un valor'));
    }

    if ( $form_state->getValue('estado') == "") {
        $form_state->setErrorByName('estado', $this->t('Debes seleccionar un valor'));
    }

    if ( $form_state->getValue('desde') == "") {
        $form_state->setErrorByName('desde', $this->t('Debes seleccionar un valor'));
    }

    if ( $form_state->getValue('hasta') == "") {
        $form_state->setErrorByName('hasta', $this->t('Debes seleccionar un valor'));
    }
	 
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {	  
        $field = $form_state->getValues();
        $empresa = $field["empresas"];
        $estado = $field["estado"];
        $desde = $field["desde"];
        $hasta = $field["hasta"];

        $url = \Drupal\Core\Url::fromRoute('reporteadelanto.show_table')
            ->setRouteParameters(array('empresas'=>$empresa,'estado'=>$estado,'desde'=>$desde,'hasta'=>$hasta));
        $form_state->setRedirectUrl($url); 
  }

}

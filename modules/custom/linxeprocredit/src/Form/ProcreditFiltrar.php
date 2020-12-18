<?php
namespace Drupal\linxeprocredit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;

/**
 * Provides the form for filter Students.
 */
class ProcreditFiltrar extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'procredit_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['proveniente']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
        
        $query = $conn->select('linxeprocredit','lp');
        $query->fields('lp');

        $proveniente = $_GET['proveniente'];
        $desde = $_GET['desde'];
        $hasta = $_GET['hasta'];

        if($proveniente !== ""){
            $query->condition('lp.proveniente_de',$proveniente);
        }

        if($desde !== "" && $hasta !== "" ){
            $query->condition('lp.fecha_carga',[$desde,$hasta],'BETWEEN');
        }

        $record = $query->execute()->fetchAssoc();        
    }

    $form['filters'] = [
        '#type'  => 'fieldset',
        '#title' => $this->t('Filtrar información'),
        '#open'  => true,
    ];

    $form['filters']['proveniente_a'] = [
        '#type' => 'select',
        '#title' => ('Proveniente a'),
        '#default_value' => ($_GET['proveniente']) ? $_GET['proveniente']:'',
        '#options' => array(
            '' => t('Seleccione'),
            'Datascoring' => t('Datascoring'),
            'Linxe' => t('Linxe'),
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
     
    if ( $form_state->getValue('proveniente_a') == "" && $form_state->getValue('desde') == "" && $form_state->getValue('hasta') == "") {
        $form_state->setErrorByName('proveniente_a', $this->t('Debes seleccionar un valor'));
    }
	 
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {	  
        $field = $form_state->getValues();
        $proveniente = $field["proveniente_a"];
        $desde = $field["desde"];
        $hasta = $field["hasta"];

        $url = \Drupal\Core\Url::fromRoute('linxeprocredit.ver_procredit')
            ->setRouteParameters(array('proveniente'=>$proveniente,'desde'=>$desde,'hasta'=>$hasta));
        $form_state->setRedirectUrl($url); 
  }

}

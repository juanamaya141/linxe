<?php
namespace Drupal\adelanto\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;

/**
 * Provides the form for filter Students.
 */
class Filtrar extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'adelanto_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['filtro'])) {
        
        $query = $conn->select('adelantos_nomina', 'an')
            ->fields('an')
            ->condition('an.estado_general_solicitud', $_GET['filtro']);
            
        $record = $query->execute()->fetchAssoc();

        
    }

    $form['filters'] = [
        '#type'  => 'fieldset',
        '#title' => $this->t('Filtrar'),
        '#open'  => true,
    ];

    $form['filters']['filtro'] = [
        '#type' => 'select',
        '#title' => ('Filtrar'),
        '#required' => TRUE,
        '#default_value' => ($_GET['filtro']) ? $_GET['filtro']:'',
        '#options' => array(
            '' => t('Seleccione'),
            'aprobacion_empresa' => t('Aprobación Empresa'),
            'en_proceso_desembolso' => t('En proceso de desembolso'),
        ),
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
     
    if ( $form_state->getValue('filtro') == "") {
        $form_state->setErrorByName('filtro', $this->t('Debes seleccionar un valor'));
    }
	 
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {	  
        $field = $form_state->getValues();
        $filtro = $field["filtro"];
        $url = \Drupal\Core\Url::fromRoute('adelanto.display_table_controller_display')
            ->setRouteParameters(array('filtro'=>$filtro));
        $form_state->setRedirectUrl($url); 
  }

}

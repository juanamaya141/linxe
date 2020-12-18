<?php
namespace Drupal\reportehistorico\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;

/**
 * Provides the form for filter Students.
 */
class FiltrosReporte extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'reportehistorico_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['empresas']) && isset($_GET['estado'])) {
        $query = $conn->select('adelantos_nomina','an');
        $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $query->fields('an');
        $query->fields('reg');
        $query->condition('an.estado_general_solicitud',$estado)
                ->condition('reg.idempresa',$empresas);
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

    $arrayEmpresas = $query->execute()->fetchAll();

    $empresas = ['' => 'Seleccione'];
    foreach ($arrayEmpresas as $results) {
        $empresas[$results->idempresa] = t($results->razon_social);
    }


    $form['filters'] = [
        '#type'  => 'fieldset',
        '#title' => $this->t('Filtrar'),
        '#open'  => true,
    ];
    if($roles[1] == "empresa_linxe"){
        //CHECK EMPRESA
        $select = db_select('empresas', 'e')
            ->condition('e.iduser', $uid)
            ->fields('e');
        $resultado = $select->execute()->fetchObject();

        $form['filters']['empresas'] = [
            '#type' => 'hidden',
            '#value' => $resultado->idempresa,
        ];
        
    }else{
        $form['filters']['empresas'] = [
            '#type' => 'select',
            '#title' => ('Seleccione una empresa'),
            '#default_value' => ($_GET['empresas']) ? $_GET['empresas']:'',
            '#options' => $empresas,
        ];
    
    }

    $form['filters']['estado'] = [
        '#type' => 'select',
        '#title' => ('Seleccione un estado'),
        '#default_value' => ($_GET['estado']) ? $_GET['estado']:'',
        '#options' => array(
            '' => t('Seleccione'),
            'solicitada' => t('Solicitada'),
            'aprobada' => t('Aprobada'),
            'rechazada' => t('Rechazada'),
            'seleccion_monto' => t('Seleccion monto'),
            'validacion_desembolso' => t('Validación desembolso'),
            'rechazado_empresa' => t('Rechazado empresa'),
            'en_proceso_desembolso' => t('En proceso desembolso'),
            'desembolsado' => t('Desembolsado empresa'),
            'en_proceso_liquidacion' => t('En proceso liquidacion'),
            'liquidado' => t('Liquidado')

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
     
    if ( $form_state->getValue('empresas') == "" && $form_state->getValue('estado') == "" && $form_state->getValue('desde') == "" && $form_state->getValue('hasta') == "") {
        $form_state->setErrorByName('empresas', $this->t('Debes seleccionar un valor'));
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

        $url = \Drupal\Core\Url::fromRoute('reportehistorico.ver_tabla')
            ->setRouteParameters(array('empresas'=>$empresa,'estado'=>$estado,'desde'=>$desde,'hasta'=>$hasta));
        $form_state->setRedirectUrl($url); 
  }

}

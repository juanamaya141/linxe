<?php
namespace Drupal\confirmacionempresas\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class MydataForm.
 *
 * @package Drupal\confirmacionempresas\Form
 */
class AprobarAdelantos extends ConfirmFormBase 
{
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'aprobar_adelantos_form';
  }

  public function getQuestion() { 
    return t('¿Deseas aprobar todos los adelantos de salario de la lista?');
  }

  public function getCancelUrl() {
      return new Url('confirmacionempresas.index');
  }

  public function getDescription() {
      return t('No se podrán deshacer los cambios');
  }
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
      return t('Aprobar Adelantos');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
      return t('Cancelar');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
      return parent::buildForm($form, $form_state);
  }

  /**
      * {@inheritdoc}
      */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uid = \Drupal::currentUser()->id();  //obtenemos el uid del usuario logueado

    $db = \Drupal::database();
    $query = $db->select('adelantos_nomina','an');
    $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
    $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
    $query->fields('an');
    $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
    $query->condition('an.estado_general_solicitud',"validacion_desembolso");
    $query->condition('emp.iduser',$uid);
    
    $result = $query->execute();
    $arrayIDsAdelanto = [];
    foreach($result as $data){
      $arrayIDsAdelanto[]=$data->idadelanto;
    }

    $currentDate = date("Y-m-d H:i:s");
    $fields  = array(
        'autorizacion_desembolso_empresa'   => 1,
        'estado_desembolso'   => 'aprobacion_empresa',
        'estado_general_solicitud'   => 'aprobacion_empresa',
        'fecha_hora_desembolso'   => $currentDate
    );
    $update = $db->update('adelantos_nomina')
        ->fields($fields)
        ->condition('idadelanto',$arrayIDsAdelanto,'in')
        ->execute();


    if($update > 0)
    {
        drupal_set_message("Adelantos de Salario aprobados satisfactoriamente");
    }else{
        drupal_set_message("Adelantos de salario no pudieron ser aprobados, inténtelo nuevamente.");    
    }
    $response = new RedirectResponse("/admin/confirmacionempresas");
    $response->send();
  }
}
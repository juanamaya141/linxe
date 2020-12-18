<?php
namespace Drupal\confirmacionempresas\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class MydataForm.
 *
 * @package Drupal\confirmacionempresas\Form
 */
class EditarCuenta extends FormBase 
{
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'editar_cuenta_form';
  }

  public $idadelanto;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

    $conn = Database::getConnection();
    $record = array();
    $this->idadelanto = $id;
    if (isset($this->idadelanto)) {
        $query = $conn->select('adelantos_nomina', 'an')
            ->condition('an.idadelanto', $this->idadelanto )
            ->fields('an');
        $record = $query->execute()->fetchAssoc();
    }
    

    $arrayBcos = ["BANCO DE BOGOTÁ",
    "BANCO POPULAR",
    "ITAÚ CORPBANCA COLOMBIA S.A.",
    "BANCOLOMBIA S.A.",
    "CITIBANK COLOMBIA",
    "GNB SUDAMERIS S.A.",
    "BBVA COLOMBIA",
    "COLPATRIA",
    "BANCO DE OCCIDENTE",
    "BANCO CAJA SOCIAL - BCSC S.A.",
    "BANCO AGRARIO DE COLOMBIA S.A.",
    "BANCO DAVIVIENDA S.A.",
    "BANCO AV VILLAS",
    "BANCO W S.A.",
    "BANCO CREDIFINANCIERA S.A.C.F",
    "BANCAMIA",
    "BANCO PICHINCHA S.A.",
    "BANCOOMEVA",
    "CMR FALABELLA S.A.",
    "BANCO FINANDINA S.A.",
    "BANCO SANTANDER DE NEGOCIOS COLOMBIA S.A.",
    "BANCO COOPERATIVO COOPCENTRAL",
    "BANCO COMPARTIR S.A",
    "BANCO SERFINANZA S.A"];

    $arrayBancos[""] = "" ;
    foreach($arrayBcos as $key=>$bco){
      $arrayBancos[$bco]=$bco;
    }
    
    $form['banco'] = array(
      '#type' => 'select',
      '#title' => t('Banco:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['banco']) ) ? $record['banco']:'',
      '#label_display' =>'after',
      '#options' => $arrayBancos,
      '#attributes' => array(
        'data-msj' => t("Banco donde tienes tu cuenta de nómina, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $arrayTipoCta[""] = "" ;
    $arrayTipoCta["ahorros"] = "Cuenta de Ahorros" ;
    $arrayTipoCta["corriente"] = "Cuenta Corriente" ;


    $form['tipo_cuenta'] = array(
      '#type' => 'select',
      '#title' => t('Tipo de Cuenta de Nómina:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['tipo_cuenta']) ) ? $record['tipo_cuenta']:'',
      '#label_display' =>'after',
      '#options' => $arrayTipoCta,
      '#attributes' => array(
        'data-msj' => t("Tipo de cuenta de nómina, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['numero_cuenta'] = array(
      '#type' => 'textfield',
      '#title' => t('Número de Cuenta Nómina:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['numero_cuenta']) ) ? $record['numero_cuenta']:'',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Número de cuenta de nómina, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'Guardar',
        //'#value' => t('Submit'),
    ];
    $form['#cache'] = [
        'max-age' => 0
    ];
  

    return $form;

  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $banco = $form_state->getValue('banco');
    if($banco == "") {
        $form_state->setErrorByName('banco', $this->t('Selecciona el banco.'));
    }
    $tipo_cuenta = $form_state->getValue('tipo_cuenta');
    if($tipo_cuenta == "") {
        $form_state->setErrorByName('tipo_cuenta', $this->t('Selecciona el tipo de cuenta de nómina.'));
    }
    $numero_cuenta = $form_state->getValue('numero_cuenta');
    if($numero_cuenta == "") {
         $form_state->setErrorByName('numero_cuenta', $this->t('Ingresa tu número de cuenta de nómina.'));
    }
    if(!is_numeric($numero_cuenta)) {
         $form_state->setErrorByName('numero_cuenta', $this->t('La cuenta de nómina debe ser un valor numérico.'));
    }

    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
    public function submitForm(array &$form, FormStateInterface $form_state) 
    {

        $field = $form_state->getValues();
        $banco = $field['banco'];
        $tipo_cuenta = $field['tipo_cuenta'];
        $numero_cuenta = $field['numero_cuenta'];

        if (isset($this->idadelanto) ) {
            $field  = array(
                'banco'   => $banco,
                'tipo_cuenta'   => $tipo_cuenta,
                'numero_cuenta'   => $numero_cuenta
            );

            $query = \Drupal::database();
            $update = $query->update('adelantos_nomina')
                ->fields($field)
                ->condition('idadelanto', $this->idadelanto)
                ->execute();

            if($update > 0)
            {
                drupal_set_message("Cuenta de nomina ha sido actualizada");
            }else{
                drupal_set_message("Cuenta de nomina no ha podido ser actualizada, intentalo nuevamente");
            }
            $response = new RedirectResponse("/admin/confirmacionempresas");
            $response->send();
            
        }else{
            drupal_set_message("Identificador no valido");
            $response = new RedirectResponse("/admin/confirmacionempresas");
            $response->send();
        }
    }
}
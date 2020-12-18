<?php

namespace Drupal\linxecredit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;
/**
 * Class RecoveryPassForm.
 *
 * @package Drupal\linxecredit\Form
 */
class RecoveryPassForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxerecoverypass_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    if(\Drupal::request()->query->get('Id')!=="")
      $idcode = intval(\Drupal::request()->query->get('Id'));
    else
      $idcode = 0;

    $form['password'] = array(
      '#type' => 'password',
      '#title' => t('Contraseña:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER APELLIDO'),
        'data-msj' => t("Escribe tu Contraseña"),
        'class' => array("password"),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['password2'] = array(
      '#type' => 'password',
      '#title' => t('Confirmar Contraseña:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER APELLIDO'),
        'data-msj' => t("Escribe la confirmación de tu contraseña"),
        'class' => array("password"),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    
    $form['IdCode'] = array(
      '#type' => 'hidden',
      '#required' => TRUE,
      '#default_value' => $idcode,
      '#title_display' => "none",
    );
    
    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Cambiar contraseña'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'linxerecoverypasstheme_form';

    $form['#attached'] = [
          'library' => [
            'linxecredit/linxecreditstyles', //include our custom library for this response
          ]
        ];
    
    //$form['#attributes']['class'][] = 'formularioin';
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
    $password = $form_state->getValue('password');
    if($password == "") {
         $form_state->setErrorByName('password', $this->t('Escribe tu Contraseña.'));
    }

    $password2 = $form_state->getValue('password2');
    if($password2 == "") {
         $form_state->setErrorByName('password2', $this->t('Escribe la confirmación de tu contraseña'));
    }

    if($password != $password2)
    {
      $form_state->setErrorByName('password2', $this->t('La contraseña y su confirmación no coinciden.'));
    }
    
    
    $IdCode = $form_state->getValue('IdCode');
    if($IdCode == "") {
         $form_state->setErrorByName('IdCode', $this->t('Parametro IdCode no definido'));
    }

    
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $session = \Drupal::request()->getSession();
    
    $field=$form_state->getValues();

   
    $dataField["Token"] = $field["IdCode"];
    $dataField["contraseniaNva"] = $field["password"];

    $linxelib = new LinxeLibrary();

    //registro
    $objRegister = $linxelib->getRecoveryPw($dataField);

    
    if(array_key_exists('Success', $objRegister))
    {
      $pos = strpos($objRegister->SpResult, "Error");
      if($objRegister->Success==true){

        if($pos === false)
        {
          $dataResponse = json_decode($objRegister->SpResult);
          if($objRegister->SpResult=="Ha expirado el tiempo para realizar esta operación por favor solicitar nuevamente la recuperación de contraseña")
          {
            $session->set('message',$objRegister->SpResult);
            $session->set('labelbutton',"Aceptar");
            $form_state->setRedirect('linxecredit.showmessage');
          }else{
            $url = Url::fromRoute('/password-create/confirmacion');
            $form_state->setRedirect('entity.node.canonical',
              array('node' => 17),
              array()
            );
          }
        }else{
          $session->set('message',$objRegister->SpResult);
          $session->set('labelbutton',"Aceptar");
          $form_state->setRedirect('linxecredit.showmessage');
        }

      }else{
        $session->set('message',$objRegister->SpResult);
        $session->set('labelbutton',"Aceptar");
        $form_state->setRedirect('linxecredit.showmessage');
      }
    }else{
      $session->set('message',"Se presento un error de conexión, inténtelo más tarde");
      $session->set('labelbutton',"Aceptar");
      $form_state->setRedirect('linxecredit.showmessage');
    }

  }

}

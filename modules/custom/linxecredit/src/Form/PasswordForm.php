<?php

namespace Drupal\linxecredit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;
/**
 * Class CodeGenForm.
 *
 * @package Drupal\linxecredit\Form
 */
class PasswordForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxepassword_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

     // or get your GET parameter
    $username = \Drupal::request()->query->get('username');

    /*$pass = '8080808080808080';
    $method = 'aes-128-cbc';
    $length_iv = openssl_cipher_iv_length($method);
    $iv = $pass; 

    $username_dec = openssl_decrypt ($username, $method, $pass,0,$iv);*/

    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => t('Usuario:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Escribe tu nombre de usuario")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['password_old'] = array(
      '#type' => 'password',
      '#title' => t('Contraseña actual:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER APELLIDO'),
        'data-msj' => t("Escribe tu Contraseña actual"),
        'class' => array("password"),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

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
    
    
    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Cambiar contraseña'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'linxepasswordtheme_form';

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
    $username = $form_state->getValue('username');
    if($username == "") {
         $form_state->setErrorByName('username', $this->t('Escribe tu nombre de usuario.'));
    }

    $password_old = $form_state->getValue('password_old');
    if($password_old == "") {
         $form_state->setErrorByName('password_old', $this->t('Escribe tu Contraseña actual.'));
    }

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
    
    
    $username = $form_state->getValue('username');
    if($username == "") {
         $form_state->setErrorByName('username', $this->t('Parametro username no definido'));
    }

    
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $session = \Drupal::request()->getSession();
    
    $field=$form_state->getValues();

   
    $dataField["UserLogin"] = $field["username"];
    $dataField["PswOlg"] = $field["password_old"];
    $dataField["PwsNew"] = $field["password"];

    $linxelib = new LinxeLibrary();

    //registro
    //print_r($dataField);
    $objRegister = $linxelib->getChangePw($dataField);
    //print_r($objRegister);
    //exit();
    if(array_key_exists('Success', $objRegister))
    {
      $pos = strpos($objRegister->SpResult, "Error");
      
      if($objRegister->Success==true  ){
        if($pos === false)
        {
          $dataResponse = json_decode($objRegister->SpResult);
          $url = Url::fromRoute('/password-create/confirmacion');
          $form_state->setRedirect('entity.node.canonical',
            array('node' => 17),
            array()
          );
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

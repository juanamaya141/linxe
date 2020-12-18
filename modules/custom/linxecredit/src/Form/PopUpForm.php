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
class PopUpForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxepopup_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $fechainicioanio = date("Y-m-d H:i:s");
        //tiene un código vigente
    $query = \Drupal::entityQuery('node')
    ->condition('status', NODE_PUBLISHED)
    ->condition('type', 'webinar')
    //->condition('field_fecha_y_hora_webinar', $fechainicioanio,"<=")
    ->sort('created' , 'DESC')
    ->range(0,1);
    
    $nids0 = $query->execute();
    

    foreach ($nids0 as $key => $nid) {
      $node = \Drupal\node\Entity\Node::load($nid);
      $titulo = $node->title->value;
      $textpopup = $node->field_textpopup->value;
      $webinar_id = $node->field_webinar_id_plataforma->value;
      //$webinar_schedule = $node->field_webinar_schedule_plataform->value;
    }
    
    //echo $fechacomienzomesredencion;
  
    

    $linxelib = new LinxeLibrary();
    $arrayFields["webinar_id"]  = $webinar_id;

    $respuesta = $linxelib->queryWebinar($arrayFields);

    if($respuesta->status=="success")
    {
      foreach ($respuesta->webinar->schedules as $key => $currentSchedule) {
        $webinar_schedule = $currentSchedule->schedule;
      }
    }

    
    $arrayEmpresa["Otra"] = "Otra";

    
    $form['name'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => '',
      '#title_display' => "none",
      '#attributes' => array(
        'placeholder' => t('NOMBRE'),
        'data-msj' => t("Escribe tu nombre")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    $form['email'] = array(
      '#type' => 'email',
      '#required' => TRUE,
      '#default_value' => '',
      '#title_display' => "none",
      '#attributes' => array(
        'placeholder' => t('CORREO ELECTRÓNICO'),
        'data-msj' => t("Correo electrónico, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['webinar_id'] = array(
      '#type' => 'hidden',
      '#required' => TRUE,
      '#default_value' => $webinar_id,
      '#title_display' => "none",
    );

    $form['webinar_schedule'] = array(
      '#type' => 'hidden',
      '#required' => TRUE,
      '#default_value' => $webinar_schedule,
      '#title_display' => "none",
    );

    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#id' => 'btn-enviar',
        '#value' => $this->t('ENVIAR'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'linxepopuptheme_form';

    $form['#myvars']['titulo'] = $titulo;
    $form['#myvars']['textpopup'] = $textpopup;
    $form['#myvars']['webinar_id'] = $webinar_id;
    $form['#myvars']['webinar_schedule'] = $webinar_schedule;
    //borrar cache
    $form['#cache'] = [
      'max-age' => 0
    ];
    /*
    $form['#attached'] = [
          'library' => [
            'linxecredit/librarypopupwebinar', //include our custom library for this response
          ]
        ];*/
    
    //$form['#attributes']['class'][] = 'formularioin';
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $name = $form_state->getValue('name');
    if($name == "") {
         $form_state->setErrorByName('name', $this->t('Escribe tu nombre.'));
    }
    $email = $form_state->getValue('email');
    if($email == "") {
         $form_state->setErrorByName('email', $this->t('Digita tu correo electrónico.'));
    }

    if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
    {
      $form_state->setErrorByName('email', $this->t('El correo electrónico no es válido'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $field=$form_state->getValues();
    $session = \Drupal::request()->getSession();


    $session->remove('tkn_access');
    $session->remove('tkn_expiresin');

    $linxelib = new LinxeLibrary();

    $arrayFields["name"]  = $field["name"];
    $arrayFields["email"]  = $field["email"];
    $arrayFields["webinar_id"]  = $field["webinar_id"];
    $arrayFields["webinar_schedule"]  = $field["webinar_schedule"];
    $arrayFields["registerdate"] = date("Y-m-d H:i:s");
    //insertar primero en la bd
    $query = \Drupal::database();
    $query ->insert('registroWebinar')
    ->fields($arrayFields)
    ->execute();
    //guardar una cookie para saber que el usuario ya llenó el formulario previamente
      //user_cookie_save(['enablePopupForm' => "yes"]);
    
    $respuesta = true;
    $respuesta = $linxelib->enviarWebinar($arrayFields);
    
    //exit();
    if($respuesta) //el registro se realizo sin ningún problema (1 - Registro exitoso 2 - Error Sotfware nomina 3 - Otro tipo de error 4 - Usuario ya se encuentra creado)
    {

      setcookie("enablePopupForm", 1, strtotime('+1 week'));
      $form_state->setRedirect('entity.node.canonical',
        array('node' => 33), //mensaje respuesta webinar confirmation
        array()
      );

    }else{
      $session->set('message',"No fue posible registrarte a nuestro webinar, inténtalo más tarde");
      $session->set('labelbutton',"Aceptar");
      $form_state->setRedirect('linxecredit.showmessage');
    }
  }

}

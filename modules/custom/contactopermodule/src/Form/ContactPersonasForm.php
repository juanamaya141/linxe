<?php

namespace Drupal\contactopermodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;


/**
 * Class CodeGenForm.
 *
 * @package Drupal\contactopermodule\Form
 */
class ContactPersonasForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contactpersonas_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    
    $form['nombre'] = array(
      '#type' => 'textfield',
      '#title' => t('Primer Nombre:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Nombre, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    $form['apellido'] = array(
      '#type' => 'textfield',
      '#title' => t('Primer Apellido:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER APELLIDO'),
        'data-msj' => t("Apellido, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    $form['celular'] = array(
      '#type' => 'number',
      '#title' => t('Celular:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t('Celular, es un dato obligatorio'),

      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    $form['email'] = array(
      '#type' => 'email',
      '#title' => t('Correo Electrónico:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t('Correo electrónico, es un dato obligatorio'),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    
    $form['empresa'] = array(
      '#type' => 'textfield',
      '#title' => t('Empresa:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t('Empresa, es un dato obligatorio'),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['asunto'] = array(
      '#type' => 'textfield',
      '#title' => t('Asunto:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t('Asunto, es un dato obligatorio'),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['mensaje'] = array(
      '#type' => 'textarea',
      '#title' => t('Mensaje:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t('Mensaje, es un dato obligatorio'),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Enviar'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'contactopermodule_form';
    $form['#attributes'] = array(
          'class' => "formularioin",
        );

    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $nombre = $form_state->getValue('nombre');
    if($nombre == "") {
         $form_state->setErrorByName('nombre', $this->t('Digite su nombre'));
    }

    $apellido = $form_state->getValue('apellido');
    if($apellido == "") {
         $form_state->setErrorByName('apellido', $this->t('Digite su apellido'));
    }

    $celular = $form_state->getValue('celular');
    if($celular == "") {
         $form_state->setErrorByName('celular', $this->t('Digite su celular'));
    }
    if(is_numeric($celular) == false) {
         $form_state->setErrorByName('celular', $this->t('El celular debe ser numérico'));
    }
    
    $email = $form_state->getValue('email');
    if($email == "") {
         $form_state->setErrorByName('email', $this->t('Digite su email'));
    }

    $empresa = $form_state->getValue('empresa');
    if($email == "") {
         $form_state->setErrorByName('empresa', $this->t('Digite su empresa'));
    }

    $asunto = $form_state->getValue('asunto');
    if($asunto == "") {
         $form_state->setErrorByName('asunto', $this->t('Digite el asunto'));
    }
    
    
    $mensaje = $form_state->getValue('mensaje');
    if($mensaje == "") {
         $form_state->setErrorByName('mensaje', $this->t('Digite el mensaje de su solicitud'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('contactopermodule.settings');

    $field=$form_state->getValues();

    
    $arrayFields["nombre"] = $field["nombre"];
    $arrayFields["apellido"] = $field["apellido"];
    $arrayFields["email"] = $field["email"];
    $arrayFields["celular"] = $field["celular"];
    $arrayFields["empresa"] = $field["empresa"];
    $arrayFields["asunto"] = $field["asunto"];
    $arrayFields["mensaje"] = $field["mensaje"];
    $arrayFields["contactdate"] = date("Y-m-d H:i:s");
    
    $query = \Drupal::database();
    $query ->insert('contactoPersonas')
    ->fields($arrayFields)
    ->execute();

    $bodymsg = "
    
    Nombre:".$field["nombre"]."
    Apellido: ".$field["apellido"]."
    Email:".$field["email"]."
    Celular:".$field["celular"]."
    Empresa:".$field["empresa"]."
    Asunto:".$field["asunto"]."
    Mensaje:".$field["mensaje"]."
    Fecha/hora:".$arrayFields["contactdate"]."";

    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'contactopermodule';
    $key = 'contacto_personas';
    $to = $config->get('contactopermodule.email');
    $params['Bcc'] = $config->get('contactopermodule.emailcc');;
    $params['message'] = $bodymsg;
    $params['subject'] = t("Registro Formulario Contacto Personas");
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      drupal_set_message(t('Tu mensaje no pudo enviarse, por favor inténtalo más adelante.'), 'error');
    }
    else {
      drupal_set_message(t('Gracias por contactarnos, pronto nos comunicaremos contigo.'));
      
    }
    $url = Url::fromRoute('/contacto/confirmacion');
    $form_state->setRedirect('entity.node.canonical',
      array('node' => 10),
      array()
    );
    //$form_state->setRedirect('codegenmodule.display_table_controller_display');
  }

}

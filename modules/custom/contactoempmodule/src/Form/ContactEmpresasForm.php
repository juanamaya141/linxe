<?php

namespace Drupal\contactoempmodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;


/**
 * Class CodeGenForm.
 *
 * @package Drupal\contactoempmodule\Form
 */
class ContactEmpresasForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contactempresas_form';
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
    $form['telefono'] = array(
      '#type' => 'number',
      '#title' => t('Teléfono:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t('Teléfono, es un dato obligatorio'),

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

    $form['cargo'] = array(
      '#type' => 'textfield',
      '#title' => t('Cargo:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        'data-msj' => t('Cargo, es un dato obligatorio'),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $arrayTamano['1 a 19 empleados'] = "1 a 19 empleados" ;
    $arrayTamano['20 a 49 empleados'] = "20 a 49 empleados" ;
    $arrayTamano['50 a 99 empleados'] = "50 a 99 empleados" ;
    $arrayTamano['100 a 499 empleados'] = "100 a 499 empleados" ;
    $arrayTamano['500 a 999 empleados'] = "500 a 999 empleados" ;
    $arrayTamano['Más de 1.000 empleados'] = "Más de 1.000 empleados" ;

    $form['tamanoempresa'] = array(
      '#type' => 'select',
      '#title' => t('Tamaño de la empresa:'),
      '#title_display' => "none",
      '#label_display' =>'after',
      '#required' => TRUE,
      '#options' => $arrayTamano,
      "#empty_option"=> t(''),
      '#attributes' => array(
        'data-msj' => t('Tamaño de la empresa, es un dato obligatorio'),
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


    $form['#theme'] = 'contactoempmodule_form';
    

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

    $telefono = $form_state->getValue('telefono');
    if($telefono == "") {
         $form_state->setErrorByName('celular', $this->t('Digite su telefono'));
    }
    if(is_numeric($telefono) == false) {
         $form_state->setErrorByName('telefono', $this->t('El telefono debe ser numérico'));
    }
    
    $email = $form_state->getValue('email');
    if($email == "") {
         $form_state->setErrorByName('email', $this->t('Digite su email'));
    }

    $empresa = $form_state->getValue('empresa');
    if($email == "") {
         $form_state->setErrorByName('empresa', $this->t('Digite su empresa'));
    }

    $cargo = $form_state->getValue('cargo');
    if($cargo == "") {
         $form_state->setErrorByName('cargo', $this->t('Digite su cargo'));
    }

    $tamanoempresa = $form_state->getValue('tamanoempresa');
    if($tamanoempresa == "") {
         $form_state->setErrorByName('tamanoempresa', $this->t('Seleccione tamaño empresa'));
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
    $config = $this->config('contactoempmodule.settings');

    $field=$form_state->getValues();

    
    $arrayFields["nombre"] = $field["nombre"];
    $arrayFields["apellido"] = $field["apellido"];
    $arrayFields["email"] = $field["email"];
    $arrayFields["telefono"] = $field["telefono"];
    $arrayFields["empresa"] = $field["empresa"];
    $arrayFields["cargo"] = $field["cargo"];
    $arrayFields["tamanoempresa"] = $field["tamanoempresa"];
    $arrayFields["asunto"] = $field["asunto"];
    $arrayFields["mensaje"] = $field["mensaje"];
    $arrayFields["contactdate"] = date("Y-m-d H:i:s");
    
    $query = \Drupal::database();
    $query ->insert('contactoEmpresas')
    ->fields($arrayFields)
    ->execute();

    $bodymsg = "
    
    Nombre:".$field["nombre"]."
    Apellido: ".$field["apellido"]."
    Email:".$field["email"]."
    Teléfono:".$field["telefono"]."
    Empresa:".$field["empresa"]."
    Cargo:".$field["cargo"]."
    Tamaño empresa:".$field["tamanoempresa"]."
    Asunto:".$field["asunto"]."
    Mensaje:".$field["mensaje"]."
    Fecha/hora:".$arrayFields["contactdate"]."";

    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'contactoempmodule';
    $key = 'contacto_empresas';
    $to = $config->get('contactoempmodule.email');
    $params['Bcc'] = $config->get('contactoempmodule.emailcc');;
    $params['message'] = $bodymsg;
    $params['subject'] = t("Registro Formulario Contacto Empresas");
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
      array(),
    );
    //$form_state->setRedirect('codegenmodule.display_table_controller_display');
  }

}

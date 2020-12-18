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
class PreferenciasForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxepreferencias_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $session = \Drupal::request()->getSession();
    $userObj = (object) array();

    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $userObj->name = $session->get('nombres');
      $id_tipodoc = $session->get('tipodocumento');
      $num_id = $session->get('numerodocumento');
      $valormontoaprobado = $session->get('montoaprobado');
      $valormontoseleccionado = $session->get('montoseleccionado');
      $plazo = $session->get('plazo');
      $creditovigente = $session->get('creditovigente');
      $estatus = $session->get('estatus');
      $validaempresa = $session->get('validaempresa');
      $showPreferenciaForm = $session->get('showPreferenciaForm');
      $email = $session->get('email');
      $celular = $session->get('celular');
      $modificarSeleccion = $session->get('modificarSeleccion');
    }else{
      $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
      return new RedirectResponse($url);
    }

    $config = $this->config('linxecredit.settings');

    $paththeme = base_path().drupal_get_path('theme', 'linxe');
    $basepath = base_path();

    

    $linxelib = new LinxeLibrary();
        
    //validar sesion
    if($linxelib->validaVigenciaSesion()==false)
    {
      $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
      return new RedirectResponse($url);
    }
    
    $options = array();
    $vocabulary = 'categorias_preferencias';
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', $vocabulary);
    $query->sort('weight');
    $tids = $query->execute();
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
    $i=0;
    foreach($terms as $term) {
      $options[$term->getName()] = $term->getName();
    }


    $form['categorias'] = array(
      '#type' => 'checkboxes',
      '#title_display' => "none",
      '#options' => $options,
    );

    foreach ($options as $key => $value) {
      //echo $key;
      $form['categorias'][$key] = array(
        '#prefix' => '<div class="form-element col-md-6 custom-check mr-0 align-left" >',
        '#suffix' => '</div>',
      );
    }

    

    $form['mensaje'] = array(
      '#type' => 'textarea',
      '#title' => t('Sugerencias:'),
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

    
    $form['#myvars']['showPreferenciaForm'] = $showPreferenciaForm;
    $form['#theme'] = 'preferenciastheme_form';
    
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
    $categorias = $form_state->getValue('categorias');
    if($categorias == "") {
         $form_state->setErrorByName('categorias', $this->t('Selecciona al menos una categoría.'));
    }

    $mensaje = $form_state->getValue('mensaje');
    if($mensaje == "") {
         $form_state->setErrorByName('mensaje', $this->t('Escribe tu Sugerencia.'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $field=$form_state->getValues();
    $session = \Drupal::request()->getSession();
    $userObj = (object) array();

    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $userObj->name = $session->get('nombres');
      $id_tipodoc = $session->get('tipodocumento');
      $num_id = $session->get('numerodocumento');
      $valormontoaprobado = $session->get('montoaprobado');
      $valormontoseleccionado = $session->get('montoseleccionado');
      $plazo = $session->get('plazo');
      $creditovigente = $session->get('creditovigente');
      $estatus = $session->get('estatus');
      $validaempresa = $session->get('validaempresa');
      $showPreferenciaForm = $session->get('showPreferenciaForm');
      $email = $session->get('email');
      $celular = $session->get('celular');
      $modificarSeleccion = $session->get('modificarSeleccion');
    }else{
      $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
      return new RedirectResponse($url);
    }

    $field=$form_state->getValues();

   
    $dataField["tipodocumento"] = $id_tipodoc;
    $dataField["documento"] = $num_id;
    $dataField["nombre"] = $userObj->name;
    $dataField["celular"] = $celular;
    $dataField["email"] = $email;

    $categoriasArray = [];
    $i=0;
    foreach ($field["categorias"] as $key => $value) {
      if($value === $key)
      {
        $categoriasArray[$i]=$value;
        $i++;
      }
    }

    
    $dataField["categoriasseleccionadas"] = implode(",", $categoriasArray);
    $dataField["mensaje"] = $field["mensaje"];
    $dataField["contactdate"] = date("Y-m-d H:i:s");


    $linxelib = new LinxeLibrary();

    //registro
    $respuesta = $linxelib->setPreferenciaRegistro($dataField);
    
    
    if($respuesta>0){
      $session->set('showPreferenciaForm',false);
      $form_state->setRedirect('linxecredit.dashboard-informacionpersonal'); 
    }else{
      drupal_set_message(t('Tu mensaje no pudo enviarse, por favor inténtalo más adelante.'), 'error');
      $form_state->setRedirect('linxecredit.dashboard-informacionpersonal'); 
    }
  }

}

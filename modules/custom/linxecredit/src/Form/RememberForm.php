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
class RememberForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxeremember_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $session = \Drupal::request()->getSession();

    $session->remove('tkn_access');
    $session->remove('tkn_expiresin');

    $linxelib = new LinxeLibrary();

    $arrayEmpresa[""] = "" ;
    /*
    $objEmpresas = $linxelib->getEmpresas();

    foreach ($objEmpresas->SpResult->Table as $key => $value) {
      $key = $value->Nit;
      $arrayEmpresa[$value->NombreEmpresa] = $value->NombreEmpresa;
    }
    */

    //verificar si esta logueado la persona no mostrar el formulario de registro
    /*
    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
     //$form_state->setRedirect('linxecredit.dashboard-seleccion');
      $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-seleccion')->toString();
      return new RedirectResponse($url);
    }
    */
    //
    $database = \Drupal::database();

		// registro
    $query = $database->select('empresas', 'em');
    $result = $query
                ->fields('em')
                ->orderBy("em.razon_social", 'ASC')
                ->execute();
    //print_r($result);
    foreach ($result as $key=>$record) {
      //echo $record->convenio_actividadecon."<br/>";
      $arrayEmpresa[$record->razon_social] = $record->razon_social;
    }
            


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
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Apellido, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );


    $form['documento'] = array(
      '#type' => 'number',
      '#title' => t('Número de Documento:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Número de documento, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['empresa'] = array(
      '#type' => 'select',
      '#title' => t('Selecciona tu empresa:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayEmpresa,
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Empresa, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    
    
    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Ingresar a Linxe'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'linxeremembertheme_form';
    
    //$form['#attributes']['class'][] = 'formularioin';
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $nombre = $form_state->getValue('nombre');
    if($nombre == "") {
         $form_state->setErrorByName('nombre', $this->t('Escribe tu nombre.'));
    }
    $apellido = $form_state->getValue('apellido');
    if($apellido == "") {
         $form_state->setErrorByName('apellido', $this->t('Escribe tu apellido.'));
    }


    $documento = $form_state->getValue('documento');
    if($documento == "") {
         $form_state->setErrorByName('documento', $this->t('Ingresa tu número de documento.'));
    }

    if(!is_numeric($documento)) {
         $form_state->setErrorByName('documento', $this->t('El documento debe ser numérico.'));
    }

    
    $empresa = $form_state->getValue('empresa');
    if($empresa == "") {
         $form_state->setErrorByName('empresa', $this->t('Selecciona la empresa a la que perteneces.'));
    }
    
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $session = \Drupal::request()->getSession();
    
    $field=$form_state->getValues();

    $dataField["Nombre"] = $field["nombre"];
    $dataField["Apellido"] = $field["apellido"];
    $dataField["Cedula"] = $field["documento"];
    $dataField["NombreEmpresa"] = $field["empresa"];    

    $linxelib = new LinxeLibrary();

    //registro
    //print_r($dataField);
    $objRegister = $linxelib->validaCambioContrasena($dataField);
    //print_r($objRegister);
    //exit();
    
    if(array_key_exists('Success', $objRegister))
    {
      if($objRegister->Success==true){
        
        if($objRegister->SpResult=="Hemos enviado un correo a tu mail registrado para realizar la recuperacion de contraseña")
        {
          $url = Url::fromRoute('/restableciendo-contrasena');
          $form_state->setRedirect('entity.node.canonical',
            array('node' => 19),
            array()
          );
        }else{
          $session->set('message',$objRegister->SpResult);
          $session->set('labelbutton',"Aceptar");
          $form_state->setRedirect('linxecredit.showmessage');
        }
        
      }else{
        $session->set('message',"Se presentó un error , inténtelo más tarde");
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

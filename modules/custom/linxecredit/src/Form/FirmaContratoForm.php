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
class FirmaContratoForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxefirmacontrato_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $session = \Drupal::request()->getSession();
    $config = $this->config('linxecredit.settings');
    
    $tooltip_text_nomina = $config->get('linxecredit.tooltip_text_nomina');
    $tooltip_text_movi = $config->get('linxecredit.tooltip_text_movi');
    $userObj = (object) array();

    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $userObj->name = $session->get('nombres');
      $id_tipodoc = $session->get('tipodocumento');
      $num_id = $session->get('numerodocumento');
      $valormontoaprobado = number_format(intval($session->get('montoaprobado')) ,0,",",".");
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

    //lanzar el envío del mensaje sms - otp para abrir el formulario
    $linxelib = new LinxeLibrary();
    //$enviosms = $linxelib->envioOTP($id_tipodoc,$num_id);
    //
        
    //validar sesion
    if($linxelib->validaVigenciaSesion()==false)
    {
      $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
      return new RedirectResponse($url);
    }


    $form['codigo'] = array(
      '#type' => 'number',
      '#title' => t('Código:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Ingresa el código")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['tipoabono'] = array(
      '#type' => 'hidden',
      '#name' => 'tipoabono',
      '#required' => TRUE,
      '#default_value' => 'Cta Nomina',
      '#title_display' => "none",
    );

    /*
    $form['tipoabono1'] = array(
      '#type' => 'radio',
      '#name' => 'tipoabono',
      '#title'  => "",
      '#title_display' => "none",
      '#label_display' =>'none',
      '#suffix' => '',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'class' => array("form-check-input")
      ),
      '#return_value' => 'Cta Nomina',
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );


    $form['tipoabono2'] = array(
      '#type' => 'radio',
      '#name' => 'tipoabono',
      '#title'  => "",
      '#title_display' => "none",
      '#label_display' =>'none',
      '#suffix' => '',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'class' => array("form-check-input")
      ),
      '#return_value' => 'Movii',
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    */
    

    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Firmar y Aceptar'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'linxefirmacontratotheme_form';

    $form['#myvars']['celular'] = $linxelib->ofuscarTexto($celular);
    $form['#myvars']['email'] = $linxelib->ofuscarTexto($email);
    $form['#myvars']['tooltip_nomina'] = $tooltip_text_nomina;
    $form['#myvars']['tooltip_movi'] = $tooltip_text_movi;
    //borrar cache
    $form['#cache'] = [
      'max-age' => 0
    ];
    
    
    //$form['#attributes']['class'][] = 'formularioin';
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $codigo = $form_state->getValue('codigo');
    if($codigo == "") {
         $form_state->setErrorByName('codigo', $this->t('Ingresa el código.'));
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
      $email = $session->get('email');
      $celular = $session->get('celular');
      $modificarSeleccion = $session->get('modificarSeleccion');

      $session->set('tipo_desembolso',$field["tipoabono"]);
    }else{
      $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
      return new RedirectResponse($url);
    }

    $linxelib = new LinxeLibrary();

    $dataField["tipoId"] = $id_tipodoc;
    $dataField["numId"] = $num_id;
    $dataField["Token"] = $field["codigo"];
    //$dataField["tDesembolso"] = $_POST["tipoabono"]; 
    $dataField["tDesembolso"] = $field["tipoabono"];    

    //registro
    //print_r($dataField);
    $objFirma = $linxelib->getFirmarPagare($dataField);
    //print_r($objFirma);
    //exit();
    //$objAutenticacion = $linxelib->getLogin($user,$pass);

      //print_r($objAutenticacion);
      //exit();

    if(array_key_exists('Success', $objFirma))
    {
      if($objFirma->Success==true){
        
        if($objFirma->SpResult=="Pagaré firmado éxitosamente.")
        {
          $form_state->setRedirect('linxecredit.dashboard-desembolso');
        }else{
          $session->set('message',$objFirma->SpResult);
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

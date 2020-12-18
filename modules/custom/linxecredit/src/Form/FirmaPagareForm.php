<?php

namespace Drupal\linxecredit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;
use Drupal\linxecredit\Libs\AdelantoLibrary as AdelantoLibrary;
use Drupal\linxecredit\Libs\DecevalLibrary as DecevalLibrary;
/**
 * Class FirmaPagareForm.
 *
 * @package Drupal\linxecredit\Form
 */
class FirmaPagareForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxefirmapagare_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $session = \Drupal::request()->getSession();
    $config = $this->config('linxecredit.settings');
    
    
    $userObj = (object) array();

    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $userObj->name = $session->get('nombres');
      $id_tipodoc = $session->get('tipodocumento');
      $num_id = $session->get('numerodocumento');
      $idregistro = $session->get('idregistro');
      
      $valormontoaprobado = $session->get('montoaprobado');
      $valormontoseleccionado = $session->get('montoseleccionado');
      $plazo = $session->get('plazo');
      $nombreempresa = $session->get('nombreempresa');
      $plazo = $session->get('plazo');
      $creditovigente = $session->get('creditovigente');
      $estatus = $session->get('estatus');
      $validaempresa = $session->get('validaempresa');
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
        'data-msj' => t("Ingresa el código")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    

    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Firmar y Aceptar'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'linxefirmapagaretheme_form';

    $form['#myvars']['celular'] = $linxelib->ofuscarTexto($celular);
    $form['#myvars']['email'] = $linxelib->ofuscarTexto($email);
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
  public function submitForm(array &$form, FormStateInterface $form_state) 
  {
    $config = $this->config('linxecredit.settings');
    $urlws_deceval = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_deceval') ; 
    $deceval_codigodepositante = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_codigodepositante');
    $deceval_usuario = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_usuario');
    $deceval_identificacionemisor = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_identificacionemisor');

    $field=$form_state->getValues();
    $session = \Drupal::request()->getSession();
    $userObj = (object) array();

    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $userObj->name = $session->get('nombres');
      $id_tipodoc = $session->get('tipodocumento');
      $num_id = $session->get('numerodocumento');
      $idregistro = $session->get('idregistro');
      
      $valormontoaprobado = $session->get('montoaprobado');
      $valormontoseleccionado = $session->get('montoseleccionado');
      $plazo = $session->get('plazo');
      $nombreempresa = $session->get('nombreempresa');
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


    $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
    $dataUser = $returnArray->userData;
    $idadelanto = $dataUser->idadelanto;
    $otp_code = $dataUser->otp;
    $idDocumentoPagare = $dataUser->iddocumentopagare;

    //realizamos la validación del código otp
    if($otp_code == $field["codigo"])
    {
      $arrayCrearGirador = [];
      //params
      $arrayCrearGirador["urlws_deceval"] = $urlws_deceval;
      $arrayCrearGirador["deceval_codigodepositante"] = $deceval_codigodepositante;
      $arrayCrearGirador["deceval_usuario"] = $deceval_usuario;
      $arrayCrearGirador["deceval_identificacionemisor"] = $deceval_identificacionemisor;
      //fields
      $arrayCrearGirador["OTPPagare"] = ""; //?
      $arrayCrearGirador["OTPProcedimiento"] = ""; //?
      $arrayCrearGirador["clave"] = ""; //?
      $arrayCrearGirador["idRolFirmante"] = ""; //?

      $arrayCrearGirador["idDocumentoPagare"] = $idDocumentoPagare;
      $arrayCrearGirador["motivo"] = "Firma Pagaré Adelanto Nómina"; //?
      $arrayCrearGirador["numeroDocumento"] = $num_id;
      $arrayCrearGirador["tipoDocumento"] = $id_tipodoc;


      $arrayFirmarPagare = $decevallib->firmarPagare($arrayCrearGirador);
      if( $arrayFirmarPagare->return->codigoError == "SDL.SE.0000" && $arrayFirmarPagare->return->exitoso == 1)
      {
          $respuestaDeceval = json_encode($arrayFirmarPagare);
          $numpagareentidad = $arrayCreatePagare->return->respuesta->numPagareEntidad;
          $iddocumentopagare = $arrayCreatePagare->return->respuesta->idDocumentoPagare; 
          //actualizar el regitro de adelanto de nómina con la información del girador
          $actualizarPagare = $adelantolib->setRespuestaFirmaPagareDeceval($idadelanto,$respuestaDeceval);

          if($actualizarPagare->status=="ok"){
              $arrayReturn["status"] = $actualizarPagare->Success;
              $arrayReturn["msg"] = $actualizarPagare->msg;
              $form_state->setRedirect('linxecredit.dashboard-desembolso');
              //
          }else{
              $arrayReturn["status"] = false;
              $arrayReturn["msg"] = "Error";
              $arrayReturn["error"] = $actualizarPagare->error;
              
              $session->set('message',$actualizarPagare->error);
              $session->set('labelbutton',"Aceptar");
              $form_state->setRedirect('linxecredit.showmessage');
          }

      }else{
          $arrayReturn["status"] = false;
          $arrayReturn["msg"] = "Error";
          $arrayReturn["error"] = $arrayCreateGirador->return->descripcion;
          
          $session->set('message',$arrayCreateGirador->return->descripcion);
          $session->set('labelbutton',"Aceptar");
          $form_state->setRedirect('linxecredit.showmessage');
      }

    }else{
      $session->set('message',"Código no valido");
      $session->set('labelbutton',"Aceptar");
      $form_state->setRedirect('linxecredit.showmessage');
    }

  }

  

}

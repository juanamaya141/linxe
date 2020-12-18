<?php

namespace Drupal\linxecredit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;

use Drupal\linxecredit\Libs\AdelantoLibrary as AdelantoLibrary;
/**
 * Class CodeGenForm.
 *
 * @package Drupal\linxecredit\Form
 */
class LoginForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxelogin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    
    $form['usuario'] = array(
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

    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Enviar'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    $form['#theme'] = 'linxelogintheme_form';
    
    //$form['#attributes']['class'][] = 'formularioin';
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $usuario = $form_state->getValue('usuario');
    if($usuario == "") {
         $form_state->setErrorByName('usuario', $this->t('Escribe tu nombre de usuario.'));
    }
    $password = $form_state->getValue('password');
    if($password == "") {
         $form_state->setErrorByName('password', $this->t('Escribe tu Contraseña.'));
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

    $user = $field["usuario"];
    $pass = $field["password"];
    
    $pos = strpos($user, '_');

    if($pos === false){ //se logueará por datascoring para crédito libranza
      $objAutenticacion = $linxelib->getLogin($user,$pass);
      if(array_key_exists('Success', $objAutenticacion))
      {
        if($objAutenticacion->Success==true){
          if($objAutenticacion->SpResult->Table[0]->ERROR != "" || $objAutenticacion->SpResult->Table[0]->Column1 != "")
          {
            $session->remove('tipoproducto');
            $session->remove('tipodocumento');
            $session->remove('numerodocumento');
            $session->remove('nombres');
            $session->remove('montoaprobado');
            $session->remove('montoseleccionado');
            $session->remove('cantidadseleccionada');
            $session->remove('plazo');
            $session->remove('creditovigente');
            $session->remove('estatus');
            $session->remove('validaempresa');
            $session->remove('email');
            $session->remove('celular');
            $session->remove('nombreempresa');
            $session->remove('modificarSeleccion');
            $session->remove('showPreferenciaForm');
            $session->remove('showInfoPersonalForm');

            $session->remove('monto_temp');
            $session->remove('plazo_temp');
            $session->remove('tipo_desembolso');

            $session->remove('last_activity');
            $session->remove('expire_time');

            $session->remove('tkn_access');

            //$session->set('message',$objAutenticacion->SpResult->Table[0]->ERROR);
            $error = "";
            if($objAutenticacion->SpResult->Table[0]->ERROR!= "")
              $error = $objAutenticacion->SpResult->Table[0]->ERROR;
            else if($objAutenticacion->SpResult->Table[0]->Column1!= "")
              $error = $objAutenticacion->SpResult->Table[0]->Column1;
            $session->set('message',$error);
            $session->set('labelbutton',"Aceptar");
            $form_state->setRedirect('linxecredit.showmessage');
          }else{

            $session->set('tipoproducto', "libranza");
            $session->set('tipodocumento', $objAutenticacion->SpResult->Table[0]->TipoDocumento);
            $session->set('numerodocumento', $objAutenticacion->SpResult->Table[0]->NumeroDocumento);
            $session->set('nombres', $objAutenticacion->SpResult->Table[0]->Nombres);

            if($objAutenticacion->SpResult->Table[0]->MontoAprobado=="")
              $session->set('montoaprobado', 0);
            else
              $session->set('montoaprobado', $objAutenticacion->SpResult->Table[0]->MontoAprobado);

            $session->set('montoseleccionado', $objAutenticacion->SpResult->Table[0]->MontoSeleccionado);

            if($objAutenticacion->SpResult->Table[0]->CantidadSeleccionada!="")
              $session->set('cantidadseleccionada', $objAutenticacion->SpResult->Table[0]->CantidadSeleccionada);
            else
              $session->set('cantidadseleccionada', $objAutenticacion->SpResult->Table[0]->MontoSeleccionado);

            $session->set('plazo', $objAutenticacion->SpResult->Table[0]->Plazo);
            $session->set('creditovigente', $objAutenticacion->SpResult->Table[0]->CreditoVigente);
            $session->set('estatus', $objAutenticacion->SpResult->Table[0]->Estatus);
            $session->set('validaempresa', $objAutenticacion->SpResult->Table[0]->ValidaEmpresa);
            $session->set('email', $objAutenticacion->SpResult->Table[0]->Email);
            $session->set('celular', $objAutenticacion->SpResult->Table[0]->Celular);
            $session->set('nombreempresa', $objAutenticacion->SpResult->Table[0]->NombreEmpresa);
            $session->set('modificarSeleccion', "NO");
            $session->set('showPreferenciaForm',true);
            $session->set('showInfoPersonalForm',true);
            $session->set('monto_temp',0);
            $session->set('plazo_temp',0);
            $session->set('tipo_desembolso',"");

            $session->set('last_activity',time());
            $session->set('expire_time',10*60);//expire time in seconds: 10 minutes


            $form_state->setRedirect('linxecredit.dashboard-seleccion');
          }
          
        }else{
          $session->remove('tipoproducto');
          $session->remove('tipodocumento');
          $session->remove('numerodocumento');
          $session->remove('nombres');
          $session->remove('montoaprobado');
          $session->remove('montoseleccionado');
          $session->remove('cantidadseleccionada');
          $session->remove('plazo');
          $session->remove('creditovigente');
          $session->remove('estatus');
          $session->remove('validaempresa');
          $session->remove('email');
          $session->remove('celular');
          $session->remove('nombreempresa');
          $session->remove('modificarSeleccion');
          $session->remove('showPreferenciaForm');
          $session->remove('showInfoPersonalForm');
          $session->remove('monto_temp');
          $session->remove('plazo_temp');
          $session->remove('tipo_desembolso');

          $session->remove('last_activity');
          $session->remove('expire_time');

          $session->remove('tkn_access');

          //drupal_set_message("Se presento un error de conexión, inténtelo más tarde","error");
          $session->set('message',"Se presento un error de conexión, inténtelo más tarde");
          $session->set('labelbutton',"Aceptar");
          $form_state->setRedirect('linxecredit.showmessage');
        }
      }else{
        $session->remove('tipoproducto');
        $session->remove('tipodocumento');
        $session->remove('numerodocumento');
        $session->remove('nombres');
        $session->remove('montoaprobado');
        $session->remove('montoseleccionado');
        $session->remove('cantidadseleccionada');
        $session->remove('plazo');
        $session->remove('creditovigente');
        $session->remove('estatus');
        $session->remove('validaempresa');
        $session->remove('email');
        $session->remove('celular');
        $session->remove('nombreempresa');
        $session->remove('modificarSeleccion');
        $session->remove('showPreferenciaForm');
        $session->remove('showInfoPersonalForm');
        $session->remove('monto_temp');
        $session->remove('plazo_temp');
        $session->remove('tipo_desembolso');

        $session->remove('last_activity');
        $session->remove('expire_time');

        $session->remove('tkn_access');

        //drupal_set_message("Se presento un error de conexión, inténtelo más tarde","error");
        $session->set('message',"Se presento un error de conexión, inténtelo más tarde");
        $session->set('labelbutton',"Aceptar");
        $form_state->setRedirect('linxecredit.showmessage');
      }
    }else{  //se logueará por drupal para adelanto de nómina
      $adelantolib = new AdelantoLibrary();
      $uid = \Drupal::service('user.auth')->authenticate($user, $pass);
      if(is_numeric($uid))
      {
        $user = \Drupal\user\Entity\User::load($uid);
        //user_login_finalize($user);
        //echo "uid: ".$uid;

        $returnArray = json_decode($adelantolib->getUserDataByID($uid));
        $dataUser = $returnArray->userData;
        
        $session->set('tipoproducto', "adelanto");
        $session->set('tipodocumento', $dataUser->tipodocumento);
        $session->set('numerodocumento', $dataUser->documento);
        $session->set('idregistro', $dataUser->idregistro);
        $session->set('nombres', $dataUser->nombre." ".$dataUser->primer_apellido);
        $session->set('montoaprobado', $dataUser->monto_maximo_aprobado);
        $session->set('montoseleccionado', $dataUser->valor_solicitado);

        $session->set('cantidadseleccionada', $dataUser->valor_solicitado);

        $session->set('plazo', "30 días");
        if($dataUser->estado_solicitud =='solicitada')
          $session->set('creditovigente', 1 );
        else
          $session->set('creditovigente', 0 );
        $session->set('estatus', $dataUser->estado_solicitud );
        $session->set('validaempresa', 1);
        $session->set('email', $dataUser->email);
        $session->set('celular', $dataUser->celular);
        $session->set('nombreempresa', $dataUser->mareigua_razon_social);
        $session->set('modificarSeleccion', "NO");
        $session->set('showPreferenciaForm',true);
        $session->set('showInfoPersonalForm',true);
        $session->set('monto_temp',0);
        $session->set('plazo_temp',0);
        $session->set('tipo_desembolso',"");

        $session->set('last_activity',time());
        $session->set('expire_time',10*60);//expire time in seconds: 10 minutes


        $form_state->setRedirect('linxecredit.dashboard-adelanto');
      }else{
        $session->remove('tipoproducto');
        $session->remove('tipodocumento');
        $session->remove('numerodocumento');
        $session->remove('nombres');
        $session->remove('montoaprobado');
        $session->remove('montoseleccionado');
        $session->remove('cantidadseleccionada');
        $session->remove('plazo');
        $session->remove('creditovigente');
        $session->remove('estatus');
        $session->remove('validaempresa');
        $session->remove('email');
        $session->remove('celular');
        $session->remove('nombreempresa');
        $session->remove('modificarSeleccion');
        $session->remove('showPreferenciaForm');
        $session->remove('showInfoPersonalForm');
        $session->remove('monto_temp');
        $session->remove('plazo_temp');
        $session->remove('tipo_desembolso');

        $session->remove('last_activity');
        $session->remove('expire_time');

        $session->remove('tkn_access');

        //drupal_set_message("Se presento un error de conexión, inténtelo más tarde","error");
        $session->set('message',"Tu usuario y contraseña no son correctos");
        $session->set('labelbutton',"Aceptar");
        $form_state->setRedirect('linxecredit.showmessage');
      }
      

    }
    //print_r($objAutenticacion);
    //exit();

  }

}

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

    $config = \Drupal::config('linxecredit.settings');
    $adelantonomina_rangos = $config->get('linxecredit.adelantonomina_rangos');
    $adelantonomina_montos_adelanto = $config->get('linxecredit.adelantonomina_montos_adelanto');
    $adelantonomina_montos_salario = $config->get('linxecredit.adelantonomina_montos_salario');
    $arrayOne = explode(",",$adelantonomina_rangos);
    $arrayDos = explode(",",$adelantonomina_montos_adelanto);

    $arrayTres = explode(",",$adelantonomina_montos_salario);
    $salarioMinimo = $arrayOne[0];

    //var configuracion
    $minMesesCotizados = $config->get('linxecredit.minimo_meses_cotizados');


    $field=$form_state->getValues();
    $session = \Drupal::request()->getSession();
    $adelantolib = new AdelantoLibrary();

    $session->remove('tkn_access');
    $session->remove('tkn_expiresin');

    $linxelib = new LinxeLibrary();

    $user = $field["usuario"];
    $pass = $field["password"];

    $returnArray = json_decode($adelantolib->getUserDataByDocument($user));
    $dataUser = $returnArray->userData;

    if($dataUser->documento == $user)
    {
      $tipoProductoUser = "adelanto";
    }else{
      $tipoProductoUser = "libranza";
    }

    if($tipoProductoUser == "libranza"){ //se logueará por datascoring para crédito libranza
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
            $session->remove('tasa');

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

            $session->set('tasa', $objAutenticacion->SpResult->Table[0]->Tasa);


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
          $session->remove('tasa');

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
        $session->remove('tasa');

        //drupal_set_message("Se presento un error de conexión, inténtelo más tarde","error");
        $session->set('message',"Se presento un error de conexión, inténtelo más tarde");
        $session->set('labelbutton',"Aceptar");
        $form_state->setRedirect('linxecredit.showmessage');
      }
    }else if($tipoProductoUser == "adelanto"){  //se logueará por drupal para adelanto de nómina
      
      //validamos si no tiene una solicitud de adelanto vigente hay que hacer el proceso nuevamente
      if(!is_numeric($dataUser->idadelanto))
      {
        $arrayMareigua["tipodocumento"] =$dataUser->tipodocumento;
        $arrayMareigua["documento"] = $dataUser->documento;
        //hacemos nuevamente la consulta con mareigua
        $objMareigua = $adelantolib->mareiguaAnalyticsIE($arrayMareigua);
        $nitCurrent = $dataUser->nit_empresa;

        
        //hay que meter la funcion de seleccionarMejorEmpresa en donde le envíe el nit de la empresa 
        //y esta funcion verifique que efectivamente en mareigua si aparezca esta empresa con ese nit como aportante
        if($objMareigua->respuesta_id!=4)
        {
          $session->set('titlemsg',"HA OCURRIDO UN ERROR");
          $session->set('message',"No hemos podido validar tu información, inténtalo más tarde");
          $session->set('labelbutton',"Aceptar");

          $mensajeError = "No hemos podido validar tu información, inténtalo más tarde";
          $titleMsg = "HA OCURRIDO UN ERROR";
          $emailto = $dataUser->email;
          $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);

          $form_state->setRedirect('linxecredit.showmessage');
          return;
        }else{
          
          $empresaSel = $adelantolib->seleccionarMareiguaEmpresaByNIT($objMareigua,$nitCurrent);
          
          if($empresaSel == false)
          {
            
            $session->set('titlemsg',"HA OCURRIDO UN ERROR");
            $session->set('message',"Lo sentimos, la empresa seleccionada no fue encontrada en la validación interna que realizamos");
            $session->set('labelbutton',"Aceptar");

            $mensajeError = "Lo sentimos, la empresa seleccionada no fue encontrada en la validación interna que realizamos";
            $titleMsg = "HA OCURRIDO UN ERROR";
            $emailto = $dataUser->email;
            $resultmail = $adelantolib->sendMail_error("LINXE LOGIN - ".$titleMsg,$mensajeError,$emailto);

            $form_state->setRedirect('linxecredit.showmessage');
            return;
          }else{
            //hacemos la validacion de las reglas para ver si el usuario cumple con las condiciones para adelanto de nómina
            echo "entro aqui 0";
            $rangoSalario = $dataUser->rango_salarial;
            $registraPagos6Meses = $dataUser->registra_pagos_6_meses;
            $eps_sel = $dataUser->eps;
            $afp_sel = $dataUser->afp;

            
            $usuario_preexistente=true;
            $objValidacion = json_decode($adelantolib->validationRules($empresaSel,$salarioMinimo,$minMesesCotizados,$rangoSalario,$registraPagos6Meses,$eps_sel,$afp_sel,$usuario_preexistente,$dataUser->idregistro));
            if($objValidacion->status == "accepted")
            {
              //validacion si la empresa existe con convenio adelanto de nomina y que no sea rechazada, si no existe se crea en la tabla de empresas
              $crearEmpresaAN = $adelantolib->validarCrearEmpresaAN($empresaSel);
              if($crearEmpresaAN["status"] == "fail" && $crearEmpresaAN["msg"] == "convenio_rechazado")
              {
                $session->set('titlemsg',"HA OCURRIDO UN ERROR");
                $session->set('message',"Lo sentimos, la empresa seleccionada no aceptó el convenio con nuestra plataforma");
                $session->set('labelbutton',"Aceptar");

                $mensajeError = "Lo sentimos, la empresa seleccionada no aceptó el convenio con nuestra plataforma";
                $titleMsg = "HA OCURRIDO UN ERROR";
                $emailto = $dataUser->email;
                $resultmail = $adelantolib->sendMail_error("LINXE REGISTRO - ".$titleMsg,$mensajeError,$emailto);
            

                $form_state->setRedirect('linxecredit.showmessage');
              }elseif($crearEmpresaAN["status"] == "fail"){
                $session->set('titlemsg',"HA OCURRIDO UN ERROR");
                $session->set('message',"Lo sentimos, la empresa no pudo ser creada en nuestra plataforma");
                $session->set('labelbutton',"Aceptar");
                $form_state->setRedirect('linxecredit.showmessage');
              }else{
                $arrayFields["tipodocumento"] = $dataUser->tipodocumento;
                $arrayFields["documento"] = $dataUser->documento;
                $arrayFields["email"] = $dataUser->email;
                $arrayFields["nombre"] = $dataUser->nombre;
                $arrayFields["primer_apellido"] = $dataUser->primer_apellido;
                //agregamos campos adicionales de la empresa para el registro adelanto nómina
                if($crearEmpresaAN["msg"] == "ya_creada_convenio"){
                  $arrayFields["convenio_empresa"] = "si";
                  $arrayFields["nit_empresa"] = $empresaSel["numero_identificacion_aportante"];
                  $arrayFields["idempresa"] = $crearEmpresaAN["idempresa"];
                }else{
                  $arrayFields["convenio_empresa"] = "no";
                  $arrayFields["nit_empresa"] = $empresaSel["numero_identificacion_aportante"];
                  $arrayFields["idempresa"] = $crearEmpresaAN["idempresa"];
                }
                
                //registro
                $objRegister = json_decode($adelantolib->registerAdelantoNomina($arrayFields));
                if($objRegister->status=="ok")
                {
                  $idregistro = $objRegister->id;
                  //creamos una solicitud o retomamos una que ya estaba pendiente
                  $montoMaximoAprobado = $adelantolib->getMontoMaximo($empresaSel["salario_cotizado"]);
                  $objSolicitud = json_decode($adelantolib->crearRegistroSolicitud($idregistro,$arrayFields,$objMareigua,$montoMaximoAprobado,$empresaSel));

                  if($objSolicitud->operation=="create" ){
                    //creamos el usuario en el sistema
                    $titleMsg = "LINXE - SOLICITUD APROBADA";
                    $mensaje = "<p>Hola ".$arrayFields["nombre"]." ".$arrayFields["primer_apellido"].", tu solicitud de Adelanto de Salario ha sido aprobada con un monto $ ".number_format($montoMaximoAprobado,0,",",".").".</p><br/>";
                    $mensaje .= "<p>Ingresa con el usuario que se te envió al correo y continúa con el proceso.</p>";
                    $emailto = $arrayFields["email"];
                    $resultmail = $adelantolib->sendMail_success($titleMsg,$mensaje,$emailto);
                    
                    //actualizamos el elemento datauser para que traiga la nueva solicitud
                    $returnArray = json_decode($adelantolib->getUserDataByDocument($user));
                    $dataUser = $returnArray->userData;

                  }
                  
                }else{
                  $session->set('message',$objSolicitud->msg);
                  $session->set('labelbutton',"Aceptar");
                  
                  $mensajeError = $objSolicitud->msg;
                  $titleMsg = "HA OCURRIDO UN ERROR";
                  $emailto = $dataUser->email;
                  $resultmail = $adelantolib->sendMail_error("LINXE LOGIN - ".$titleMsg,$mensajeError,$emailto);

                  $form_state->setRedirect('linxecredit.showmessage');
                  return;
                }
                
              }
            }else{
              
              $session->set('message',$objValidacion->msg);
              $session->set('labelbutton',"Aceptar");
              
              $mensajeError = $objValidacion->msg;
              $titleMsg = "HA OCURRIDO UN ERROR";
              $emailto = $dataUser->email;
              $resultmail = $adelantolib->sendMail_error("LINXE LOGIN - ".$titleMsg,$mensajeError,$emailto);

              $form_state->setRedirect('linxecredit.showmessage');
              return;
            }
          }

        }
        
      }
      //hasta aqui cuando no hay una solicitud vigente para el usuario

      

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
            $session->remove('convenio_empresa');

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
            //autenticó correctamente usuario para adelanto de nómina
            
            $session->set('tipoproducto', "adelanto");
            $session->set('tipodocumento', $dataUser->tipodocumento);
            $session->set('numerodocumento', $dataUser->documento);
            $session->set('idregistro', $dataUser->idregistro);
            $session->set('nombres', strtoupper($dataUser->nombre." ".$dataUser->primer_apellido) );
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
            $session->set('convenio_empresa',$dataUser->convenio_empresa);

            $session->set('tipo_desembolso',"");

            $session->set('last_activity',time());
            $session->set('expire_time',10*60);//expire time in seconds: 10 minutes

            
            $form_state->setRedirect('linxecredit.dashboard-adelanto');
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
          $session->remove('convenio_empresa');

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
        $session->remove('convenio_empresa');

        $session->remove('last_activity');
        $session->remove('expire_time');

        $session->remove('tkn_access');

        //drupal_set_message("Se presento un error de conexión, inténtelo más tarde","error");
        $session->set('message',"Se presento un error de conexión, inténtelo más tarde");
        $session->set('labelbutton',"Aceptar");
        $form_state->setRedirect('linxecredit.showmessage');
      }
      
      
      /*
      // AUTENTICAR CON DRUPAL
      $uid = \Drupal::service('user.auth')->authenticate($user, $pass);
      if(is_numeric($uid))
      {
        $user = \Drupal\user\Entity\User::load($uid);
        //user_login_finalize($user);
        //echo "uid: ".$uid;

        
        
        $session->set('tipoproducto', "adelanto");
        $session->set('tipodocumento', $dataUser->tipodocumento);
        $session->set('numerodocumento', $dataUser->documento);
        $session->set('idregistro', $dataUser->idregistro);
        $session->set('nombres', strtoupper($dataUser->nombre." ".$dataUser->primer_apellido) );
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
        $session->set('convenio_empresa',$dataUser->convenio_empresa);

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
        $session->remove('convenio_empresa');

        $session->remove('last_activity');
        $session->remove('expire_time');

        $session->remove('tkn_access');

        //drupal_set_message("Se presento un error de conexión, inténtelo más tarde","error");
        $session->set('message',"Tu usuario y contraseña no son correctos");
        $session->set('labelbutton',"Aceptar");
        $form_state->setRedirect('linxecredit.showmessage');
      }
      */
      

    }

    //print_r($objAutenticacion);
    //exit();

  }

}

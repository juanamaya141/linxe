<?php

namespace Drupal\linxecredit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;
use Drupal\linxecredit\Libs\AdelantoLibrary as AdelantoLibrary;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
/**
 * Class DatosEmpresaForm.
 *
 * @package Drupal\linxecredit\Form
 */
class DatosEmpresaForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxedatosempresa_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $session = \Drupal::request()->getSession();
    $config = $this->config('linxecredit.settings');
    $paththeme = base_path().drupal_get_path('theme', 'linxe');
    $basepath = base_path();

    $linxelib = new LinxeLibrary();
    $adelantolib = new AdelantoLibrary();
    //validar sesion
    if($linxelib->validaVigenciaSesion()==false)
    {
        $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
        return new RedirectResponse($url);
    }

    $userObj = (object) array();
    
    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $userObj->name = $session->get('nombres');
      $id_tipodoc = intval($session->get('tipodocumento'));
      $num_id = intval($session->get('numerodocumento'));
      
      $creditovigente = $session->get('creditovigente');
      $estatus = $session->get('estatus');
      $validaempresa = $session->get('validaempresa');
      $email = $session->get('email');
      $celular = $session->get('celular');
      $modificarSeleccion = $session->get('modificarSeleccion');

      $idregistro = $session->get('idregistro');

      $convenio_empresa = $session->get('convenio_empresa');

      
      $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
      $dataUser = $returnArray->userData;
      //print_r($dataUser);
      $valormontoaprobado = $dataUser->monto_maximo_aprobado;
      $valormontoseleccionado = $dataUser->valor_solicitado;

      switch( $dataUser->estado_general_solicitud )
      {
        case "solicitada": 
          //$form_state->setRedirect('linxecredit.dashboard-adelanto');
          //break;
          $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-adelanto')->toString();
          return new RedirectResponse($url);
          break;
        case "validacion_desembolso": 
          //$form_state->setRedirect('linxecredit.dashboard-desembolsoadelanto');
          //break;
          $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-desembolsoadelanto')->toString();
          return new RedirectResponse($url);
          break;
        
      }
      
    }else{
        $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
        return new RedirectResponse($url);
    }
    
    $arrayBcos = ["BANCO DE BOGOTÁ",
    "BANCO POPULAR",
    "ITAÚ CORPBANCA COLOMBIA S.A.",
    "BANCOLOMBIA S.A.",
    "CITIBANK COLOMBIA",
    "GNB SUDAMERIS S.A.",
    "BBVA COLOMBIA",
    "COLPATRIA",
    "SCOTIANBANK",
    "BANCO DE OCCIDENTE",
    "BANCO CAJA SOCIAL - BCSC S.A.",
    "BANCO AGRARIO DE COLOMBIA S.A.",
    "BANCO DAVIVIENDA S.A.",
    "BANCO AV VILLAS",
    "BANCO W S.A.",
    "BANCO CREDIFINANCIERA S.A.C.F",
    "BANCAMIA",
    "BANCO PICHINCHA S.A.",
    "BANCOOMEVA",
    "CMR FALABELLA S.A.",
    "BANCO FINANDINA S.A.",
    "BANCO SANTANDER DE NEGOCIOS COLOMBIA S.A.",
    "BANCO COOPERATIVO COOPCENTRAL",
    "BANCO COMPARTIR S.A",
    "BANCO SERFINANZA S.A",
    "BANCO PROCREDIT",
    "BANCO MUNDO MUJER S.A.",
    "BANCO MULTIBANK S.A.",
    ];

    $arrayBancos[""] = "" ;
    foreach($arrayBcos as $key=>$bco){
      $arrayBancos[$bco]=$bco;
    }
    
    $form['cuenta_nomina_banco'] = array(
      '#type' => 'select',
      '#title' => t('Banco:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayBancos,
      '#attributes' => array(
        'data-msj' => t("Banco donde tienes tu cuenta de nómina, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $arrayTipoCta[""] = "" ;
    $arrayTipoCta["ahorros"] = "Cuenta de Ahorros" ;
    //$arrayTipoDocs["2"] = "N.I.T." ;
    $arrayTipoCta["corriente"] = "Cuenta Corriente" ;


    $form['cuenta_nomina_tipocta'] = array(
      '#type' => 'select',
      '#title' => t('Tipo de Cuenta de Nómina:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#options' => $arrayTipoCta,
      '#attributes' => array(
        'data-msj' => t("Tipo de cuenta de nómina, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['cuenta_nomina_numcta'] = array(
      '#type' => 'textfield',
      '#title' => t('Número de Cuenta Nómina:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Número de cuenta de nómina, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );

    $form['fecha_nacimiento'] = array(
      '#type' => 'textfield',
      '#title' => t('Fecha de Nacimiento:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#id' => 'fecha_nacimiento',
      '#attributes' => array(
        //'placeholder' => t('PRIMER NOMBRE'),
        'data-msj' => t("Fecha de Nacimiento, es un dato obligatorio")
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );


    if($convenio_empresa != "si")
    {
      $form['nombre_contacto'] = array(
        '#type' => 'textfield',
        '#title' => t('Primer Nombre:'),
        '#required' => TRUE,
        '#default_value' => '',
        '#label_display' =>'after',
        '#attributes' => array(
          'data-msj' => t("Primer nombre es un dato obligatorio"),
        ),
        '#wrapper_attributes'=> array(
          'class' => "",
        )
      );
  
      $form['apellido_contacto'] = array(
        '#type' => 'textfield',
        '#title' => t('Primer Apellido:'),
        '#required' => TRUE,
        '#default_value' => '',
        '#label_display' =>'after',
        '#attributes' => array(
          'data-msj' => t("Primer apellido es un dato obligatorio")
        ),
        '#wrapper_attributes'=> array(
          'class' => "",
        )
      );
  
      $form['telefono_contacto'] = array(
        '#type' => 'tel',
        '#title' => t('Teléfono:'),
        '#required' => TRUE,
        '#default_value' => '',
        '#mask' => '+7(999)999-9999',
        '#size' => 10,
        '#label_display' =>'after',
        '#attributes' => array(
          'data-msj' => t("Teléfono es un dato obligatorio"),
          'maxlength' => 10
        ),
        '#wrapper_attributes'=> array(
          'class' => "",
          'max' => 10,
        )
      );
  
      $form['correo_contacto'] = array(
        '#type' => 'email',
        '#title' => t('Correo Electrónico:'),
        '#required' => TRUE,
        '#default_value' => '',
        '#label_display' =>'after',
        '#attributes' => array(
          'data-msj' => t("Correo electrónico es un dato obligatorio")
        ),
        '#wrapper_attributes'=> array(
          'class' => "",
        )
      );

      $form['ciudad_contacto'] = array(
        '#type' => 'textfield',
        '#title' => t('Ciudad Empresa:'),
        '#required' => TRUE,
        '#default_value' => '',
        '#label_display' =>'after',
        '#attributes' => array(
          'data-msj' => t("Ciudad Empresa es un dato obligatorio")
        ),
        '#wrapper_attributes'=> array(
          'class' => "",
        )
      );

    }
    
    

    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Finalizar'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    
    $form['#myvars']['tipodoc'] = $tipodoc;
    $form['#myvars']['numdoc'] = str_replace(" ","", $num_id);
    $form['#myvars']['valormontoaprobado'] = $valormontoaprobado;
    $form['#myvars']['valormontoseleccionado'] = $valormontoseleccionado;
    $form['#myvars']['nombres'] = $userObj->name;
    $form['#myvars']['convenio_empresa'] = $convenio_empresa;
    

    $form['#theme'] = 'dashboard_datosempresa';
    $form['#attached'] = [
          'library' => [
            'linxecredit/librarydatosempresa', //include our custom library for this response
          ],
          'drupalSettings' => [
            'linxecredit' => [
              'librarydatosempresa' => [
                'valormontoaprobado'=> $valormontoaprobado,
              ],
            ], 
          ]
        ];
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $session = \Drupal::request()->getSession();

    $convenio_empresa = $session->get('convenio_empresa');
    $email_usuario = $session->get('email');
    $celular_usuario = $session->get('celular');
    $nombre_usuario = $session->get('nombres');
    //datos contacto empresa
    if($convenio_empresa != "si")
    {
      $nombre_contacto = $form_state->getValue('nombre_contacto');
      if($nombre_contacto == "") {
          $form_state->setErrorByName('nombre_contacto', $this->t('Escribe el nombre del contacto de tu empresa.'));
      }
      if($nombre_contacto == $nombre_usuario) {
        $form_state->setErrorByName('nombre_contacto', $this->t('Escribe el nombre del contacto de tu empresa, debe ser diferente a tu nombre.'));
      }

      $apellido_contacto = $form_state->getValue('apellido_contacto');
      if($apellido_contacto == "") {
          $form_state->setErrorByName('apellido_contacto', $this->t('Escribe el apellido del contacto de tu empresa.'));
      }

      $telefono_contacto = $form_state->getValue('telefono_contacto');
      if($telefono_contacto == "") {
          $form_state->setErrorByName('telefono_contacto', $this->t('Escribe el teléfono del contacto de tu empresa.'));
      }
      if($telefono_contacto == $celular_usuario) {
        $form_state->setErrorByName('telefono_contacto', $this->t('Escribe el teléfono del contacto de tu empresa, por favor ingresa un número de contacto diferente al que registraste previamente.'));
      }

      $correo_contacto = $form_state->getValue('correo_contacto');
      if($correo_contacto == "") {
          $form_state->setErrorByName('correo_contacto', $this->t('Escribe el correo electrónico del contacto de tu empresa,'));
      }
      if($correo_contacto == $email_usuario) {
        $form_state->setErrorByName('correo_contacto', $this->t('Escribe el correo electrónico del contacto de tu empresa, por favor ingresa un correo electrónico de contacto diferente al que registraste previamente.'));
      }
      if( !filter_var($correo_contacto, FILTER_VALIDATE_EMAIL) )
      {
        $form_state->setErrorByName('correo_contacto', $this->t('El correo electrónico no es válido'));
      }

      $ciudad_contacto = $form_state->getValue('ciudad_contacto');
      if($ciudad_contacto == "") {
          $form_state->setErrorByName('ciudad_contacto', $this->t('Escribe la ciudad donde está ubicada tu empresa.'));
      }

    }
    //cuenta de nómina
    $cuenta_nomina_banco = $form_state->getValue('cuenta_nomina_banco');
    if($cuenta_nomina_banco == "") {
        $form_state->setErrorByName('cuenta_nomina_banco', $this->t('Selecciona el banco.'));
    }
    $cuenta_nomina_tipocta = $form_state->getValue('cuenta_nomina_tipocta');
    if($cuenta_nomina_tipocta == "") {
        $form_state->setErrorByName('cuenta_nomina_tipocta', $this->t('Selecciona el tipo de cuenta de nómina.'));
    }
    $cuenta_nomina_numcta = $form_state->getValue('cuenta_nomina_numcta');
    if($cuenta_nomina_numcta == "") {
         $form_state->setErrorByName('cuenta_nomina_numcta', $this->t('Ingresa tu número de cuenta de nómina.'));
    }
    if(!is_numeric($cuenta_nomina_numcta)) {
         $form_state->setErrorByName('cuenta_nomina_numcta', $this->t('La cuenta de nómina debe ser un valor numérico.'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $field=$form_state->getValues();
    $session = \Drupal::request()->getSession();
    $config = $this->config('linxecredit.settings');
    $paththeme = base_path().drupal_get_path('theme', 'linxe');
    $basepath = base_path();

    $linxelib = new LinxeLibrary();
    $adelantolib = new AdelantoLibrary();
    //validar sesion
    if($linxelib->validaVigenciaSesion()==false)
    {
        $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
        return new RedirectResponse($url);
    }

    $userObj = (object) array();
    
    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $userObj->name = $session->get('nombres');
      $id_tipodoc = intval($session->get('tipodocumento'));
      $num_id = intval($session->get('numerodocumento'));
      
      $creditovigente = $session->get('creditovigente');
      $estatus = $session->get('estatus');
      $validaempresa = $session->get('validaempresa');
      $email = $session->get('email');
      $celular = $session->get('celular');
      $modificarSeleccion = $session->get('modificarSeleccion');

      $idregistro = $session->get('idregistro');
      $convenio_empresa = $session->get('convenio_empresa');

      
      $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));

      $dataUser = $returnArray->userData;
      $idadelanto = $dataUser->idadelanto;
      $idempresa = $dataUser->idempresa;
      $valormontoaprobado = $dataUser->monto_maximo_aprobado;
      $valormontoseleccionado = $dataUser->valor_solicitado;

      

      $banco = $field["cuenta_nomina_banco"];
      $tipocta = $field["cuenta_nomina_tipocta"];
      $numcta = $field["cuenta_nomina_numcta"];

      $returnSetBcoArray = json_decode($adelantolib->setDatosBancarios($idadelanto,$banco,$tipocta,$numcta));

      //exit();
      if($returnSetBcoArray->status=="ok")
      {
        $fecha_nacimiento = $field["fecha_nacimiento"];
        $returnActualizaFechaNac = json_decode($adelantolib->setActualizaFechaNac($idregistro,$fecha_nacimiento));

        if($convenio_empresa != "si")
        {
          $nombre = $field["nombre_contacto"];
          $apellido = $field["apellido_contacto"];
          $telefono = $field["telefono_contacto"];
          $email = $field["correo_contacto"];
          $ciudad = $field["ciudad_contacto"];
          
          $returnSetEmpresaArray = json_decode($adelantolib->setDatosEmpresa($idadelanto,$nombre,$apellido,$telefono,$email,$idempresa,$ciudad));

        }

        /**
         * ENVÍO NUEVO DE LA NOTIFICACIÓN de T&C
         */
        $configuration = [];
        $configuration["cargo_administracion_adelanto"] = $config->get('linxecredit.cargo_administracion_adelanto');
        $configuration["cargo_tecnologia_adelanto"] = $config->get('linxecredit.cargo_tecnologia_adelanto');
        $configuration["seguro_adelanto"] = $config->get('linxecredit.seguro_adelanto');
        $configuration["iva_adelanto"] = $config->get('linxecredit.iva_adelanto');

        //acá se debería lanzar la generación del pdf de términos y condiciones
        $empresa = $adelantolib->getEmpresaByNit($dataUser->nit_empresa);
          
        $arrayData = [];
        $arrayData["nombres"] = $dataUser->nombre;
        $arrayData["apellidos"] = $dataUser->primer_apellido." ".$dataUser->segundo_apellido;
        $arrayData["tipo_identificacion"] = "Cédula Ciudadanía";
        $arrayData["identificacion"] = $dataUser->documento;
        $arrayData["tipo_producto"] = "Adelanto de Salario";
        $arrayData["valor_solicitado"] = $dataUser->valor_solicitado;
        $valorcuota = $dataUser->valor_solicitado + $dataUser->administracion + $dataUser->seguros + $dataUser->tecnologia + $dataUser->iva;
        $arrayData["valor_cuota"] = $valorcuota;
        $arrayData["tipo_tasa"] = "No Aplica";
        $arrayData["tasa_mora"] = "Máxima permitida por ley";
        $arrayData["costo_administracion_linxe"] = $configuration["cargo_administracion_adelanto"];
        $arrayData["costo_tecnologia_linxe"] = $configuration["cargo_tecnologia_adelanto"];
        $arrayData["razon_social"] = mb_strimwidth($empresa[0]["razon_social"], 0, 25, "...");
        $arrayData["periodicidad_pago"] = $empresa[0]["periodicidad_pago"];
        if($empresa[0]["periodicidad_pago"] == "quincenal"){
            $arrayData["num_cuotas"] = 2;
            $arrayData["monto_cuota"] = round($valorcuota/2);
        }else{
            $arrayData["num_cuotas"] = 1;
            $arrayData["monto_cuota"] = $valorcuota;
        }

        $htmlTerms = $adelantolib->getTermsConditionsHTML($arrayData);
        $today = date("Y-m-d");

        $dompdf = new Dompdf();
        $dompdf->load_html($htmlTerms);
        $dompdf->render();
        $pdfoutput = $dompdf->output();
        $filename = 'terminos-condiciones-'.$arrayData['identificacion'].'_'.$today.'.pdf';
        $filepath = 'sites/default/files/pdfterminos/'.$filename;
        $fp = fopen($filepath, "w+");
        if(fwrite($fp, $pdfoutput) !==  false)
        {
            //envio de mensaje de confirmación de los términos y condiciones
            if($dataUser->convenio_empresa=="si")
            {
                $titleMsg = "LINXE - TÉRMINOS Y CONDICIONES ADELANTO DE SALARIO / ".$dataUser->nombre." ".$dataUser->primer_apellido;
                $mensaje = "<h2>TÉRMINOS Y CONDICIONES<h2><br/>";
                $mensaje .= "<p><b>ADELANTO DE SALARIO / ".$dataUser->nombre." ".$dataUser->primer_apellido."</b></p><br/>";
                $mensaje = "<p>Podrás contar con el dinero en tu cuenta de nómina, a más tardar en el transcurso de las próximas 24 horas.</p><br/>";
                $mensaje .= "<p>Siempre que necesites ayuda, puedes contar con nosotros ingresando a <a href='https://www.linxe.com' target='_blank'>www.linxe.com</a></p><br/>";
                $mensaje .= "<p>Nos interesa seguir mejorando, cuéntanos como podemos hacerlo. Click aquí <a href='https://www.linxe.com/form/nps' target='_blank'>www.linxe.com/form/nps</a>.</p>";
                $mensaje .= "<br><br><p>Equipo Linxe</p>";
            }else{
                $titleMsg = "LINXE - TÉRMINOS Y CONDICIONES ADELANTO DE SALARIO / ".$dataUser->nombre." ".$dataUser->primer_apellido;
                $mensaje = "<h2>TÉRMINOS Y CONDICIONES<h2><br/>";
                $mensaje .= "<p><b>ADELANTO DE SALARIO / ".$dataUser->nombre." ".$dataUser->primer_apellido."</b></p><br/>";
                $mensaje = "<p>Una vez esté firmado y legalizado el convenio con tu empresa, podrémos desembolsar el dinero en tu cuenta de nómina.</p><br/>";
                $mensaje .= "<p>Siempre que necesites ayuda, puedes contar con nosotros ingresando a <a href='https://www.linxe.com' target='_blank'>www.linxe.com</a></p><br/>";
                $mensaje .= "<p>Nos interesa seguir mejorando, cuéntanos como podemos hacerlo. Click aquí <a href='https://www.linxe.com/form/nps' target='_blank'>www.linxe.com/form/nps</a>.</p>";
                $mensaje .= "<br><br><p>Equipo Linxe</p>";
            }
            
            $emailto = $dataUser->email;
            $resultmail = $adelantolib->sendMail_acceptTerms($titleMsg,$mensaje,$emailto,$filepath,$filename);

        }else{
          $session->set('message',"Error send terms email");
          $session->set('labelbutton',"Aceptar");
          $form_state->setRedirect('linxecredit.showmessage');
        } 
        fclose($fp);
        /**
        * Hasta aquí envío notificación de T&C
        */
        $form_state->setRedirect('linxecredit.dashboard-desembolsoadelanto');
      }else{
        $session->set('message',$returnSetBcoArray->error);
        $session->set('labelbutton',"Aceptar");
        $form_state->setRedirect('linxecredit.showmessage');
      }
    }else{
        $form_state->setRedirect('<front>');
    }


    
  }

}

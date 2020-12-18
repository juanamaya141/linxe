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
class InformacionPersonalForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linxeinfopersonal_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $session = \Drupal::request()->getSession();
    $config = $this->config('linxecredit.settings');

    $paththeme = base_path().drupal_get_path('theme', 'linxe');
    $basepath = base_path();

    $gl_cantidad_min = $config->get('linxecredit.cantidad_min');
    $gl_cantidad_max = $config->get('linxecredit.cantidad_max');
    $gl_meses_min = $config->get('linxecredit.meses_min');
    $gl_meses_max = $config->get('linxecredit.meses_max');

    if($session->has('tasa') && $session->get('tasa')!="")
      $gl_tasa = $session->get('tasa');
    else
      $gl_tasa = $config->get('linxecredit.tasa');
      
    $gl_seguro = $config->get('linxecredit.seguro');
    $gl_cuota = 0;
    $iva = $config->get('linxecredit.iva');
    $cargo_tecnologia = $config->get('linxecredit.cargo_tecnologia');
    $plazos = $config->get('linxecredit.plazos');

    $adelantonomina_rangos = $config->get('linxecredit.adelantonomina_rangos');
    $adelantonomina_montos_adelanto = $config->get('linxecredit.adelantonomina_montos_adelanto');
    $adelantonomina_montos_salario = $config->get('linxecredit.adelantonomina_montos_salario');
    $arrayOne = explode(",",$adelantonomina_rangos);
    $arrayDos = explode(",",$adelantonomina_montos_adelanto);
    $arrayTres = explode(",",$adelantonomina_montos_salario);

    $userObj = (object) array();

    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $tipoproducto = $session->get('tipoproducto');
      if( $session->has('tipoproducto') == "adelanto" )
      {
        $userObj->name = $session->get('nombres');
        $id_tipodoc = $session->get('tipodocumento');
        $num_id = $session->get('numerodocumento');
        $valormontoaprobado = $session->get('montoaprobado');
        $valormontoseleccionado = $session->get('montoseleccionado');
        $plazo = $session->get('plazo');
        $nombreempresa = $session->get('nombreempresa');
        $plazo = $session->get('plazo');
        $creditovigente = $session->get('creditovigente');
        $estatus = $session->get('estatus');
        $validaempresa = $session->get('validaempresa');
        $showPreferenciaForm = $session->get('showPreferenciaForm');
        $showInfoPersonalForm = $session->get('showInfoPersonalForm');
        $email = $session->get('email');
        $celular = $session->get('celular');
        $modificarSeleccion = $session->get('modificarSeleccion');
      }else{
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
        $showInfoPersonalForm = $session->get('showInfoPersonalForm');
        $email = $session->get('email');
        $celular = $session->get('celular');
        $modificarSeleccion = $session->get('modificarSeleccion');
      }
      
    }else{
      $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
      return new RedirectResponse($url);
    }

    $linxelib = new LinxeLibrary();
        
    //validar sesion
    if($linxelib->validaVigenciaSesion()==false)
    {
      $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
      return new RedirectResponse($url);
    }
    

    
    
    $form['password_old'] = array(
      '#type' => 'password',
      '#title' => t('Contraseña actual:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER APELLIDO'),
        'data-msj' => t("Escribe tu Contraseña actual"),
        'class' => array("password"),
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

    $form['password2'] = array(
      '#type' => 'password',
      '#title' => t('Confirmar Contraseña:'),
      '#required' => TRUE,
      '#default_value' => '',
      '#label_display' =>'after',
      '#attributes' => array(
        //'placeholder' => t('PRIMER APELLIDO'),
        'data-msj' => t("Escribe la confirmación de tu contraseña"),
        'class' => array("password"),
      ),
      '#wrapper_attributes'=> array(
        'class' => "",
      )
    );
    

    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Cambiar contraseña'),
        '#attributes' => ['class' => array("btn1","mt-2","w-100")],
        
        '#prefix' => '',
        '#suffix' => '',
    );

    switch ($id_tipodoc) {
      case 1:
        $tipodoc = "Cédula de ciudadanía";
        break;
      case 2:
        $tipodoc = "N.I.T.";
        break;
      case 3:
        $tipodoc = "Cédula de extranjería";
        break;
      case 4:
        $tipodoc = "Tarjeta de identidad";
        break;
      default:
        $tipodoc = "C.C.";
        break;
    }
    $form['#myvars']['tipodoc'] = $tipodoc;
    $form['#myvars']['numdoc'] = str_replace(" ","", $num_id);
    $form['#myvars']['celular'] = $linxelib->ofuscarTexto($celular);
    $form['#myvars']['email'] = $linxelib->ofuscarTexto($email);
    $form['#myvars']['nombres'] = $userObj->name;
    $form['#myvars']['valormontoaprobado'] = $valormontoaprobado;
    $form['#myvars']['showPreferenciaForm'] = $showPreferenciaForm;
    $form['#myvars']['showInfoPersonalForm'] = $showInfoPersonalForm;
    $form['#myvars']['tipoproducto'] = $tipoproducto;

    

    $form['#theme'] = 'linxeinfopersonaltheme_form';
    $form['#attached'] = [
          'library' => [
            'linxecredit/libraryinformacionpersonal', //include our custom library for this response
          ],
          'drupalSettings' => [
            'linxecredit' => [
              'libraryinformacionpersonal' => [
                'tipoproducto'=> $tipoproducto,
                'showPreferenciaForm'=> $showPreferenciaForm,
                'nombres'=> $userObj->name,
                'valormontoaprobado'=> number_format($valormontoaprobado,0,",","."),
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

    $password_old = $form_state->getValue('password_old');
    if($password_old == "") {
         $form_state->setErrorByName('password_old', $this->t('Escribe tu Contraseña actual.'));
    }

    $password = $form_state->getValue('password');
    if($password == "") {
         $form_state->setErrorByName('password', $this->t('Escribe tu Contraseña.'));
    }

    $password2 = $form_state->getValue('password2');
    if($password2 == "") {
         $form_state->setErrorByName('password2', $this->t('Escribe la confirmación de tu contraseña'));
    }

    if($password != $password2)
    {
      $form_state->setErrorByName('password2', $this->t('La contraseña y su confirmación no coinciden.'));
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
    }else{
      $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
      return new RedirectResponse($url);
    }

    $field=$form_state->getValues();

   
    $dataField["UserLogin"] = $num_id;
    $dataField["PswOlg"] = $field["password_old"];
    $dataField["PwsNew"] = $field["password"];

    $linxelib = new LinxeLibrary();

    //registro
    $objRegister = $linxelib->getChangePw($dataField);
    //exit();
    
    if(array_key_exists('Success', $objRegister))
    {

      if($objRegister->Success==true){
        $pos = strpos($objRegister->SpResult, "Error");
        if($pos === false)
        {
          $session->set('showInfoPersonalForm',false);
          //drupal_set_message("Contraseña Actualizada Satisfactoriamente");
        }else{
          drupal_set_message($objRegister->SpResult,"error");
        }
        
        $form_state->setRedirect('linxecredit.dashboard-informacionpersonal'); 
      }else{
        $session->set('message',$objRegister->SpResult);
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

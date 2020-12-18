<?php
namespace Drupal\empresas\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\empresas\Libs\EmpresasLibrary as EmpresasLibrary;


/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class RegistrarEmpresa extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'registrar_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['id'])) {
        $query = $conn->select('empresas', 'm')
            ->condition('idempresa', $_GET['id'])
            ->fields('m');
        $record = $query->execute()->fetchAssoc();
    }

    $form['tipo_identificacion'] = array(
        '#type' => 'select',
        '#title' => ('Tipo de identificación:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['tipo_identificacion']) && $_GET['id']) ? $record['tipo_identificacion']:'',
        '#options' => array(
            '1' => t('NIT'),
            '2' => t('Cédula de Ciudadania'),
        ),
    );

    $form['identificacion'] = array(
        '#type' => 'textfield',
        '#title' => t('Número de identificacion:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['identificacion']) && $_GET['id']) ? $record['identificacion']:'',
    );

    $form['razon_social'] = array(
        '#type' => 'textfield',
        '#title' => t('Razón Social:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['razon_social']) && $_GET['id']) ? $record['razon_social']:'',
    );

    $form['periodicidad_pago'] = array(
        '#type' => 'select',
        '#title' => ('Periodicidad de pago:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['periodicidad_pago']) && $_GET['id']) ? $record['periodicidad_pago']:'',
        '#options' => array(
            'mensual' => t('Mensual'),
            'quincenal' => t('Quincenal'),
        ),
    );

    $form['fecha_corte_1'] = array(
        '#type' => 'textfield',
        '#title' => t('Fecha de Corte 1:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['fecha_corte_1']) && $_GET['id']) ? $record['fecha_corte_1']:'',
    );

    $form['fecha_corte_2'] = array(
        '#type' => 'textfield',
        '#title' => t('Fecha de Corte 2:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['fecha_corte_2']) && $_GET['id']) ? $record['fecha_corte_2']:'',
    );

    $form['fecha_pago_1'] = array(
        '#type' => 'textfield',
        '#title' => t('Fecha de Pago 1:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['fecha_pago_1']) && $_GET['id']) ? $record['fecha_pago_1']:'',
    );

    $form['fecha_pago_2'] = array(
        '#type' => 'textfield',
        '#title' => t('Fecha de Pago 2:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['fecha_pago_2']) && $_GET['id']) ? $record['fecha_pago_2']:'',
    );

    $form['contacto_nombres'] = array(
        '#type' => 'textfield',
        '#title' => t('Nombres del contacto de la empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['contacto_nombres']) && $_GET['id']) ? $record['contacto_nombres']:'',
    );

    $form['contacto_apellidos'] = array(
        '#type' => 'textfield',
        '#title' => t('Apellidos del contacto de la empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['contacto_apellidos']) && $_GET['id']) ? $record['contacto_apellidos']:'',
    );

    $form['contacto_cargo'] = array(
        '#type' => 'textfield',
        '#title' => t('Cargo del contacto de la empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['contacto_cargo']) && $_GET['id']) ? $record['contacto_cargo']:'',
    );

    $form['telefono'] = array(
        '#type' => 'textfield',
        '#title' => t('Telefono del contacto de la empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['telefono']) && $_GET['id']) ? $record['telefono']:'',
    );

    $form['direccion'] = array(
        '#type' => 'textfield',
        '#title' => t('Dirección del contacto de la empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['direccion']) && $_GET['id']) ? $record['direccion']:'',
    );

    $form['email'] = array(
        '#type' => 'email',
        '#title' => t('Email del contacto de la empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['email']) && $_GET['id']) ? $record['email']:'',
    );

    $form['num_empleados'] = array(
        '#type' => 'textfield',
        '#title' => t('Número de Empleados:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['num_empleados']) && $_GET['id']) ? $record['num_empleados']:'',
    );


    $form['convenio_tipoproducto'] = array(
        '#type' => 'select',
        '#title' => ('Convenio del tipo de producto:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['convenio_tipoproducto']) && $_GET['id']) ? $record['convenio_tipoproducto']:'',
        '#options' => array(
            'libranza' => t('Libranza'),
            'adelanto' => t('Adelanto'),
        ),
    );

    $form['convenio_actividadecon'] = array(
        '#type' => 'select',
        '#title' => ('Convenio de la actividad economica:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['convenio_actividadecon']) && $_GET['id']) ? $record['convenio_actividadecon']:'',
        '#options' => array(
            'empleado' => t('Empleado'),
            'pensionado' => t('Pensionado'),
            'ambos' => t('Ambos'),
        ),
    );

    $form['estado_convenio'] = array(
        '#type' => 'select',
        '#title' => ('Estado del convenio:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['estado_convenio']) && $_GET['id']) ? $record['estado_convenio']:'',
        '#options' => array(
            'pendiente' => t('Pendiente'),
            'aceptado' => t('Aceptado'),
            'rechazado' => t('Rechazado'),
        ),

    );

    $form['td'] = array(
        '#type' => 'textfield',
        '#title' => t('TD Empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['td']) && $_GET['id']) ? $record['td']:'',
    );

    $form['regional'] = array(
        '#type' => 'textfield',
        '#title' => t('Regional Empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['regional']) && $_GET['id']) ? $record['regional']:'',
    );

    $form['sw_nomina'] = array(
        '#type' => 'textfield',
        '#title' => t('Software de Nomina de Empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['sw_nomina']) && $_GET['id']) ? $record['sw_nomina']:'',
    );

    $form['score'] = array(
        '#type' => 'textfield',
        '#title' => t('Score de Empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['score']) && $_GET['id']) ? $record['score']:'',
    );

    $form['tasa'] = array(
        '#type' => 'textfield',
        '#title' => t('Tasa de Empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['tasa']) && $_GET['id']) ? $record['tasa']:'',
    );

    $form['url_sw'] = array(
        '#type' => 'textfield',
        '#title' => t('URL de Software de Empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['url_sw']) && $_GET['id']) ? $record['url_sw']:'',
    );

    $form['tipo_servicio'] = array(
        '#type' => 'textfield',
        '#title' => t('TIpo de servicio de Software de Empresa:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['tipo_servicio']) && $_GET['id']) ? $record['tipo_servicio']:'',
    );

    $form['requiere_notificacion'] = array(
        '#type' => 'select',
        '#title' => ('Empresa requiere notificación:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['requiere_notificacion']) && $_GET['id']) ? $record['requiere_notificacion']:'',
        '#options' => array(
            'si' => t('Si'),
            'no' => t('No'),
        ),
    );

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'Guardar',
        //'#value' => t('Submit'),
    ];
    $form['#cache'] = [
        'max-age' => 0
    ];
  

    return $form;

  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

        $tipo_identificacion = $form_state->getValue('tipo_identificacion');
        if($tipo_identificacion == "") {
             $form_state->setErrorByName('tipo_identificacion', $this->t('Selecciona un tipo de identificación'));
        }

        $identificacion = $form_state->getValue('identificacion');
        if($identificacion == "") {
             $form_state->setErrorByName('identificacion', $this->t('Ingresa tu número de identificacion.'));
        }
    
        if(!is_numeric($identificacion)) {
             $form_state->setErrorByName('identificacion', $this->t('El número de identificación debe ser numérico.'));
        }
       
        $razon_social = $form_state->getValue('razon_social');
        if($razon_social == "") {
             $form_state->setErrorByName('razon_social', $this->t('Escribe la razón social'));
        }

        $periodicidad_pago = $form_state->getValue('periodicidad_pago');
        if($periodicidad_pago == "") {
             $form_state->setErrorByName('periodicidad_pago', $this->t('Selecciona la periodicidad del pago'));
        }

        $fecha_corte_1 = $form_state->getValue('fecha_corte_1');
        if($fecha_corte_1 == "") {
             $form_state->setErrorByName('fecha_corte_1', $this->t('Ingresa el dia de fecha de corte.'));
        }
    
        if(!is_numeric($fecha_corte_1)) {
             $form_state->setErrorByName('fecha_corte_1', $this->t('El dia de fecha de corte debe ser numérico.'));
        }

        $fecha_corte_2 = $form_state->getValue('fecha_corte_2');
        if($fecha_corte_2 == "") {
             $form_state->setErrorByName('fecha_corte_2', $this->t('Ingresa el dia de fecha de corte.'));
        }
    
        if(!is_numeric($fecha_corte_2)) {
             $form_state->setErrorByName('fecha_corte_2', $this->t('El dia de fecha de corte debe ser numérico.'));
        }
        
        $fecha_pago_1 = $form_state->getValue('fecha_pago_1');
        if($fecha_pago_1 == "") {
             $form_state->setErrorByName('fecha_pago_1', $this->t('Ingresa el dia de fecha de pago.'));
        }
    
        if(!is_numeric($fecha_pago_1)) {
             $form_state->setErrorByName('fecha_pago_1', $this->t('El dia de fecha de pago debe ser numérico.'));
        }

        $fecha_pago_2 = $form_state->getValue('fecha_pago_2');
        if($fecha_pago_2 == "") {
             $form_state->setErrorByName('fecha_pago_2', $this->t('Ingresa el dia de fecha de pago.'));
        }
    
        if(!is_numeric($fecha_pago_2)) {
             $form_state->setErrorByName('fecha_pago_2', $this->t('El dia de fecha de pago debe ser numérico.'));
        }

        $contacto_nombres = $form_state->getValue('contacto_nombres');
        if($contacto_nombres == "") {
             $form_state->setErrorByName('contacto_nombres', $this->t('Escriba los nombres'));
        }

        $contacto_apellidos = $form_state->getValue('contacto_apellidos');
        if($contacto_apellidos == "") {
             $form_state->setErrorByName('contacto_apellidos', $this->t('Escriba los apellidos'));
        }

        $contacto_cargo = $form_state->getValue('contacto_cargo');
        if($contacto_cargo == "") {
             $form_state->setErrorByName('contacto_cargo', $this->t('Escriba el cargo'));
        }

        $telefono = $form_state->getValue('telefono');
        if($telefono == "") {
             $form_state->setErrorByName('telefono', $this->t('Escriba el teléfono.'));
        }
    
        if(!is_numeric($telefono)) {
             $form_state->setErrorByName('telefono', $this->t('El teléfono debe ser numérico.'));
        }

        $direccion = $form_state->getValue('direccion');
        if($direccion == "") {
             $form_state->setErrorByName('direccion', $this->t('Escriba la dirección'));
        }

        $email = $form_state->getValue('email');
        if($email == "") {
             $form_state->setErrorByName('email', $this->t('Digita tu correo electrónico.'));
        }
    
        if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
        {
          $form_state->setErrorByName('email', $this->t('El correo electrónico no es válido'));
        }

        $num_empleados = $form_state->getValue('num_empleados');
        if($num_empleados == "") {
             $form_state->setErrorByName('num_empleados', $this->t('Ingresa el número de empleados.'));
        }


        $convenio_tipoproducto = $form_state->getValue('convenio_tipoproducto');
        if($convenio_tipoproducto == "") {
             $form_state->setErrorByName('convenio_tipoproducto', $this->t('Seleccione un convenio'));
        }

        $convenio_actividadecon = $form_state->getValue('convenio_actividadecon');
        if($convenio_actividadecon == "") {
             $form_state->setErrorByName('convenio_actividadecon', $this->t('Seleccione un convenio'));
        }

        $estado_convenio = $form_state->getValue('estado_convenio');
        if($estado_convenio == "") {
             $form_state->setErrorByName('estado_convenio', $this->t('Escriba un estado'));
        }

        $td = $form_state->getValue('td');
        if($td == "") {
             $form_state->setErrorByName('td', $this->t('Escriba un TD de empresa'));
        }

        if(!is_numeric($td)) {
            $form_state->setErrorByName('td', $this->t('El td debe ser numérico.'));
        }

        $regional = $form_state->getValue('regional');
        if($regional == "") {
             $form_state->setErrorByName('regional', $this->t('Escriba una regional'));
        }

        $sw_nomina = $form_state->getValue('sw_nomina');
        if($sw_nomina == "") {
             $form_state->setErrorByName('sw_nomina', $this->t('Escriba una Software de Nómina'));
        }

        $score = $form_state->getValue('score');
        if($score == "") {
             $form_state->setErrorByName('score', $this->t('Escriba un score.'));
        }
    
        if(!is_numeric($score)) {
             $form_state->setErrorByName('score', $this->t('El score debe ser numérico.'));
        }

        $tasa = $form_state->getValue('tasa');
        if($tasa == "") {
             $form_state->setErrorByName('tasa', $this->t('Escriba una tasa.'));
        }
    
        if(!is_numeric($tasa)) {
             $form_state->setErrorByName('tasa', $this->t('La tasa debe ser numérico.'));
        }

        $url_sw = $form_state->getValue('url_sw');
        if($url_sw == "") {
             $form_state->setErrorByName('url_sw', $this->t('Escriba la URL del Software de Nómina'));
        }

        $tipo_servicio = $form_state->getValue('tipo_servicio');
        if($tipo_servicio == "") {
             $form_state->setErrorByName('tipo_servicio', $this->t('Escriba el tipo de servicio'));
        }

        $requiere_notificacion = $form_state->getValue('requiere_notificacion');
        if($requiere_notificacion == "") {
             $form_state->setErrorByName('requiere_notificacion', $this->t('Seleccione'));
        }

        parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();
    $tipo_identificacion = $field['tipo_identificacion'];
    $identificacion = $field['identificacion'];
    $razon_social = $field['razon_social'];
    $periodicidad_pago = $field['periodicidad_pago'];
    $fecha_corte_1 = $field['fecha_corte_1'];
    $fecha_corte_2 = $field['fecha_corte_2'];
    $fecha_pago_1 = $field['fecha_pago_1'];
    $fecha_pago_2 = $field['fecha_pago_2'];
    $contacto_nombres = $field['contacto_nombres'];
    $contacto_apellidos = $field['contacto_apellidos'];
    $contacto_cargo = $field['contacto_cargo'];
    $telefono = $field['telefono'];
    $direccion = $field['direccion'];
    $email = $field['email'];
    $num_empleados = $field['num_empleados'];

    $convenio_tipoproducto = $field['convenio_tipoproducto'];
    $convenio_actividadecon = $field['convenio_actividadecon'];
    $estado_convenio = $field['estado_convenio'];
    $td = $field['td'];
    $regional = $field['regional'];
    $sw_nomina = $field['sw_nomina'];
    $score = $field['score'];
    $tasa = $field['tasa'];
    $url_sw = $field['url_sw'];
    $tipo_servicio = $field['tipo_servicio'];
    $requiere_notificacion = $field['requiere_notificacion'];
    $fecha_creacion = date("Y-m-d H:i:s");
    $fecha_actualizacion = date("Y-m-d H:i:s");



    if (isset($_GET['id'])) {
        $field  = array(
            'tipo_identificacion'   => $tipo_identificacion,
            'identificacion'   => $identificacion,
            'razon_social'   => $razon_social,
            'periodicidad_pago'   => $periodicidad_pago,
            'fecha_corte_1'   => $fecha_corte_1,
            'fecha_corte_2'   => $fecha_corte_2,
            'fecha_pago_1'   => $fecha_pago_1,
            'fecha_pago_2'   => $fecha_pago_2,
            'contacto_nombres'   => $contacto_nombres,
            'contacto_apellidos'   => $contacto_apellidos,
            'contacto_cargo'   => $contacto_cargo,
            'telefono'   => $telefono,
            'direccion'   => $direccion,
            'email'   => $email,
            'num_empleados'   => $num_empleados,
            'convenio_tipoproducto'   => $convenio_tipoproducto,
            'convenio_actividadecon'   => $convenio_actividadecon,
            'estado_convenio'   => $estado_convenio,
            'td'   => $td,
            'regional'   => $regional,
            'sw_nomina'   => $sw_nomina,
            'score'   => $score,
            'tasa'   => $tasa,
            'url_sw'   => $url_sw,
            'tipo_servicio'   => $tipo_servicio,
            'requiere_notificacion'   => $requiere_notificacion,
            'fecha_actualizacion'   => $fecha_actualizacion
        );

        $query = \Drupal::database();
        $update = $query->update('empresas')
            ->fields($field)
            ->condition('idempresa', $_GET['id'])
            ->execute();

        if($update > 0)
        {
            $empresalib = new EmpresasLibrary();
            if($estado_convenio=="aceptado")
            {
                if($empresalib->createUserEmpresa($_GET['id'], $field))
                {
                    drupal_set_message("Empresa actualizada correctamente");
                }else{
                    drupal_set_message("Empresa actualizada correctamente, pero no pudo crearse el usuario");
                }

                
            }elseif($estado_convenio=="rechazado"){
                //debemos buscar todas las solicitudes asociadas a esta empresa, rechazarlas también y enviarles la notificación correspondiente
                $empresalib->sendRejectionsAN($_GET['id']);
                drupal_set_message("Empresa actualizada correctamente");
            }else{
                drupal_set_message("Empresa actualizada correctamente");
            }
        }else{
            drupal_set_message("Empresa no ha podido ser actualizada, inténtelo nuevamente.");
        
        }
        $response = new RedirectResponse("/admin/empresas");
        $response->send();
        
    }else
        {
           $field  = array(
                'tipo_identificacion'   => $tipo_identificacion,
                'identificacion'   => $identificacion,
                'razon_social'   => $razon_social,
                'periodicidad_pago'   => $periodicidad_pago,
                'fecha_corte_1'   => $fecha_corte_1,
                'fecha_corte_2'   => $fecha_corte_2,
                'fecha_pago_1'   => $fecha_pago_1,
                'fecha_pago_2'   => $fecha_pago_2,
                'contacto_nombres'   => $contacto_nombres,
                'contacto_apellidos'   => $contacto_apellidos,
                'contacto_cargo'   => $contacto_cargo,
                'telefono'   => $telefono,
                'direccion'   => $direccion,
                'email'   => $email,
                'num_empleados'   => $num_empleados,
                'convenio_tipoproducto'   => $convenio_tipoproducto,
                'convenio_actividadecon'   => $convenio_actividadecon,
                'estado_convenio'   => $estado_convenio,
                'td'   => $td,
                'regional'   => $regional,
                'sw_nomina'   => $sw_nomina,
                'score'   => $score,
                'tasa'   => $tasa,
                'url_sw'   => $url_sw,
                'tipo_servicio'   => $tipo_servicio,
                'requiere_notificacion'   => $requiere_notificacion,
                'fecha_creacion'   => $fecha_creacion,
                'fecha_actualizacion'   => $fecha_actualizacion
            );
            $query = \Drupal::database();
            $insert = $query ->insert('empresas')
                    ->fields($field)
                    ->execute();
            if($insert)
            {
                $empresalib = new EmpresasLibrary();
                if($estado_convenio=="aceptado")
                {
                    if($empresalib->createUserEmpresa($insert, $field))
                    {
                        drupal_set_message("Empresa Creada Correctamente");
                    }else{
                        drupal_set_message("Empresa creada correctamente, pero no pudo crearse el usuario");
                    }
                }elseif($estado_convenio=="rechazado"){
                    //debemos buscar todas las solicitudes asociadas a esta empresa, rechazarlas también y enviarles la notificación correspondiente
                    $empresalib->sendRejectionsAN($insert);
                    drupal_set_message("Empresa actualizada correctamente");
                }else{
                    drupal_set_message("Empresa Creada Correctamente");
                }
            }else{
                drupal_set_message("Empresa no pudo ser creada, inténtelo nuevamente");
            }
            
            $response = new RedirectResponse("/admin/empresas");
            $response->send();
        }
    }
}
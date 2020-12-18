<?php
namespace Drupal\dispersion\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Database\Query;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\dispersion\Libs\DispersionLibrary as DispersionLibrary;

/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class ImportarAdelanto extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'importar_dispersion_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $form['csv_upload'] = array(
        '#type' => 'file',
        '#title' => t('Selecciona un archivo'),
        '#title_display' => 'invisible',
        '#size' => 22,
        '#upload_validators' => array('file_clean_name' => array()),
      );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Guardar',
    );
     $form['#cache'] = [
        'max-age' => 0
    ];
  

    return $form;

  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    parent::validateForm($form, $form_state);

    $validators = array(
        'file_validate_extensions' => array("csv"),
        'file_validate_size' => array(file_upload_max_size()),
    );

    // Save the file as a temporary file.
    $file = file_save_upload('csv_upload', $validators, FALSE, 0, FILE_EXISTS_REPLACE);
    if ($file === FALSE) {
        $form_state->setError($form["csv_upload"], "Failed to upload the file");
    }
    elseif ($file !== NULL) {

        $form_state->setValue("csv_upload", $file->toArray());
    } 
  }  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $file = $form_state->getValue('csv_upload');

    //var_dump($file);die();

    $destination = $file['uri'][0]['value'];

    $file = fopen($destination, "r");
    $id = array();

    while (!feof($file)) {

        //CSV FILE
        $adelanto = fgetcsv($file, 0, ';');
        $fecha_actualizacion = date("Y-m-d H:i:s");

        //CHECK IF EXIST IDENTIFICACION
        $select = db_select('adelantos_nomina', 'an')
                    ->condition('an.idadelanto', $adelanto[8]*1)
                    ->fields('an');
        $results = $select->execute()->fetchObject();
        $id[] = $adelanto[8]*1;

        $total = $results->valor_solicitado + $results->administracion + $results->seguros + $results->tecnologia + $results->iva;
        if($adelanto[11] == "aceptado"){
            $field  = array(
                'estado_desembolso'   => 'desembolsado',
                'fecha_hora_desembolso'   => $fecha_actualizacion,
                'saldo_pendiente'   => $total,
                'estado_general_solicitud'   => 'desembolsado'
            );
    
            $query = \Drupal::database();
            $query->update('adelantos_nomina')
                ->condition('idadelanto', $adelanto[8])
                ->fields($field)
                ->execute();

            //envio de la notificación de desembolso del dinero a usuario de la solicitud
            $query = db_select('adelantos_nomina','an');
            $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
            $query->fields('an');
            $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido","email"]);
            $query->condition('an.idadelanto', $adelanto[8]);
            $record = $query->execute()->fetchAssoc();

            $titleMsg = "FELICITACIONES";
            $mensaje = "<p>Estimado/a ".$record['nombre']." ".$record['primer_apellido']." ".$record['segundo_apellido'].". </p>";
            $mensaje .= "<p>Podrás contar con el dinero en tu cuenta de nómina, a más tardar, en el transcurso de las próximas 24 horas, recuerda que siempre que necesites ayuda puedes contar con nosotros ingresando a <a href='https://www.linxe.com' target='_blank'>https://www.linxe.com</a></p><br/>";
            $mensaje .= "<p>Nos interesa seguir mejorando, cuéntanos cómo podemos hacerlo ingresando acá: <a href='https://linxe.com/form/nps' target='_blank'>https://linxe.com/form/nps</a></p>";
            $mensaje .= "<p>Equipo Linxe.</p>";
            $emailto = $record['email'];
            $dispersionlib = new DispersionLibrary();
            $resultmail = $dispersionlib->sendMail_success($titleMsg,$mensaje,$emailto);
            

        }else{
            $field  = array(
                'estado_desembolso'   => 'desembolso_rechazado',
                'fecha_hora_desembolso'   => $fecha_actualizacion,
                'estado_general_solicitud'   => 'en_proceso_desembolso'
            );

            $query = \Drupal::database();
            $query->update('adelantos_nomina')
                ->condition('idadelanto', $adelanto[8])
                ->fields($field)
                ->execute();  
 
        }  
    }
    drupal_set_message('Importacion realizada correctamente!');
    $url = \Drupal\Core\Url::fromRoute('dispersion.table_display')
        ->setRouteParameters(array('id'=>$id));
    $form_state->setRedirectUrl($url); 



  }
}
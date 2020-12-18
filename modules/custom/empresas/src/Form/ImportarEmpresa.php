<?php
namespace Drupal\empresas\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Database\Query;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class ImportarEmpresa extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'importar_form';
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

    while (!feof($file)) {

        //CSV FILE
        $empresa = fgetcsv($file, 0, ';');
        $fecha_creacion = date("Y-m-d H:i:s");
        $fecha_actualizacion = date("Y-m-d H:i:s");

        //CHECK IF EXIST IDENTIFICACION
        $conn = Database::getConnection();
        $select = $conn->select('empresas', 'm')
        ->condition('identificacion', $empresa[1])
        ->fields('m')
        ->execute();
        $select->allowRowCount = TRUE;
        $count = $select->rowCount();
        if($count > 0){

            $field  = array(
                'tipo_identificacion'   => $empresa[0],
                'identificacion'   => $empresa[1],
                'razon_social'   => $empresa[2],
                'periodicidad_pago'   => $empresa[3],
                'fecha_corte_1'   => $empresa[4],
                'fecha_corte_2'   => $empresa[5],
                'fecha_pago_1'   => $empresa[6],
                'fecha_pago_2'   => $empresa[7],
                'contacto_nombres'   => $empresa[8],
                'contacto_apellidos'   => $empresa[9],
                'contacto_cargo'   => $empresa[10],
                'telefono'   => $empresa[11],
                'direccion'   => $empresa[12],
                'email'   => $empresa[13],
                'convenio_tipoproducto'   => $empresa[14],
                'convenio_actividadecon'   => $empresa[15],
                'estado_convenio'   => $empresa[16],
                'td'   => $empresa[17],
                'regional'   => $empresa[18],
                'sw_nomina'   => $empresa[19],
                'score'   => $empresa[20],
                'tasa'   => $empresa[21],
                'url_sw'   => $empresa[22],
                'tipo_servicio'   => $empresa[23],
                'requiere_notificacion'   => $empresa[24],
                'fecha_actualizacion'   => $fecha_actualizacion
            );
    
            $query = \Drupal::database();
            $query->update('empresas')
                ->condition('identificacion', $empresa[1])
                ->fields($field)
                ->execute();    
    
        }else{

            $field  = array(
                'tipo_identificacion'   => $empresa[0],
                'identificacion'   => $empresa[1],
                'razon_social'   => $empresa[2],
                'periodicidad_pago'   => $empresa[3],
                'fecha_corte_1'   => $empresa[4],
                'fecha_corte_2'   => $empresa[5],
                'fecha_pago_1'   => $empresa[6],
                'fecha_pago_2'   => $empresa[7],
                'contacto_nombres'   => $empresa[8],
                'contacto_apellidos'   => $empresa[9],
                'contacto_cargo'   => $empresa[10],
                'telefono'   => $empresa[11],
                'direccion'   => $empresa[12],
                'email'   => $empresa[13],
                'convenio_tipoproducto'   => $empresa[14],
                'convenio_actividadecon'   => $empresa[15],
                'estado_convenio'   => $empresa[16],
                'td'   => $empresa[17],
                'regional'   => $empresa[18],
                'sw_nomina'   => $empresa[19],
                'score'   => $empresa[20],
                'tasa'   => $empresa[21],
                'url_sw'   => $empresa[22],
                'tipo_servicio'   => $empresa[23],
                'requiere_notificacion'   => $empresa[24],
                'fecha_creacion'   => $fecha_creacion,
                'fecha_actualizacion'   => $fecha_actualizacion
            );

            $query = \Drupal::database();
            $query->insert('empresas')
                ->fields($field)
                ->execute();    
        }
    }
    drupal_set_message('Importacion realizada correctamente!');
    $response = new RedirectResponse("/admin/empresas");
    $response->send();


  }
}
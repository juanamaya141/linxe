<?php
namespace Drupal\linxeseguridadsocial\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides the form for filter Students.
 */
class RegistrarSeguridad extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'export_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $conn = Database::getConnection();
    $record = array();

    if (isset($_GET['iden'])) {
        $query = $conn->select('linxe_seguridadsocial', 'ss')
            ->condition('iden', $_GET['iden'])
            ->fields('ss');
        $record = $query->execute()->fetchAssoc();
    }

    $form['tipo_entidad'] = array(
        '#type' => 'select',
        '#title' => ('Tipo de entidad:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['tipo_entidad']) && $_GET['iden']) ? $record['tipo_entidad']:'',
        '#options' => array(
            'eps' => t('EPS'),
            'afp' => t('AFP'),
        ),
    );

    $form['nombre'] = array(
        '#type' => 'textfield',
        '#title' => t('Nombre:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['nombre']) && $_GET['iden']) ? $record['nombre']:'',
    );

    $form['alias'] = array(
        '#type' => 'textarea',
        '#title' => t('Alias:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['alias']) && $_GET['iden']) ? $record['alias']:'',
    );

    $form['estado'] = array(
        '#type' => 'select',
        '#title' => ('Estado:'),
        '#required' => TRUE,
        '#default_value' => (isset($record['estado']) && $_GET['iden']) ? $record['estado']:'',
        '#options' => array(
            '1' => t('Activo'),
            '0' => t('Inactivo'),
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

    $tipo_entidad = $form_state->getValue('tipo_entidad');
    if($tipo_entidad == "") {
         $form_state->setErrorByName('tipo_entidad', $this->t('Selecciona un tipo de entidad'));
    }

    $nombre = $form_state->getValue('nombre');
    if($nombre == "") {
         $form_state->setErrorByName('nombre', $this->t('Escribe el nombre'));
    }

    $alias = $form_state->getValue('alias');
    if($alias == "") {
         $form_state->setErrorByName('alias', $this->t('Escribe el alias'));
    }

    $estado = $form_state->getValue('estado');
    if($estado == "") {
         $form_state->setErrorByName('estado', $this->t('Selecciona un estado'));
    }

    parent::validateForm($form, $form_state);

  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {	
      
    $field = $form_state->getValues();
    $tipo_entidad = $field['tipo_entidad'];
    $nombre = $field['nombre'];
    $alias = $field['alias'];
    $estado = $field['estado'];

    if (isset($_GET['iden'])) {
        $field  = array(
            'tipo_entidad' => $tipo_entidad,
            'nombre' => $nombre,
            'alias' => $alias,
            'estado' => $estado
        );

        $query = \Drupal::database();
        $update = $query->update('linxe_seguridadsocial')
            ->fields($field)
            ->condition('iden', $_GET['iden'])
            ->execute();

        drupal_set_message("Seguridad Actualizada Correctamente");
        $response = new RedirectResponse("/admin/linxeseguridadsocial");
        $response->send();

    }else{

        $field  = array(
            'tipo_entidad' => $tipo_entidad,
            'nombre' => $nombre,
            'alias' => $alias,
            'estado' => $estado
        );

        $query = \Drupal::database();
        $insert = $query ->insert('linxe_seguridadsocial')
                ->fields($field)
                ->execute();

        drupal_set_message("Seguridad Creada Correctamente");
        $response = new RedirectResponse("/admin/linxeseguridadsocial");
        $response->send();

    }


  }

}

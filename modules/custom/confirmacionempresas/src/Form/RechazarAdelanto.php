<?php
namespace Drupal\confirmacionempresas\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\confirmacionempresas\Libs\ConfirmacionLibrary as ConfirmacionLibrary;

/**
 * Class DeleteForm.
 *
 * @package Drupal\mydata\Form
 */
class RechazarAdelanto extends ConfirmFormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'rechazar_adelanto_form';
    }

    public $idadelanto;

    public function getQuestion() { 
        return t('¿Estás seguro de rechazar el adelanto %id?', array('%id' => $this->idadelanto));
    }

    public function getCancelUrl() {
        return new Url('confirmacionempresas.index');
    }

    public function getDescription() {
        return t('No se podrán deshacer los cambios');
    }
    /**
     * {@inheritdoc}
     */
    public function getConfirmText() {
        return t('Rechazar');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelText() {
        return t('Cancelar');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

        $this->idadelanto = $id;
        return parent::buildForm($form, $form_state);
    }

    /**
        * {@inheritdoc}
        */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        
        
        $conn = \Drupal::database();
        
        $currentDate = date("Y-m-d H:i:s");
        $fields  = array(
            'autorizacion_desembolso_empresa'   => 0,
            'estado_general_solicitud'   => 'rechazado_empresa',
            'fecha_hora_desembolso'   => $currentDate,
        );
        $update = $conn->update('adelantos_nomina')
            ->fields($fields)
            ->condition('idadelanto', $this->idadelanto)
            ->execute();


        if($update > 0)
        {
            drupal_set_message("Adelanto rechazado satisfactoriamente");
            //envio de laa notificación de rechazo a usuario de la solicitud
            
            if (isset($this->idadelanto)) {
                $query = $conn->select('adelantos_nomina','an');
                $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
                $query->fields('an');
                $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido","email"]);
                $query->condition('an.idadelanto', $this->idadelanto);
                $record = $query->execute()->fetchAssoc();

                $titleMsg = "ACERCATE AL ÁREA DE RECURSOS HUMANOS";
                $mensaje = "<p>Estimado/a ".$record['nombre']." ".$record['primer_apellido']." ".$record['segundo_apellido'].": </p>";
                $mensaje .= "<p>Te invitamos para que te acerques al área de recursos humanos de tu empresa para validar con ellos el estado de tu <b>adelanto</b>.</p><br/>";
                
                $mensaje .= "<p>Gracias.</p>";
                $mensaje .= "<p>Equipo Linxe.</p>";
                $emailto = $record['email'];
                $confirmacionlib = new ConfirmacionLibrary();
                $resultmail = $confirmacionlib->sendMail_error($titleMsg,$mensaje,$emailto);
            }
            
        }else{
            drupal_set_message("Adelanto no pudo ser rechazado, inténtelo nuevamente.");
        
        }
        


        $response = new RedirectResponse("/admin/confirmacionempresas");
        $response->send();
    }
}
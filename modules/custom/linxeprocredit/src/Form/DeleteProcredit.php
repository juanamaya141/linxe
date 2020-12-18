<?php
namespace Drupal\linxeprocredit\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DeleteForm.
 *
 * @package Drupal\mydata\Form
 */
class DeleteProcredit extends ConfirmFormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'delete_form';
    }

    public function getQuestion() { 
        return t('¿Estás seguro de eliminar los registros de la tabla?');
    }

    public function getCancelUrl() {
        return new Url('linxeprocredit.ver_procredit');
    }

    public function getDescription() {
        return t('No se podrán deshacer los cambios');
    }
    /**
     * {@inheritdoc}
     */
    public function getConfirmText() {
        return t('Eliminar!');
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
    public function buildForm(array $form, FormStateInterface $form_state) {
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
        $query = \Drupal::database();
        $query->truncate('linxeprocredit')
                    ->execute();
        drupal_set_message("Registros eliminados correctamente");
        $response = new RedirectResponse("/admin/linxeprocredit");
        $response->send();
    }
}
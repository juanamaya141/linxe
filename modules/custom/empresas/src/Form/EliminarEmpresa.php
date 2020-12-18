<?php
namespace Drupal\empresas\Form;
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
class EliminarEmpresa extends ConfirmFormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'delete_form';
    }

    public $id;

    public function getQuestion() { 
        return t('¿Estás seguro de eliminar %id?', array('%id' => $this->id));
    }

    public function getCancelUrl() {
        return new Url('empresas.index');
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
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

        $this->idempresa = $id;
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
        $query->delete('empresas')
                    ->condition('idempresa',$this->idempresa)
                    ->execute();
        drupal_set_message("Empresa eliminada correctamente");
        $response = new RedirectResponse("/admin/empresas");
        $response->send();
    }
}
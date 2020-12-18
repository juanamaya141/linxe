<?php

namespace Drupal\contactoempmodule\Form;

use Drupal\Core\Form\ConfigFormBase; 
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ContactoConfigForm.
 *
 * @package Drupal\contactoempmodule\Form
 */
class ContactoEmpresasConfigForm extends ConfigFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contactoempresasconfig_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
 
    $config = $this->config('contactoempmodule.settings');

    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => t('CONTACTO BUSINESS - EMAIL TO'),
      '#required' => TRUE,
      '#default_value' => $config->get('contactoempmodule.email'),
      
    );

    $form['emailcc'] = array(
      '#type' => 'textfield',
      '#title' => t('CONTACTO BUSINESS - EMAIL CC TO'),
      '#required' => TRUE,
      '#default_value' => $config->get('contactoempmodule.emailcc'),
      
    );


    return $form;
  }

  /**
 
   * {@inheritdoc}
 
   */
 
  public function submitForm(array &$form, FormStateInterface $form_state) {
 
    $config = $this->config('contactoempmodule.settings');
    $config->set('contactoempmodule.email', $form_state->getValue('email'));
    $config->set('contactoempmodule.emailcc', $form_state->getValue('emailcc'));
    $config->save();
    return parent::submitForm($form, $form_state);
 
  }
 
  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'contactoempmodule.settings',
    ];
  }

}

<?php

namespace Drupal\contactopermodule\Form;

use Drupal\Core\Form\ConfigFormBase; 
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ContactoConfigForm.
 *
 * @package Drupal\contactopermodule\Form
 */
class ContactoPersonasConfigForm extends ConfigFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contactopersonasconfig_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
 
    $config = $this->config('contactopermodule.settings');

    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => t('CONTACTO BUSINESS - EMAIL TO'),
      '#required' => TRUE,
      '#default_value' => $config->get('contactopermodule.email'),
      
    );

    $form['emailcc'] = array(
      '#type' => 'textfield',
      '#title' => t('CONTACTO BUSINESS - EMAIL CC TO'),
      '#required' => TRUE,
      '#default_value' => $config->get('contactopermodule.emailcc'),
      
    );


    return $form;
  }

  /**
 
   * {@inheritdoc}
 
   */
 
  public function submitForm(array &$form, FormStateInterface $form_state) {
 
    $config = $this->config('contactopermodule.settings');
    $config->set('contactopermodule.email', $form_state->getValue('email'));
    $config->set('contactopermodule.emailcc', $form_state->getValue('emailcc'));
    $config->save();
    return parent::submitForm($form, $form_state);
 
  }
 
  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'contactopermodule.settings',
    ];
  }

}

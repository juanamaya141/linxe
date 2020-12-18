<?php

/**
 * @file
 * Theme settings 
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;
use Drupal\file\Entity\File;

/**
 * Implements hook_preprocess_HOOK() for HTML document templates.
 *
 * Adds body classes if certain regions have content.
 */
function linxe_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface &$form_state, $form_id = NULL) {
   if (isset($form_id)) {
    return;
  }

  
  $form['url_bannermain'] = [
    '#type' => 'managed_file',
    '#title' => t('URL Banner Principal'),
    '#description'   => t("Ingrese la url de la imagen del banner principal del home."),
    '#upload_validators' => [
      'file_validate_extensions' => ['gif png jpg jpeg'],
      'file_validate_size' => [25600000],
    ],
    '#theme' => 'image_widget',
    '#preview_image_style' => 'medium',
    '#upload_location' => 'public://',
    '#required' => FALSE,
    '#default_value' => theme_get_setting('url_bannermain'),
  ];

  $form['url_bannersecondary'] = [
    '#type' => 'managed_file',
    '#title' => t('URL Banner Secundario'),
    '#description'   => t("Ingrese la url de la imagen del banner secundario del home."),
    '#upload_validators' => [
      'file_validate_extensions' => ['gif png jpg jpeg'],
      'file_validate_size' => [25600000],
    ],
    '#theme' => 'image_widget',
    '#preview_image_style' => 'medium',
    '#upload_location' => 'public://',
    '#required' => FALSE,
    '#default_value' => theme_get_setting('url_bannersecondary'),
  ];

  $form['url_bannerthird'] = [
    '#type' => 'managed_file',
    '#title' => t('URL Banner Terciario'),
    '#description'   => t("Ingrese la url de la imagen del banner terciario del home."),
    '#upload_validators' => [
      'file_validate_extensions' => ['gif png jpg jpeg'],
      'file_validate_size' => [25600000],
    ],
    '#theme' => 'image_widget',
    '#preview_image_style' => 'medium',
    '#upload_location' => 'public://',
    '#required' => FALSE,
    '#default_value' => theme_get_setting('url_bannerthird'),
  ];

  // Add your submission handler to the form.
  $form['#submit'][] = 'linxe_form_system_theme_settings_submit';
}

function linxe_form_system_theme_settings_submit(&$form, \Drupal\Core\Form\FormStateInterface &$form_state, $form_id = NULL) {
  $url_bannermain_id = $form_state->getValue('url_bannermain');
  $file = File::load($url_bannermain_id[0]);
    // Set the status flag permanent of the file object.
  if (!empty($file)) {
      $file->setPermanent();
      // Save the file in the database.
      $file->save();
      $file_usage = \Drupal::service('file.usage'); 
      $file_usage->add($file, 'linxe', 'linxe', \Drupal::currentUser()->id());
  }


  $url_bannersecondary_id = $form_state->getValue('url_bannersecondary');
  $file2 = File::load($url_bannersecondary_id[0]);
    // Set the status flag permanent of the file object.
  if (!empty($file2)) {
      $file2->setPermanent();
      // Save the file in the database.
      $file2->save();
      $file2_usage = \Drupal::service('file.usage'); 
      $file2_usage->add($file2, 'linxe', 'linxe', \Drupal::currentUser()->id());
  }

  $url_bannerthird_id = $form_state->getValue('url_bannerthird');
  $file3 = File::load($url_bannerthird_id[0]);
    // Set the status flag permanent of the file object.
  if (!empty($file3)) {
      $file3->setPermanent();
      // Save the file in the database.
      $file3->save();
      $file3_usage = \Drupal::service('file.usage'); 
      $file3_usage->add($file3, 'linxe', 'linxe', \Drupal::currentUser()->id());
  }

}

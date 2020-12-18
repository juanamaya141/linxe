<?php

namespace Drupal\adelanto\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class adelantoController.
 *
 * @package Drupal\adelanto\Controller
 */
class adelantoController extends ControllerBase {

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('This page contain all inforamtion about my data ')
    ];
  }

}

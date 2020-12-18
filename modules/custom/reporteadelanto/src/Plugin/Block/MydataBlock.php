<?php

namespace Drupal\reporteadelanto\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'reporteadelantoBlock' block.
 *
 * @Block(
 *  id = "reporteadelanto_block",
 *  admin_label = @Translation("reporteadelanto block"),
 * )
 */
class reporteadelantoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    ////$build = [];
    //$build['reporteadelanto_block']['#markup'] = 'Implement reporteadelantoBlock.';

    $form = \Drupal::formBuilder()->getForm('Drupal\reporteadelanto\Form\reporteadelantoForm');

    return $form;
  }

}

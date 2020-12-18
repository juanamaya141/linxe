<?php

namespace Drupal\adelanto\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'adelantoBlock' block.
 *
 * @Block(
 *  id = "adelanto_block",
 *  admin_label = @Translation("adelanto block"),
 * )
 */
class adelantoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    ////$build = [];
    //$build['adelanto_block']['#markup'] = 'Implement adelantoBlock.';

    $form = \Drupal::formBuilder()->getForm('Drupal\adelanto\Form\adelantoForm');

    return $form;
  }

}

<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LinxerememberBlock' block.
 *
 * @Block(
 *  id = "linxeremember_block",
 *  admin_label = @Translation("Linxe Remember Form block"),
 * )
 */
class LinxerememberBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    

    $form = \Drupal::formBuilder()->getForm('Drupal\linxecredit\Form\RememberForm');

    return $form;
  }

}

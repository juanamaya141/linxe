<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LinxeloginBlock' block.
 *
 * @Block(
 *  id = "linxelogin_block",
 *  admin_label = @Translation("Linxe login block"),
 * )
 */
class LinxeloginBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    

    $form = \Drupal::formBuilder()->getForm('Drupal\linxecredit\Form\LoginForm');

    return $form;
  }

}

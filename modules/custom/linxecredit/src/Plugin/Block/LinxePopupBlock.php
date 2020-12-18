<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LinxePopupBlock' block.
 *
 * @Block(
 *  id = "linxepopup_block",
 *  admin_label = @Translation("Linxe popup block"),
 * )
 */
class LinxePopupBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    

    $form = \Drupal::formBuilder()->getForm('Drupal\linxecredit\Form\PopUpForm');

    return $form;
  }

}

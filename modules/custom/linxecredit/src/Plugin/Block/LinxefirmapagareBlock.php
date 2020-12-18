<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LinxefirmapagareBlock' block.
 *
 * @Block(
 *  id = "linxefirmapagare_block",
 *  admin_label = @Translation("Linxe firma pagare block"),
 * )
 */
class LinxefirmapagareBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    

    $form = \Drupal::formBuilder()->getForm('Drupal\linxecredit\Form\FirmaPagareForm');

    return $form;
  }

  public function getCacheMaxAge() {
        return 0;
  }

}

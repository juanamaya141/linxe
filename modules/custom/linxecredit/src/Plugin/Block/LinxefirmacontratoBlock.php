<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LinxefirmacontratoBlock' block.
 *
 * @Block(
 *  id = "linxefirmacontrato_block",
 *  admin_label = @Translation("Linxe firma contrato block"),
 * )
 */
class LinxefirmacontratoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    

    $form = \Drupal::formBuilder()->getForm('Drupal\linxecredit\Form\FirmaContratoForm');

    return $form;
  }

  public function getCacheMaxAge() {
        return 0;
  }

}

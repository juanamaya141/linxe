<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'PreferenciasBlock' block.
 *
 * @Block(
 *  id = "preferencias_block",
 *  admin_label = @Translation("Preferencias block"),
 * )
 */
class PreferenciasBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    

    $form = \Drupal::formBuilder()->getForm('Drupal\linxecredit\Form\PreferenciasForm');

    return $form;
  }

}

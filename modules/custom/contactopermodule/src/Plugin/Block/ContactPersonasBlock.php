<?php

namespace Drupal\contactopermodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ContactPersonasBlock' block.
 *
 * @Block(
 *  id = "contactopersonas_block",
 *  admin_label = @Translation("Contacto Personas block"),
 * )
 */
class ContactPersonasBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    ////$build = [];
    //$build['mydata_block']['#markup'] = 'Implement MydataBlock.';

    $form = \Drupal::formBuilder()->getForm('Drupal\contactopermodule\Form\ContactPersonasForm');

    return $form;
  }

}

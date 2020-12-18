<?php

namespace Drupal\contactoempmodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;
/**
 * Provides a 'ContactEmpresasBlock' block.
 *
 * @Block(
 *  id = "contact_empresas_block",
 *  admin_label = @Translation("Contacto Empresas block"),
 *  category = @Translation("Contacto Empresas block")
 * )
 */
class ContactEmpresasBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    ////$build = [];
    //$build['mydata_block']['#markup'] = 'Implement MydataBlock.';

    $form = \Drupal::formBuilder()->getForm('Drupal\contactoempmodule\Form\ContactEmpresasForm');
    //print_r($form);
    return $form;
    
  }

}

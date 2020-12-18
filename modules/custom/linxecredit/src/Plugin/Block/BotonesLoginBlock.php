<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * Provides a 'BotonesLoginBlock' block.
 *
 * @Block(
 *  id = "botoneslogin_block",
 *  admin_label = @Translation("Botones Login block"),
 * )
 */
class BotonesLoginBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $session = \Drupal::request()->getSession();
    $config = \Drupal::config('linxecredit.settings');
    $islogin = false;
    $isAdelanto = false;
    $infoObj = (object) array();
    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $islogin = true;
    }
    $tipoproducto = $session->get('tipoproducto'); 
    
    ////$build = [];
    //$build['mydata_block']['#markup'] = 'Implement MydataBlock.';

    return array(
    	'#theme' => 'botoneslogin_block',
      '#islogin' => $islogin,
      '#tipoproducto' => $tipoproducto,
      '#attached' => [
          'library' => [
            'linxecredit/linxecreditstyles', //include our custom library for this response
          ],
          'drupalSettings' => [
            'linxecredit' => [
              'linxecreditstyles' => [
                'islogin'=> $islogin,
              ],
            ],
          ], 
      ],
      '#cache' => [
        'max-age' => 0,
      ]
    );

  }

}

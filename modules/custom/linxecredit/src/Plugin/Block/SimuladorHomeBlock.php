<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'SimuladorHomeBlock' block.
 *
 * @Block(
 *  id = "simuladorhome_block",
 *  admin_label = @Translation("Simulador Home block"),
 * )
 */
class SimuladorHomeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $config = \Drupal::config('linxecredit.settings');
    
    $gl_cantidad_min = $config->get('linxecredit.cantidad_min');
    $gl_cantidad_max = $config->get('linxecredit.cantidad_max');
    $gl_meses_min = $config->get('linxecredit.meses_min');
    $gl_meses_max = $config->get('linxecredit.meses_max');
    $gl_tasa = $config->get('linxecredit.tasa');
    $gl_seguro = $config->get('linxecredit.seguro');
    $gl_cuota = 0;

    
    ////$build = [];
    //$build['mydata_block']['#markup'] = 'Implement MydataBlock.';

    return array(
    	'#theme' => 'simuladorhome_block',
    	'#name' => 'Simulador Home',
      '#gl_cantidad_min'=> $gl_cantidad_min,
      '#gl_cantidad_max'=> $gl_cantidad_max,
      '#gl_meses_min'=> $gl_meses_min,
      '#gl_meses_max'=> $gl_meses_max,
      '#gl_tasa'=> $gl_tasa,
      '#gl_seguro'=> $gl_seguro,
      '#gl_cuota'=> $gl_cuota,
      '#attached' => [
          'library' => [
            'linxecredit/linxecreditstyles', //include our custom library for this response
          ],
          'drupalSettings' => [
            'linxecredit' => [
              'linxecreditstyles' => [
                'gl_cantidad_min'=> $gl_cantidad_min,
                'gl_cantidad_max'=> $gl_cantidad_max,
                'gl_meses_min'=> $gl_meses_min,
                'gl_meses_max'=> $gl_meses_max,
                'gl_tasa'=> $gl_tasa,
                'gl_seguro'=> $gl_seguro,
                'gl_cuota'=> $gl_cuota,
              ],
            ], 
          ]
      ]
    );

  }

}

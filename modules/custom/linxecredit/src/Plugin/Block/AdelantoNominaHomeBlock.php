<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'AdelantoNominaHomeBlock' block.
 *
 * @Block(
 *  id = "adelantonominahome_block",
 *  admin_label = @Translation("Simulador Adelanto de Nómina Home block"),
 * )
 */
class AdelantoNominaHomeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $config = \Drupal::config('linxecredit.settings');
    
    $adelantonomina_rangos = $config->get('linxecredit.adelantonomina_rangos');
    $adelantonomina_montos_adelanto = $config->get('linxecredit.adelantonomina_montos_adelanto');
    $adelantonomina_montos_salario = $config->get('linxecredit.adelantonomina_montos_salario');

    $arrayOne = explode(",",$adelantonomina_rangos);
    $arrayDos = explode(",",$adelantonomina_montos_adelanto);
    $arrayTres = explode(",",$adelantonomina_montos_salario);

    //print_r($arrayDos);

    $valor_min_an = number_format($arrayOne[0],0,",",".");
    $canti = count($arrayOne);
    $canti2 = count($arrayDos);
    $canti3 = count($arrayTres);
    $valor_max_an = number_format($arrayTres[$canti3-1],0,",",".") ;
    $valor_med_default_an = $arrayTres[1];
    $valor_adelanto_default_an = number_format($arrayDos[1],0,",",".");

    ////$build = [];
    //$build['mydata_block']['#markup'] = 'Implement MydataBlock.';

    return array(
    	'#theme' => 'adelantohome_block',
      '#name' => 'Simulador Adelanto de Nómina Home',
      '#valor_min_an'=> $valor_min_an,
      '#valor_med_default_an'=> $valor_med_default_an,
      '#valor_max_an'=> $valor_max_an,
      '#valor_adelanto_default_an'=> $valor_adelanto_default_an,
      '#adelantonomina_rangos'=> $adelantonomina_rangos,
      '#adelantonomina_montos_adelanto'=> $adelantonomina_montos_adelanto,
      '#adelantonomina_montos_salario'=> $adelantonomina_montos_salario,
      '#attached' => [
          'library' => [
            'linxecredit/linxecreditstyles', //include our custom library for this response
          ],
          'drupalSettings' => [
            'linxecredit' => [
              'linxecreditstyles' => [
                '#valor_min_an'=> $valor_min_an,
                '#valor_med_default_an'=> $valor_med_default_an,
                '#valor_max_an'=> $valor_max_an,
                '#valor_adelanto_default_an'=> $valor_adelanto_default_an,  
                'adelantonomina_rangos'=> $adelantonomina_rangos,
                'adelantonomina_montos_adelanto'=> $adelantonomina_montos_adelanto,
                'adelantonomina_montos_salario'=> $adelantonomina_montos_salario
              ],
            ], 
          ]
      ]
    );

  }

}

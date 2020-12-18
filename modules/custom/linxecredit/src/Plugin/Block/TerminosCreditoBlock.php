<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * Provides a 'TerminosCreditoBlock' block.
 *
 * @Block(
 *  id = "terminoscredito_block",
 *  admin_label = @Translation("Terminos Credito block"),
 * )
 */
class TerminosCreditoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $session = \Drupal::request()->getSession();
    $config = \Drupal::config('linxecredit.settings');
    

    if(\Drupal::request()->query->get('monto_temp')!=="")
      $monto_temp = intval(\Drupal::request()->query->get('monto_temp'));
    else
      $monto_temp = 0;

    if(\Drupal::request()->query->get('plazo_temp')!=="")
      $plazo_temp = intval(\Drupal::request()->query->get('plazo_temp'));
    else
      $plazo_temp = 0;

    $arrayTipoDocs[1] = "Cédula de ciudadanía" ;
    $arrayTipoDocs[2] = "N.I.T." ;
    $arrayTipoDocs[3] = "Cédula de extranjería" ;
    $arrayTipoDocs[4] = "Tarjeta de identidad" ;

    
    $infoObj = (object) array();
    if($session->has('tipodocumento') && $session->has('numerodocumento'))
    {
      $infoObj->name = $session->get('nombres');
      $infoObj->id_tipodoc = $session->get('tipodocumento');
      $infoObj->tipodoc = $arrayTipoDocs[$infoObj->id_tipodoc] ;
      $infoObj->num_id = $session->get('numerodocumento');
      $infoObj->valormontoaprobado = number_format(intval($session->get('montoaprobado')) ,0,",",".");
      $infoObj->valormontoseleccionado = $monto_temp;
      $infoObj->plazo = $plazo_temp;
      
      if($session->has('tasa') && $session->get('tasa')!="")
        $infoObj->gl_tasa = $session->get('tasa');
      else
        $infoObj->gl_tasa = $config->get('linxecredit.tasa');

      $infoObj->tasamora = $config->get('linxecredit.tasamora');
      $infoObj->gl_seguro = $config->get('linxecredit.seguro');
      $valorseguro = ($infoObj->valormontoseleccionado * $infoObj->gl_seguro )/100;
      $infoObj->valorcuota = (($infoObj->valormontoseleccionado / 100) * $infoObj->gl_tasa) / (1 - pow(((100 + $infoObj->gl_tasa) / 100),$infoObj->plazo * -1 ) ) + $valorseguro;
      $infoObj->empresa = $session->get('nombreempresa');;
      $infoObj->gl_tasa_anual = $infoObj->gl_tasa*12;
    }
    ////$build = [];
    //$build['mydata_block']['#markup'] = 'Implement MydataBlock.';

    return array(
    	'#theme' => 'terminoscredito_block',
    	'#name' => 'Términos Crédito',
      '#infoObj' => $infoObj,
      '#attached' => [
          'library' => [
            'linxecredit/linxecreditstyles', //include our custom library for this response
          ]
      ],
      '#cache' => [
        'max-age' => 0,
      ]
    );

  }

}

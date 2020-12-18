<?php

namespace Drupal\linxecredit\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Drupal\linxecredit\Libs\AdelantoLibrary as AdelantoLibrary;

/**
 * Provides a 'TerminosAdelantoNBlock' block.
 *
 * @Block(
 *  id = "terminosadelanton_block",
 *  admin_label = @Translation("Terminos Adelanto Nomina block"),
 * )
 */
class TerminosAdelantoNBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $session = \Drupal::request()->getSession();
    $config = \Drupal::config('linxecredit.settings');
    $adelantolib = new AdelantoLibrary();
    $idregistro = $session->get('idregistro');
    $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
    $dataUser = $returnArray->userData;
    
    $idadelanto = $dataUser->idadelanto;
    $idempresa = $dataUser->idempresa;

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

      $infoObj->cargo_administracion_adelanto = $config->get('linxecredit.cargo_administracion_adelanto');
      $infoObj->cargo_tecnologia_adelanto = $config->get('linxecredit.cargo_tecnologia_adelanto');
      $infoObj->seguro_adelanto = $config->get('linxecredit.seguro_adelanto');
      $infoObj->iva_adelanto = $config->get('linxecredit.iva_adelanto');

      
      $infoObj->valor_administracion = $infoObj->cargo_administracion_adelanto;
      $infoObj->valor_tecnologia = $infoObj->cargo_tecnologia_adelanto;
      $infoObj->valor_seguro = ($infoObj->valormontoseleccionado * $infoObj->seguro_adelanto)/100;
      $infoObj->valor_iva = ($infoObj->valor_tecnologia * $infoObj->iva_adelanto)/100;
      $infoObj->valor_pagar = $infoObj->valormontoseleccionado + $infoObj->valor_administracion + $infoObj->valor_tecnologia + $infoObj->valor_seguro + $infoObj->valor_iva;
      $infoObj->empresa = $session->get('nombreempresa');

      $empresa = $adelantolib->getEmpresaByID($idempresa);

      $infoObj->periodicidad_pago = $empresa[0]["periodicidad_pago"];
      if($empresa[0]["periodicidad_pago"] == "quincenal"){
        $infoObj->num_cuotas = 2;
        $infoObj->monto_cuota = round($infoObj->valor_pagar/2);
      }else{
        $infoObj->num_cuotas = 1;
        $infoObj->monto_cuota = $infoObj->valor_pagar;
      }
      
    }
    ////$build = [];
    //$build['mydata_block']['#markup'] = 'Implement MydataBlock.';

    return array(
    	'#theme' => 'terminosadelanto_block',
    	'#name' => 'Términos Adelanto de Nómina',
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

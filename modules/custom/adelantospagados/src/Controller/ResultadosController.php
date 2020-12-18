<?php

namespace Drupal\adelantospagados\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;


class ResultadosController extends ControllerBase {


  public function resultados() {
    
    $uid = \Drupal::currentUser()->id();  //obtenemos el uid del usuario logueado


    //====load filter controller
    $form['form'] = $this->formBuilder()->getForm('Drupal\adelantospagados\Form\Reporte');

    $header_table = array(
        'idadelanto' => t('Num. Adelanto'),
        'tipoDoc' => t('Tipo Documento'),
        'doc' => t('Documento'),
        'nombre' => t('Nombre'),
        'valorAdelanto' => t('Valor Adelanto'),
        'saldoCorte' => t('Saldo al Corte'),
        'numCuota' => t('Num. Cuota'),
        'saldoMora' => t('Saldo en Mora'),
        'interesMora' => t('Intereses Mora'),
        'valorFacturado' => t('Valor Facturado'),
        'valorPagar' => t('Valor a Pagar'),
    );

    $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => obtener_resultados($uid),
        '#empty' => $this->t('No se encontraron registros'),
    ];

    $form['pager'] = [
        '#type' => 'pager'
    ];

    return $form;

  }

}

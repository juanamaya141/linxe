<?php
namespace Drupal\linxeprocredit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the form for filter Students.
 */
class ExportProcredit extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'exportprecredit_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'EXPORTAR',
        //'#value' => t('Submit'),
    ];
    
    $form['#cache'] = [
        'max-age' => 0
    ];
   
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {	
      
    header("Content-type: text/csv");
    header('Content-Disposition: attachment; filename=linxe-procreditos-'.date("Y-m-d H:i:s").'.csv');
    header("Pragma: no-cache");
    header("Expires: 0");

    flush();

    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel.php';
    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';

    $objPHPExcel = new \PHPExcel();

    $objPHPExcel->getActiveSheet()->setTitle('Linxe Procréditos');


    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Tipo de Registro')
            ->setCellValue('B1', 'Tipo de Novedad')
            ->setCellValue('C1', 'Refinanciacion / Reestructuracion')
            ->setCellValue('D1', 'Fecha de Corte Informacion')
            ->setCellValue('E1', 'Seccional del Afiliado')
            ->setCellValue('F1', 'Consecutivo del Afiliado')
            ->setCellValue('G1', 'Codigo Sucursal Viejo')
            ->setCellValue('H1', 'Tipo de Documento Afiliado')
            ->setCellValue('I1', 'Numero de Documento Afiliado')
            ->setCellValue('J1', 'Codigo Sucursal Nuevo')
            ->setCellValue('K1', 'Tipo de Garante')
            ->setCellValue('L1', 'Tipo de Documento Cliente')
            ->setCellValue('M1', 'Numero de Documento Cliente')
            ->setCellValue('N1', 'Primer Nombre Cliente / Razon Social')
            ->setCellValue('O1', 'Segundo Nombre Cliente')
            ->setCellValue('P1', 'Primer Apellido Cliente')
            ->setCellValue('Q1', 'Segundo Apellido Cliente')
            ->setCellValue('R1', 'Nombre Comercial')
            ->setCellValue('S1', 'Pais')
            ->setCellValue('T1', 'Departamento')
            ->setCellValue('U1', 'Ciudad')
            ->setCellValue('V1', 'Tipo de Direccion')
            ->setCellValue('W1', 'Direccion')
            ->setCellValue('X1', 'Tipo Telefono')
            ->setCellValue('Y1', 'Telefono')
            ->setCellValue('Z1', 'Extension')
            ->setCellValue('AA1', 'Tipo Ubicacion Electronica')
            ->setCellValue('AB1', 'Ubicacion Electronica')
            ->setCellValue('AC1', 'Cupo Total Aprobado / Cupo Credito')
            ->setCellValue('AD1', 'Cupo Utilizado')
            ->setCellValue('AE1', 'Tipo Obligacion')
            ->setCellValue('AF1', 'Tipo de Contrato')
            ->setCellValue('AG1', 'Numero Obligacion')
            ->setCellValue('AH1', 'Fecha Obligacion')
            ->setCellValue('AI1', 'Periodicidad de Pago')
            ->setCellValue('AJ1', 'Termino del Contrato')
            ->setCellValue('AK1', 'Meses Celebrados')
            ->setCellValue('AL1', 'Meses de Clausula de Permanencia')
            ->setCellValue('AM1', 'Valor Obligacion')
            ->setCellValue('AN1', 'Cargo Fijo')
            ->setCellValue('AO1', 'Saldos a Fecha de Corte')
            ->setCellValue('AP1', 'Saldo en Mora a Fecha de Corte')
            ->setCellValue('AQ1', 'Cuotas Pactadas')
            ->setCellValue('AR1', 'Cuotas Pagadas')
            ->setCellValue('AS1', 'Cuotas en Mora')
            ->setCellValue('AT1', 'Motivo de Pago')
            ->setCellValue('AU1', 'Situacion o Estado del Titular')
            ->setCellValue('AV1', 'Tipo de Documento Soporte de la Obligacion Referenciado')
            ->setCellValue('AW1', 'Numero Obligacion Referenciada');

        

    $styleArrayHead = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
    ));
    $worksheet = $objPHPExcel->getActiveSheet();
    $worksheet->getStyle('A1:AW1')->applyFromArray($styleArrayHead);

    if (isset($_GET['proveniente']) && isset($_GET['desde']) && isset($_GET['hasta']) ) {
        $proveniente = $_GET['proveniente'];
        $desde = $_GET['desde'];
        $hasta = $_GET['hasta'];


        $query = db_select('linxeprocredit','lp');
        $query->fields('lp');

        if($proveniente !== ""){
            $query->condition('lp.proveniente_de',$proveniente);
        }

        if($desde !== "" && $hasta !== "" ){
            $query->condition('lp.fecha_carga',[$desde,$hasta],'BETWEEN');
        }

        $results = $query->execute()->fetchAll();


    }else{
        
        $query = db_select('linxeprocredit','lp');
        $query->fields('lp');

        $results = $query->execute()->fetchAll();

    }
    
    $rowNumber = 2;
    foreach ($results as $row) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,$row->tipo_registro);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,$row->tipo_novedad);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowNumber,$row->refinacion_reestructuracion);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,$row->fecha_corte_informacion);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,$row->seccional_afiliado);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,$row->consecutivo_afiliado);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowNumber,$row->codigo_sucursal_viejo);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowNumber,$row->tipo_documento_afiliado);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowNumber,$row->numero_documento_afiliado);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowNumber,$row->codigo_sucursal_nuevo);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowNumber,$row->tipo_garante);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowNumber,$row->tipo_documento_cliente);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowNumber,$row->numero_documento_cliente);
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$rowNumber,$row->primernombre_cliente_razonsocial);
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$rowNumber,$row->segundonombre_cliente);
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$rowNumber,$row->primerapellido_cliente);
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowNumber,$row->segundoapellido_cliente);
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$rowNumber,$row->nombre_comercial);
        $objPHPExcel->getActiveSheet()->setCellValue('S'.$rowNumber,$row->pais);
        $objPHPExcel->getActiveSheet()->setCellValue('T'.$rowNumber,$row->departamento);
        $objPHPExcel->getActiveSheet()->setCellValue('U'.$rowNumber,$row->ciudad);
        $objPHPExcel->getActiveSheet()->setCellValue('V'.$rowNumber,$row->tipo_direccion);
        $objPHPExcel->getActiveSheet()->setCellValue('W'.$rowNumber,$row->direccion);
        $objPHPExcel->getActiveSheet()->setCellValue('X'.$rowNumber,$row->tipo_telefono);
        $objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowNumber,$row->telefono);
        $objPHPExcel->getActiveSheet()->setCellValue('Z'.$rowNumber,$row->extension);
        $objPHPExcel->getActiveSheet()->setCellValue('AA'.$rowNumber,$row->tipo_ubicacion_electronica);
        $objPHPExcel->getActiveSheet()->setCellValue('AB'.$rowNumber,$row->ubicacion_electronica);
        $objPHPExcel->getActiveSheet()->setCellValue('AC'.$rowNumber,$row->cupototal_aprobado_credito);
        $objPHPExcel->getActiveSheet()->setCellValue('AD'.$rowNumber,$row->cupo_utilizado);
        $objPHPExcel->getActiveSheet()->setCellValue('AE'.$rowNumber,$row->tipo_obligacion);
        $objPHPExcel->getActiveSheet()->setCellValue('AF'.$rowNumber,$row->tipo_contrato);
        $objPHPExcel->getActiveSheet()->setCellValue('AG'.$rowNumber,$row->numero_obligacion);
        $objPHPExcel->getActiveSheet()->setCellValue('AH'.$rowNumber,$row->fecha_obligacion);
        $objPHPExcel->getActiveSheet()->setCellValue('AI'.$rowNumber,$row->periodicidad_pago);
        $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$rowNumber,$row->termino_contrato);
        $objPHPExcel->getActiveSheet()->setCellValue('AK'.$rowNumber,$row->meses_celebrados);
        $objPHPExcel->getActiveSheet()->setCellValue('AL'.$rowNumber,$row->meses_clausula_permanencia);
        $objPHPExcel->getActiveSheet()->setCellValue('AM'.$rowNumber,$row->valor_obligacion);
        $objPHPExcel->getActiveSheet()->setCellValue('AN'.$rowNumber,$row->cargo_fijo);
        $objPHPExcel->getActiveSheet()->setCellValue('AO'.$rowNumber,$row->saldos_fecha_corte);
        $objPHPExcel->getActiveSheet()->setCellValue('AP'.$rowNumber,$row->saldos_mora_fecha_corte);
        $objPHPExcel->getActiveSheet()->setCellValue('AQ'.$rowNumber,$row->cuotas_pactadas);
        $objPHPExcel->getActiveSheet()->setCellValue('AR'.$rowNumber,$row->cuotas_pagadas);
        $objPHPExcel->getActiveSheet()->setCellValue('AS'.$rowNumber,$row->cuotas_mora);
        $objPHPExcel->getActiveSheet()->setCellValue('AT'.$rowNumber,$row->motivo_pago);
        $objPHPExcel->getActiveSheet()->setCellValue('AU'.$rowNumber,$row->situacion_estado_titular);
        $objPHPExcel->getActiveSheet()->setCellValue('AV'.$rowNumber,$row->tipo_documento_soporte_obligacion_referenciado);
        $objPHPExcel->getActiveSheet()->setCellValue('AW'.$rowNumber,$row->numero_obligacion_referenciada);

        $rowNumber++;
    }
    foreach(range('A','AW') as $columnID) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            ->setAutoSize(true);
    }

    // Save as an Excel BIFF (xls) file
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        
    ob_end_clean();
    $objWriter->save('php://output');
    exit();

  }

}

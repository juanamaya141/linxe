<?php
namespace Drupal\adelantospagados\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the form for filter Students.
 */
class Reporte extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'reporte_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $conn = Database::getConnection();
    $record = array();


    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'Exportar',
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
      
    $uid = \Drupal::currentUser()->id();  //obtenemos el uid del usuario logueado

    header("Content-Type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename=pagos_adelantos_nomina-'.date("Y-m-d H:i:s").'.xls');
    header("Pragma: no-cache");
    header("Expires: 0");

    flush();

    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel.php';
    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';

    $objPHPExcel = new \PHPExcel();

    $objPHPExcel->getActiveSheet()->setTitle('Adelanto de Nómina');

    /*$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nombres')
            ->setCellValue('B1', 'Primer Apellido')
            ->setCellValue('C1', 'Segundo Apellido')
            ->setCellValue('D1', 'Tipo Documento')
            ->setCellValue('E1', 'Documento')
            ->setCellValue('F1', 'Fecha solicitud Adelanto nómina')
            ->setCellValue('G1', 'Nit Empresa')
            ->setCellValue('H1', 'Nombre Empresa')
            ->setCellValue('I1', 'Máximo Monto Aprobado')
            ->setCellValue('J1', 'Valor Solicitado')
            ->setCellValue('K1', 'Administración')
            ->setCellValue('L1', 'Seguros')
            ->setCellValue('M1', 'Tecnologia')
            ->setCellValue('N1', 'IVA')
            ->setCellValue('O1', 'Tipo de cuenta')
            ->setCellValue('P1', 'Número de cuenta')
            ->setCellValue('Q1', 'Banco')
            ->setCellValue('R1', 'Estado Solicitud');*/

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Num. Adelanto')
            ->setCellValue('B1', 'Tipo Documento')
            ->setCellValue('C1', 'Documento')
            ->setCellValue('D1', 'Nombre')
            ->setCellValue('E1', 'Valor Adelanto')
            ->setCellValue('F1', 'Saldo al Corte')
            ->setCellValue('G1', 'Num. Cuota')
            ->setCellValue('H1', 'Saldo en Mora')
            ->setCellValue('I1', 'Intereses Mora')
            ->setCellValue('J1', 'Valor Facturado')
            ->setCellValue('K1', 'Valor a Pagar');

        

    $styleArrayHead = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
    ));
    $worksheet = $objPHPExcel->getActiveSheet();
    $worksheet->getStyle('A1:Q1')->applyFromArray($styleArrayHead);
    //QUERY
    $db = \Drupal::database();
    $query = $db->select('liquidaciones_an','liq');
    $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
    $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
    $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
    $query->fields('liq');
    $query->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
    $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
    $query->condition('an.estado_general_solicitud',"en_proceso_liquidacion");
    $query->condition('liq.estado',"pagado");
    $query->condition('emp.iduser',$uid);
    $query->condition('liq.fecha_hora_pago',[date("Y-m-d", strtotime("-1 day")),date("Y-m-d", strtotime("+1 day"))],'BETWEEN');


    $results = $query->execute()->fetchAll();
    $rowNumber = 2;
    foreach ($results as $row) {
        $valor_adelanto = $row->valor_solicitado + $row->administracion + $row->seguros + $row->tecnologia + $row->iva;
        if($row->intereses_mora==null)
        {
            $row->intereses_mora = 0;
        }

        $saldo_mora = 0;
        if($row->intereses_mora > 0)
        {
            $saldo_mora = $row->valor - $row->intereses_mora;
        }

        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,$row->idadelanto);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,$row->tipodocumento);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowNumber,$row->documento);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,$row->nombre.' '.$row->primer_apellido);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,'$ '. number_format($valor_adelanto,0,",","."));
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,'$ '.number_format($row->saldo_pendiente,0,",","."));
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowNumber,$row->num_cuota);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowNumber,'$ '.number_format($saldo_mora,0,",","."));
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowNumber,'$ '.number_format($row->intereses_mora,0,",","."));
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowNumber,'$ '.number_format($row->valor,0,",","."));
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowNumber,'$ '.number_format($row->valor_pagado,0,",","."));

        $rowNumber++;
    }
    foreach(range('A','K') as $columnID) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->freezePane('A2');

    // Save as an Excel BIFF (xls) file
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        
    ob_end_clean();
    $objWriter->save('php://output');

    exit();

  }

}

<?php
namespace Drupal\reporteadelanto\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the form for filter Students.
 */
class Descarga extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'descarga_filter_form';
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
      
    header("Content-Type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename=reportes_nomina-'.date("Y-m-d H:i:s").'.xls');
    header("Pragma: no-cache");
    header("Expires: 0");

    flush();

    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel.php';
    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';

    $objPHPExcel = new \PHPExcel();

    $objPHPExcel->getActiveSheet()->setTitle('Reportes de Nómina');

    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    $uid = \Drupal::currentUser()->id();  //obtenemos el uid del usuario logueado

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Número de adelanto')
            ->setCellValue('B1', 'Tipo de documento')
            ->setCellValue('C1', 'Documento')
            ->setCellValue('D1', 'Nombre Completo')
            ->setCellValue('E1', 'Valor Adelanto de nómina')
            ->setCellValue('F1', 'Saldo al corte')
            ->setCellValue('G1', 'Número de cuota')
            ->setCellValue('H1', 'Saldo en mora')
            ->setCellValue('I1', 'Interés mora')
            ->setCellValue('J1', 'Valor Facturado')
            ->setCellValue('K1', 'Fecha hora facturación')
            ->setCellValue('L1', 'Valor Pagado')
            ->setCellValue('M1', 'Fecha hora pago')
            ->setCellValue('N1', 'Valor Conciliado')
            ->setCellValue('O1', 'Fecha hora conciliación')
            ->setCellValue('P1', 'Estado');

        

    $styleArrayHead = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
    ));
    $worksheet = $objPHPExcel->getActiveSheet();
    $worksheet->getStyle('A1:P1')->applyFromArray($styleArrayHead);
    if (isset($_GET['empresas']) && isset($_GET['estado']) && isset($_GET['desde']) && isset($_GET['hasta']) ) {
        $estado = $_GET['estado'];
        $empresas = $_GET['empresas'];
        $desde = $_GET['desde'];
        $hasta = $_GET['hasta'];
        $desde = $desde.' 00:00:00';
        $hasta = $hasta.' 23:59:59';

        if($estado == "facturado"){
            $query = db_select('liquidaciones_an','liq')
                    ->extend('\Drupal\Core\Database\Query\PagerSelectExtender')
                    ->limit(15);
            $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
            $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
            $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
            $query->fields('liq');
            $query->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
            $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"])
                    ->fields('emp')
                    ->condition('liq.estado',$estado)
                    ->condition('emp.razon_social',$empresas)
                    ->condition('liq.fecha_hora_facturacion',[$desde,$hasta],'BETWEEN');
            if($roles[1] == "empresa_linxe"){
                $query->condition('emp.iduser',$uid);
            }
                
            
            $results = $query->execute()->fetchAll();
            


        }elseif($estado == "pagado"){
            $query = db_select('liquidaciones_an','liq')
                    ->extend('\Drupal\Core\Database\Query\PagerSelectExtender')
                    ->limit(15);
            $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
            $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
            $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
            $query->fields('liq');
            $query->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
            $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"])
                    ->fields('emp')
                    ->condition('liq.estado',$estado)
                    ->condition('emp.razon_social',$empresas)
                    ->condition('liq.fecha_hora_pago',[$desde,$hasta],'BETWEEN');
            if($roles[1] == "empresa_linxe"){
                $query->condition('emp.iduser',$uid);
            }
                    
            $results = $query->execute()->fetchAll();
            

        }else{
            $query = db_select('liquidaciones_an','liq')
                    ->extend('\Drupal\Core\Database\Query\PagerSelectExtender')
                    ->limit(15);
            $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
            $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
            $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
            $query->fields('liq');
            $query->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
            $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"])
                    ->fields('emp')
                    ->condition('liq.estado',$estado)
                    ->condition('emp.razon_social',$empresas)
                    ->condition('liq.fecha_hora_conciliacion',[$desde,$hasta],'BETWEEN');
            if($roles[1] == "empresa_linxe"){
                $query->condition('emp.iduser',$uid);
            }
        
            $results = $query->execute()->fetchAll();
            
        }
    }else{
        $query = db_select('liquidaciones_an','liq');
        $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
        $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
        $query->fields('liq');
        $query->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
        $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"])
        ->fields('emp');
        if($roles[1] == "empresa_linxe"){
            $query->condition('emp.iduser',$uid);
        }

        $results = $query->execute()->fetchAll();

    }
    
    $rowNumber = 2;
    foreach ($results as $row) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,$row->idadelanto);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,$row->tipodocumento);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowNumber,$row->documento);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,$row->nombre.' '.$row->primer_apellido.' '.$row->segundo_apellido);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,$row->valor_solicitado+$row->administracion+$row->seguros+$row->tecnologia+$row->iva);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,$row->saldo_pendiente);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowNumber,$row->num_cuota);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowNumber,$row->valor-$row->intereses_mora);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowNumber,$row->intereses_mora);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowNumber,$row->valor);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowNumber,$row->fecha_hora_facturacion);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowNumber,$row->valor_pagado);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowNumber,$row->fecha_hora_pago);
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$rowNumber,$row->valor_conciliado);
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$rowNumber,$row->fecha_hora_conciliacion);
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$rowNumber,$row->estado);

        $rowNumber++;
    }
    foreach(range('A','P') as $columnID) {
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

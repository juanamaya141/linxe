<?php
namespace Drupal\exportarfacturados\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the form for filter Students.
 */
class Exportar extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'exportar_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $conn = Database::getConnection();
    $record = array();


    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'Guardar registros facturados',
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
      
    /*header("Content-Type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename=reporte-facturados.xls');
    header("Pragma: no-cache");
    header("Expires: 0");

    flush();*/

    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel.php';
    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';

    $objPHPExcel = new \PHPExcel();

    $objPHPExcel->getActiveSheet()->setTitle('Reporte de Facturados');

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
            ->setCellValue('K1', 'Fecha hora facturación');

    $styleArrayHead = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
    ));
    $worksheet = $objPHPExcel->getActiveSheet();
    $worksheet->getStyle('A1:K1')->applyFromArray($styleArrayHead);
    //QUERY
    $query = db_select('liquidaciones_an','liq');
    $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
    $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
    $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
    $query->fields('liq');
    $query->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
    $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"])
        ->fields('emp')
        ->condition('liq.estado','facturado');
    if($roles[1] == "empresa_linxe"){
        $query->condition('emp.iduser',$uid);
    }

    $results = $query->execute()->fetchAll();
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

        $rowNumber++;
    }
    foreach(range('A','K') as $columnID) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->freezePane('A2');

    // Save as an Excel BIFF (xls) file
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $namef = 'reporte-'.substr(str_shuffle($permitted_chars), 0, 30).'.xls';
    $path = 'sites/default/files/registros-facturados/';
    $filename = $path . $namef;

    $objWriter->save($filename);


    return $filename;

  }

}

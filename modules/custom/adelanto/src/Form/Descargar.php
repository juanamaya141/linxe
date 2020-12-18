<?php
namespace Drupal\adelanto\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the form for filter Students.
 */
class Descargar extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'descargar_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['filtro'])) {
        $query = $conn->select('adelantos_nomina', 'an')
            ->condition('estado_general_solicitud', $_GET['filtro'])
            ->fields('an');
        $record = $query->execute()->fetchAssoc();
    }
    if (isset($_GET['filtro'])) {
        if($_GET['filtro'] == "aprobacion_empresa"){

            $form['submit'] = [
                '#type' => 'submit',
                '#value' => 'Descargar para Desembolso',
                //'#value' => t('Submit'),
            ];
    
        }else{

            $form['submit'] = [
                '#type' => 'submit',
                '#value' => 'Volver a Descargar para Desembolso',
                //'#value' => t('Submit'),
            ];
        }
    }

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
    header('Content-Disposition: attachment; filename=adelantos_nomina-'.date("Y-m-d H:i:s").'.xls');
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
            ->setCellValue('A1', 'Tipo de Identificación')
            ->setCellValue('B1', 'Número de Identificación')
            ->setCellValue('C1', 'Nombres')
            ->setCellValue('D1', 'Apellidos')
            ->setCellValue('E1', 'Código del Banco')
            ->setCellValue('F1', 'Tipo de producto o servicio')
            ->setCellValue('G1', 'Número del producto o servicio')
            ->setCellValue('H1', 'Valor del pago o de la recarga')
            ->setCellValue('I1', 'Referencia')
            ->setCellValue('J1', 'Correo electrónico')
            ->setCellValue('K1', 'Descripción o detalle');

        

    $styleArrayHead = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
    ));
    $worksheet = $objPHPExcel->getActiveSheet();
    $worksheet->getStyle('A1:Q1')->applyFromArray($styleArrayHead);
    //QUERY
    $query = db_select('registrados_an', 'r');
    $query->join('adelantos_nomina', 'an', 'an.idregistro = r.idregistro');
    $query->join('empresas', 'e', 'r.idempresa = e.idempresa');
    $query
        ->fields('r', ['nombre','primer_apellido','segundo_apellido','tipodocumento','documento','email'])
        ->fields('e', ['identificacion','razon_social'])
        ->fields('an', ['idadelanto','fecha_solicitud','monto_maximo_aprobado','valor_solicitado','administracion','seguros','tecnologia','iva','tipo_cuenta','numero_cuenta','banco','estado_general_solicitud'])
        ->condition('an.estado_general_solicitud',$_GET['filtro']);
    $results = $query->execute()->fetchAll();
    $rowNumber = 2;
    foreach ($results as $row) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,getDocumento($row->tipodocumento));
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,$row->documento);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowNumber,$row->nombre);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,$row->primer_apellido);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,getCodigo($row->banco));
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,getTipo($row->tipo_cuenta));
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowNumber,' '.$row->numero_cuenta);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowNumber,$row->valor_solicitado);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowNumber,$row->idadelanto);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowNumber,$row->email);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowNumber,'Adelanto de Nómina');

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

    if($_GET['filtro'] == "aprobacion_empresa"){
        updateNomina($_GET['filtro']);
        $response = new RedirectResponse("/admin/adelanto-nomina/empresas?filtro=aprobacion_empresa");
        $response->send();
    }

    exit();

  }

}

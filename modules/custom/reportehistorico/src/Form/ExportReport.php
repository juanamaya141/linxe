<?php
namespace Drupal\reportehistorico\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the form for filter Students.
 */
class ExportReport extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'export_filter_form';
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
    header('Content-Disposition: attachment; filename=reportes_historico_nomina-'.date("Y-m-d H:i:s").'.xls');
    header("Pragma: no-cache");
    header("Expires: 0");

    flush();

    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel.php';
    require_once 'sites/default/libraries/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';

    $objPHPExcel = new \PHPExcel();

    $objPHPExcel->getActiveSheet()->setTitle('Reportes de Histórico');


    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id registro')
            ->setCellValue('B1', 'Id user')
            ->setCellValue('C1', 'Tipo documento')
            ->setCellValue('D1', 'Documento')
            ->setCellValue('E1', 'Fecha expedicion')
            ->setCellValue('F1', 'Nombres')
            ->setCellValue('G1', 'Celular')
            ->setCellValue('H1', 'Email')
            ->setCellValue('I1', 'Actividad Económica')
            ->setCellValue('J1', 'Destino Prestamo')
            ->setCellValue('K1', 'EPS')
            ->setCellValue('L1', 'AFP')
            ->setCellValue('M1', 'Registra pagos 6 meses')
            ->setCellValue('N1', 'Convenio empresa')
            ->setCellValue('O1', 'NIT')
            ->setCellValue('P1', 'idempresa')
            ->setCellValue('Q1', 'Rango salarial')
            ->setCellValue('R1', 'Acepto terminos')
            ->setCellValue('S1', 'Fecha de registro')
            ->setCellValue('T1', 'Estado creación DATASCORING')
            ->setCellValue('U1', 'Respuesta creación')
            ->setCellValue('V1', 'Fecha creación en DATASCORING')
            ->setCellValue('W1', 'Id adelanto')
            ->setCellValue('X1', 'Id empresa adelanto')
            ->setCellValue('Y1', 'Id registro adelanto')
            ->setCellValue('Z1', 'Fecha solicitud')
            ->setCellValue('AA1', 'Estado solicitud')
            ->setCellValue('AB1', 'Envio notificacion')
            ->setCellValue('AC1', 'Monto maximo aprobado')
            ->setCellValue('AD1', 'Valor solicitado')
            ->setCellValue('AE1', 'Administración')
            ->setCellValue('AF1', 'Seguros')
            ->setCellValue('AG1', 'Tecnologia')
            ->setCellValue('AH1', 'IVA')
            ->setCellValue('AI1', 'Acepto terminos')
            ->setCellValue('AJ1', 'Fecha hora acepta terminos')
            ->setCellValue('AK1', 'Ip address')
            ->setCellValue('AL1', 'Mareigua consulta id')
            ->setCellValue('AM1', 'Mareigua respuesta id')
            ->setCellValue('AN1', 'Mareigua EPS')
            ->setCellValue('AO1', 'Mareigua AFP')
            ->setCellValue('AP1', 'Mareigua tipo de identificacion')
            ->setCellValue('AQ1', 'Mareigua empresa')
            ->setCellValue('AR1', 'Mareigua razon social')
            ->setCellValue('AS1', 'Mareigua nivel riesgo')
            ->setCellValue('AT1', 'Mareigua promedio ingresos')
            ->setCellValue('AU1', 'Mareigua mediana ingresos')
            ->setCellValue('AV1', 'Mareigua maximo')
            ->setCellValue('AW1', 'Mareigua minimo')
            ->setCellValue('AX1', 'Mareigua pendiente')
            ->setCellValue('AY1', 'Mareigua tendencia')
            ->setCellValue('AZ1', 'Mareigua meses continuidad')
            ->setCellValue('BA1', 'Mareigua cantidad aportantes')
            ->setCellValue('BB1', 'Mareigua estado pagaré')
            ->setCellValue('BC1', 'Respuesta deceval girador')
            ->setCellValue('BD1', 'Cuenta Girador')
            ->setCellValue('BE1', 'Respuesta deceval creacion pagaré')
            ->setCellValue('BF1', 'Numero pagar entidad')
            ->setCellValue('BG1', 'Id documento pagaré')
            ->setCellValue('BH1', 'Fecha hora creación pagaré')
            ->setCellValue('BI1', 'Respuesta deceval firmar pagaré')
            ->setCellValue('BJ1', 'Fecha hora firma pagaré')
            ->setCellValue('BK1', 'OTP')
            ->setCellValue('BL1', 'Fecha generacion OTP')
            ->setCellValue('BM1', 'Contacto empresa nombre')
            ->setCellValue('BN1', 'Contacto empresa apellido')
            ->setCellValue('BO1', 'Contacto empresa telefono')
            ->setCellValue('BP1', 'Contacto empresa email')
            ->setCellValue('BQ1', 'Fecha hora datos empresa')
            ->setCellValue('BR1', 'Tipo cuenta')
            ->setCellValue('BS1', 'Numero cuenta')
            ->setCellValue('BT1', 'Banco')
            ->setCellValue('BU1', 'Autorizacion desembolso empresa')
            ->setCellValue('BV1', 'Estado desembolso')
            ->setCellValue('BW1', 'Saldo pendiente')
            ->setCellValue('BX1', 'Estado general solicitud');

        

    $styleArrayHead = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
    ));
    $worksheet = $objPHPExcel->getActiveSheet();
    $worksheet->getStyle('A1:BX1')->applyFromArray($styleArrayHead);
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    $uid = \Drupal::currentUser()->id();  //obtenemos el uid del usuario logueado

    if (isset($_GET['empresas']) && isset($_GET['estado']) && isset($_GET['desde']) && isset($_GET['hasta']) ) {
        $estado = $_GET['estado'];
        $empresas = $_GET['empresas'];
        $desde = $_GET['desde'];
        $hasta = $_GET['hasta'];
        $desde = $desde.' 00:00:00';
        $hasta = $hasta.' 23:59:59';

        $query = db_select('adelantos_nomina','an');
        $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $query->fields('an');
        $query->fields('reg');

        if($estado !== ""){
            $query->condition('an.estado_general_solicitud',$estado);
        }
        if($empresas !== ""){
            $query->condition('reg.idempresa',$empresas);
        }

        if($estado == "seleccion_monto"){
            $query->condition('an.fecha_hora_acepta_terminos',[$desde,$hasta],'BETWEEN');
        }elseif($estado == "validacion_desembolso"){
            $query->condition('an.fecha_hora_datosempresa',[$desde,$hasta],'BETWEEN');
        }elseif($estado == "en_proceso_desembolso" || $estado == "desembolsado"){
            $query->condition('an.fecha_hora_desembolso',[$desde,$hasta],'BETWEEN');
        }elseif($estado == "en_proceso_liquidacion" || $estado == "liquidado"){
            $query->condition('an.fecha_hora_liquidacion',[$desde,$hasta],'BETWEEN');
        }else{
            $query->condition('an.fecha_solicitud',[$desde,$hasta],'BETWEEN');
        }

        $results = $query->execute()->fetchAll();


    }else{
        $query = db_select('adelantos_nomina','an');
        $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $query->fields('an');
        $query->fields('reg');

        if($roles[1] == "empresa_linxe"){
            //CHECK EMPRESA
            $select = db_select('empresas', 'e')
                        ->condition('e.iduser', $uid)
                        ->fields('e');
            $resultado = $select->execute()->fetchObject();

            $query->condition('reg.idempresa',$resultado->idempresa);
        }


        $results = $query->execute()->fetchAll();

    }
    
    $rowNumber = 2;
    foreach ($results as $row) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,$row->idregistro);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,$row->iduser);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowNumber,$row->tipodocumento);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,$row->documento);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,$row->fecha_expedicion);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,$row->nombre.' '. $row->primer_apellido);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowNumber,$row->celular);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowNumber,$row->email);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowNumber,$row->actividad_economica);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowNumber,$row->destino_prestamo);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowNumber,$row->eps);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowNumber,$row->afp);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowNumber,$row->registra_pagos_6_meses);
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$rowNumber,$row->convenio_empresa);
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$rowNumber,$row->nit_empresa);
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$rowNumber,$row->idempresa);
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowNumber,$row->rango_salarial);
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$rowNumber,$row->acepto_terminos);
        $objPHPExcel->getActiveSheet()->setCellValue('S'.$rowNumber,$row->registerdate);
        $objPHPExcel->getActiveSheet()->setCellValue('T'.$rowNumber,$row->estado_creacion_datascoring);
        $objPHPExcel->getActiveSheet()->setCellValue('U'.$rowNumber,$row->respuesta_creacion_datascoring);
        $objPHPExcel->getActiveSheet()->setCellValue('V'.$rowNumber,$row->fecha_creacion_datascoring);
        $objPHPExcel->getActiveSheet()->setCellValue('W'.$rowNumber,$row->idadelanto);
        $objPHPExcel->getActiveSheet()->setCellValue('X'.$rowNumber,$row->idempresa);
        $objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowNumber,$row->idregistro);
        $objPHPExcel->getActiveSheet()->setCellValue('Z'.$rowNumber,$row->fecha_solicitud);
        $objPHPExcel->getActiveSheet()->setCellValue('AA'.$rowNumber,$row->estado_solicitud);
        $objPHPExcel->getActiveSheet()->setCellValue('AB'.$rowNumber,$row->envio_notificacion_aprobacion);
        $objPHPExcel->getActiveSheet()->setCellValue('AC'.$rowNumber,$row->monto_maximo_aprobado);
        $objPHPExcel->getActiveSheet()->setCellValue('AD'.$rowNumber,$row->valor_solicitado);
        $objPHPExcel->getActiveSheet()->setCellValue('AE'.$rowNumber,$row->administracion);
        $objPHPExcel->getActiveSheet()->setCellValue('AF'.$rowNumber,$row->seguros);
        $objPHPExcel->getActiveSheet()->setCellValue('AG'.$rowNumber,$row->tecnologia);
        $objPHPExcel->getActiveSheet()->setCellValue('AH'.$rowNumber,$row->iva);
        $objPHPExcel->getActiveSheet()->setCellValue('AI'.$rowNumber,$row->acepto_terminos);
        $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$rowNumber,$row->fecha_hora_acepta_terminos);
        $objPHPExcel->getActiveSheet()->setCellValue('AK'.$rowNumber,$row->ip_address);
        $objPHPExcel->getActiveSheet()->setCellValue('AL'.$rowNumber,$row->mareigua_consulta_id);
        $objPHPExcel->getActiveSheet()->setCellValue('AM'.$rowNumber,$row->mareigua_respuesta_id);
        $objPHPExcel->getActiveSheet()->setCellValue('AN'.$rowNumber,$row->mareigua_eps);
        $objPHPExcel->getActiveSheet()->setCellValue('AO'.$rowNumber,$row->mareigua_afp);
        $objPHPExcel->getActiveSheet()->setCellValue('AP'.$rowNumber,$row->mareigua_empresa_tipo_identificacion);
        $objPHPExcel->getActiveSheet()->setCellValue('AQ'.$rowNumber,$row->mareigua_empresa_identificacion);
        $objPHPExcel->getActiveSheet()->setCellValue('AR'.$rowNumber,$row->mareigua_razon_social);
        $objPHPExcel->getActiveSheet()->setCellValue('AS'.$rowNumber,$row->mareigua_nivel_riesgo);
        $objPHPExcel->getActiveSheet()->setCellValue('AT'.$rowNumber,$row->mareigua_promedio_ingresos);
        $objPHPExcel->getActiveSheet()->setCellValue('AU'.$rowNumber,$row->mareigua_mediana_ingresos);
        $objPHPExcel->getActiveSheet()->setCellValue('AV'.$rowNumber,$row->mareigua_maximo);
        $objPHPExcel->getActiveSheet()->setCellValue('AW'.$rowNumber,$row->mareigua_minimo);
        $objPHPExcel->getActiveSheet()->setCellValue('AX'.$rowNumber,$row->mareigua_pendiente);
        $objPHPExcel->getActiveSheet()->setCellValue('AY'.$rowNumber,$row->mareigua_tendencia);
        $objPHPExcel->getActiveSheet()->setCellValue('AZ'.$rowNumber,$row->mareigua_meses_continuidad);
        $objPHPExcel->getActiveSheet()->setCellValue('BA'.$rowNumber,$row->mareigua_cantidad_aportantes);
        $objPHPExcel->getActiveSheet()->setCellValue('BB'.$rowNumber,$row->estado_pagare);
        $objPHPExcel->getActiveSheet()->setCellValue('BC'.$rowNumber,$row->respuesta_deceval_girador);
        $objPHPExcel->getActiveSheet()->setCellValue('BD'.$rowNumber,$row->cuentaGirador);
        $objPHPExcel->getActiveSheet()->setCellValue('BE'.$rowNumber,$row->respuesta_deceval_creacion_pagare);
        $objPHPExcel->getActiveSheet()->setCellValue('BF'.$rowNumber,$row->numpagarentidad);
        $objPHPExcel->getActiveSheet()->setCellValue('BG'.$rowNumber,$row->iddocumentopagare);
        $objPHPExcel->getActiveSheet()->setCellValue('BH'.$rowNumber,$row->fecha_hora_creacion_pagare);
        $objPHPExcel->getActiveSheet()->setCellValue('BI'.$rowNumber,$row->respuesta_deceval_firmar_pagare);
        $objPHPExcel->getActiveSheet()->setCellValue('BJ'.$rowNumber,$row->fecha_hora_firma_pagare);
        $objPHPExcel->getActiveSheet()->setCellValue('BK'.$rowNumber,$row->otp);
        $objPHPExcel->getActiveSheet()->setCellValue('BL'.$rowNumber,$row->fecha_hora_generacion_otp);
        $objPHPExcel->getActiveSheet()->setCellValue('BM'.$rowNumber,$row->contacto_empresa_nombre);
        $objPHPExcel->getActiveSheet()->setCellValue('BN'.$rowNumber,$row->contacto_empresa_apellido);
        $objPHPExcel->getActiveSheet()->setCellValue('BO'.$rowNumber,$row->contacto_empresa_telefono);
        $objPHPExcel->getActiveSheet()->setCellValue('BP'.$rowNumber,$row->contacto_empresa_email);
        $objPHPExcel->getActiveSheet()->setCellValue('BQ'.$rowNumber,$row->fecha_hora_datosempresa);
        $objPHPExcel->getActiveSheet()->setCellValue('BR'.$rowNumber,$row->tipo_cuenta);
        $objPHPExcel->getActiveSheet()->setCellValue('BS'.$rowNumber,$row->numero_cuenta);
        $objPHPExcel->getActiveSheet()->setCellValue('BT'.$rowNumber,$row->banco);
        $objPHPExcel->getActiveSheet()->setCellValue('BU'.$rowNumber,$row->autorizacion_desembolso_empresa);
        $objPHPExcel->getActiveSheet()->setCellValue('BV'.$rowNumber,$row->estado_desembolso);
        $objPHPExcel->getActiveSheet()->setCellValue('BW'.$rowNumber,$row->saldo_pendiente);
        $objPHPExcel->getActiveSheet()->setCellValue('BX'.$rowNumber,$row->estado_general_solicitud);

        $rowNumber++;
    }
    foreach(range('A','BX') as $columnID) {
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

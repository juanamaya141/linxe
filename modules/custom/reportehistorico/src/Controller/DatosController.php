<?php

namespace Drupal\reportehistorico\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\reportehistorico\Controller
 */
class DatosController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'reportehistorico_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function data() {
    /**return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: display with parameter(s): $name'),
    ];*/
    $empresas = \Drupal::request()->query->get('empresas');
    $estado = \Drupal::request()->query->get('estado');
    $desde = \Drupal::request()->query->get('desde');
    $hasta = \Drupal::request()->query->get('hasta');

    
    //====load filter controller
    $form['form'] = $this->formBuilder()->getForm('Drupal\reportehistorico\Form\FiltrosReporte');

    //====load filter controller
    $form['form_2'] = $this->formBuilder()->getForm('Drupal\reportehistorico\Form\ExportReport');
    
    //create table header
    $header_table = array(
        'idregistro'=>  t('Id registro'),
        'iduser' => t('Id user'),
        'tipodocumento' => t('Tipo documento'),
        'documento' => t('Documento'),
        'fecha_expedicion' => t('Fecha Expedicion'),
        'nombres' => t('Nombres'),
        'celular' => t('Celular'),
        'email' => t('Email'),
        'actividad_economica' => t('Actividad Económica'),
        'destino_prestamo' => t('Destino Prestamo'),
        'eps' => t('EPS'),
        'afp' => t('AFP'),
        'registra_pagos_6_meses' => t('Registra pagos 6 meses'),
        'convenio_empresa' => t('Convenio empresa'),
        'nit_empresa' => t('NIT'),
        'idempresa' => t('idempresa'),
        'rango_salarial'=>  t('rango_salarial'),
        'acepto_terminos' => t('Acepto terminos'),
        'registerdate' => t('Fecha de registro'),
        'estado_creacion_datascoring' => t('Estado creación DATASCORING'),
        'respuesta_creacion_datascoring' => t('Respuesta creación'),
        'fecha_creacion_datascoring' => t('Fecha creación en DATASCORING'),
        'idadelanto' => t('Id adelanto'),
        'idempresa_adelanto' => t('Id empresa adelanto'),
        'idregistro_adelanto' => t('Id registro adelanto'),
        'fecha_solicitud' => t('Fecha solicitud'),
        'estado_solicitud' => t('Estado solicitud'),
        'envio_notificacion' => t('Envio notificacion'),
        'monto_maximo_aprobado' => t('Monto maximo aprobado'),
        'valor_solicitado' => t('Valor solicitado'),
        'administracion' => t('Administración'),
        'seguros' => t('Seguros'),
        'tecnologia'=>  t('Tecnologia'),
        'iva' => t('IVA'),
        'acepto_terminos' => t('Acepto terminos'),
        'fecha_hora_acepta_terminos' => t('Fecha hora acepta terminos'),
        'ip_address' => t('Ip address'),
        'mareigua_consulta_id' => t('Mareigua consulta id'),
        'mareigua_respuesta_id' => t('Mareigua respuesta id'),
        'mareigua_eps' => t('Mareigua EPS'),
        'mareigua_afp' => t('Mareigua AFP'),
        'mareigua_empresa_tipo_identificacion' => t('Mareigua tipo de identificacion'),
        'mareigua_empresa_identificacion' => t('Mareigua empresa'),
        'mareigua_razon_social' => t('Mareigua razon social'),
        'mareigua_nivel_riesgo' => t('Mareigua nivel riesgo'),
        'mareigua_promedio_ingresos' => t('Mareigua promedio ingresos'),
        'mareigua_mediana_ingresos' => t('Mareigua mediana ingresos'),
        'mareigua_maximo' => t('Mareigua maximo'),
        'mareigua_minimo'=>  t('Mareigua minimo'),
        'mareigua_pendiente' => t('Mareigua pendiente'),
        'mareigua_tendencia' => t('Mareigua tendencia'),
        'mareigua_meses_continuidad' => t('Mareigua meses continuidad'),
        'mareigua_cantidad_aportantes' => t('Mareigua cantidad aportantes'),
        'estado_pagare' => t('Mareigua estado pagaré'),
        'respuesta_deceval_girador' => t('Respuesta deceval girador'),
        'cuentaGirador' => t('Cuenta Girador'),
        'respuesta_deceval_creacion_pagare' => t('Respuesta deceval creacion pagaré'),
        'numpagarentidad' => t('Numero pagar entidad'),
        'iddocumentopagare' => t('Id documento pagaré'),
        'fecha_hora_creacion_pagare' => t('Fecha hora creación pagaré'),
        'respuesta_deceval_firmar_pagare' => t('Respuesta deceval firmar pagaré'),
        'fecha_hora_firma_pagare' => t('Fecha hora firma pagaré'),
        'otp' => t('OTP'),
        'fecha_hora_generacion_otp' => t('Fecha generacion OTP'),
        'contacto_empresa_nombre'=>  t('Contacto empresa nombre'),
        'contacto_empresa_apellido' => t('Contacto empresa apellido'),
        'contacto_empresa_telefono' => t('Contacto empresa telefono'),
        'contacto_empresa_email' => t('Contacto empresa email'),
        'fecha_hora_datosempresa' => t('Fecha hora datos empresa'),
        'tipo_cuenta' => t('Tipo cuenta'),
        'numero_cuenta' => t('Numero cuenta'),
        'banco' => t('Banco'),
        'autorizacion_desembolso_empresa' => t('Autorizacion desembolso empresa'),
        'estado_desembolso' => t('Estado desembolso'),
        'saldo_pendiente' => t('Saldo pendiente'),
        'estado_general_solicitud' => t('Estado general solicitud')
    );

    if($empresas == "" && $estado == "" && $desde == "" && $hasta == "" ){
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_reportehistorico("All","","","",""),
        '#empty' => $this->t('No se encontraron registros'),
        ];
    }else{
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => get_reportehistorico("",$empresas,$estado,$desde,$hasta),
        '#empty' => $this->t('No se encontraron registros'),
        ];
    }

    $form['pager'] = [
        '#type' => 'pager'
    ];

    return $form;

  }

}

<?php
/**
 * Implements hook_views_data().
 */
function linxecredit_views_data() {
  $data = [];
  // Base data.
  $data['preferenciasUsuarios']['table']['group'] = t('Formulario Preferencias');
  $data['preferenciasUsuarios']['table']['base'] = [
    'title' => t('Formulario Preferencias'),
    'help' => t('Registros del formulario de preferencias')
  ];
  // Fields.
  $data['preferenciasUsuarios']['id'] = [
    'title' => t('id'),
    'help' => t('Id Form Preferencias'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['preferenciasUsuarios']['tipodocumento'] = [
    'title' => t('Tipo Documento'),
    'help' => t('Tipo de Documento'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['preferenciasUsuarios']['documento'] = [
    'title' => t('Documento'),
    'help' => t('Documento'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['preferenciasUsuarios']['nombre'] = [
    'title' => t('Nombre'),
    'help' => t('Nombre'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['preferenciasUsuarios']['celular'] = [
    'title' => t('Celular'),
    'help' => t('Celular'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  
  $data['preferenciasUsuarios']['email'] = [
    'title' => t('Email'),
    'help' => t('Email'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['preferenciasUsuarios']['categoriasseleccionadas'] = [
    'title' => t('Categorías Seleccionadas'),
    'help' => t('Categorías Seleccionadas'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['preferenciasUsuarios']['mensaje'] = [
    'title' => t('Mensaje'),
    'help' => t('Mensaje'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['preferenciasUsuarios']['contactdate'] = [
    'title' => t('Fecha contacto'),
    'help' => t('Fecha y hora del contacto'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];



  // segunda tabla de interesados registro

  // Base data.
  $data['interesadosRegistro']['table']['group'] = t('Interesados Registro');
  $data['interesadosRegistro']['table']['base'] = [
    'title' => t('Interesados Registro'),
    'help' => t('Personas del formulario de registro que no tienen empresa asociada')
  ];
  // Fields.
  $data['interesadosRegistro']['id'] = [
    'title' => t('id'),
    'help' => t('Id Form Registro'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['tipodocumento'] = [
    'title' => t('Tipo Documento'),
    'help' => t('Tipo de Documento'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['documento'] = [
    'title' => t('Documento'),
    'help' => t('Documento'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['fecha_expedicion'] = [
    'title' => t('Fecha expedicion'),
    'help' => t('Fecha expedicion'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['nombre'] = [
    'title' => t('Nombre'),
    'help' => t('Nombre'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['primer_apellido'] = [
    'title' => t('Primer apellido'),
    'help' => t('Primer apellido'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['segundo_apellido'] = [
    'title' => t('Segundo apellido'),
    'help' => t('Segundo apellido'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['celular'] = [
    'title' => t('Celular'),
    'help' => t('Celular'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  
  $data['interesadosRegistro']['email'] = [
    'title' => t('Email'),
    'help' => t('Email'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['interesadosRegistro']['destino_prestamo'] = [
    'title' => t('Destino prestamo'),
    'help' => t('Destino prestamo'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['empresa'] = [
    'title' => t('Empresa'),
    'help' => t('Empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['interesadosRegistro']['fecha_ingreso'] = [
    'title' => t('Fecha ingreso a la empresa'),
    'help' => t('Fecha ingreso a la empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['interesadosRegistro']['tipo_contrato'] = [
    'title' => t('Tipo de contrato'),
    'help' => t('Tipo de contrato'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['interesadosRegistro']['descuento_nomina'] = [
    'title' => t('Tiene descuento nomina'),
    'help' => t('Tiene descuento nomina'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['interesadosRegistro']['cargo'] = [
    'title' => t('Cargo'),
    'help' => t('Cargo'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['interesadosRegistro']['acepto_terminos'] = [
    'title' => t('Aceptó Términos'),
    'help' => t('Aceptó Términos'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['interesadosRegistro']['registerdate'] = [
    'title' => t('Fecha registro'),
    'help' => t('Fecha y hora del registro'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];


  // tercera tabla formulario webinar
  // Base data.
  $data['registroWebinar']['table']['group'] = t('Formulario Webinar');
  $data['registroWebinar']['table']['base'] = [
    'title' => t('Formulario Webinar'),
    'help' => t('Inscritos al formulario del popup webinar')
  ];
  // Fields.
  $data['registroWebinar']['id'] = [
    'title' => t('id'),
    'help' => t('Id Registro'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  
  $data['registroWebinar']['name'] = [
    'title' => t('Nombre'),
    'help' => t('Nombre'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  
  $data['registroWebinar']['email'] = [
    'title' => t('Email'),
    'help' => t('Email'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['registroWebinar']['webinar_id'] = [
    'title' => t('Email'),
    'help' => t('Email'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  $data['registroWebinar']['webinar_schedule'] = [
    'title' => t('Email'),
    'help' => t('Email'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['registroWebinar']['registerdate'] = [
    'title' => t('Fecha registro'),
    'help' => t('Fecha y hora del registro'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    // tabla empresas registradas
  // Base data.
  $data['empresas']['table']['group'] = t('Empresas registradas en Linxe');
  $data['empresas']['table']['base'] = [
    'title' => t('Empresas registradas en Linxe'),
    'help' => t('Empresas registradas en Linxe')
  ];
  // Fields.
  $data['empresas']['idempresa'] = [
    'title' => t('idempresa'),
    'help' => t('Id de la empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  
  $data['empresas']['tipo_identificacion'] = [
    'title' => t('Tipo de Identificación'),
    'help' => t('Tipo de Identificación'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['identificacion'] = [
    'title' => t('Identificación'),
    'help' => t('Identificación'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
    $data['empresas']['razon_social'] = [
    'title' => t('Razón Social'),
    'help' => t('Razón Social'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
    $data['empresas']['periodicidad_pago'] = [
    'title' => t('Periodicidad de Pago'),
    'help' => t('Periodicidad de Pago'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
    $data['empresas']['fecha_corte_1'] = [
    'title' => t('Fecha de Corte 1'),
    'help' => t('Fecha de Corte 1'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['fecha_corte_2'] = [
    'title' => t('Fecha de Corte 2'),
    'help' => t('Fecha de Corte 2'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

      $data['empresas']['fecha_pago_1'] = [
    'title' => t('Fecha de Pago 1'),
    'help' => t('Fecha de Pago 1'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['fecha_pago_2'] = [
    'title' => t('Fecha de Pago 2'),
    'help' => t('Fecha de Pago 2'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['contacto_nombres'] = [
    'title' => t('Nombres'),
    'help' => t('Nombres del contacto de la empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['contacto_apellidos'] = [
    'title' => t('Apellidos'),
    'help' => t('Apellidos del contacto de la empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['contacto_cargo'] = [
    'title' => t('Cargo'),
    'help' => t('Cargo del contacto de la empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['telefono'] = [
    'title' => t('Telefono'),
    'help' => t('Telefono del contacto de la empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['direccion'] = [
    'title' => t('Dirección'),
    'help' => t('Dirección del contacto de la empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
    $data['empresas']['email'] = [
    'title' => t('Email'),
    'help' => t('Email del contacto de la empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['empresas']['num_empleados'] = [
    'title' => t('Número de empleados'),
    'help' => t('Número de empleados'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['convenio_tipoproducto'] = [
    'title' => t('Convenio del tipo de producto'),
    'help' => t('Convenio del tipo de producto'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['convenio_actividadecon'] = [
    'title' => t('Convenio de la actividad economica'),
    'help' => t('Convenio de la actividad economica'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['estado_convenio'] = [
    'title' => t('Estado del convenio'),
    'help' => t('Estado del convenio'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['td'] = [
    'title' => t('TD Empresa'),
    'help' => t('TD Empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
      
    $data['empresas']['regional'] = [
    'title' => t('Regional'),
    'help' => t('Regional'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['sw_nomina'] = [
    'title' => t('Software Nomina'),
    'help' => t('Software Nomina'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['score'] = [
    'title' => t('Score empresa'),
    'help' => t('Score empresa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['tasa'] = [
    'title' => t('Tasa'),
    'help' => t('Tasa'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['url_sw'] = [
    'title' => t('URL del Software'),
    'help' => t('URL del Software'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['tipo_servicio'] = [
    'title' => t('Tipo del servicio'),
    'help' => t('Tipo del servicio'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['requiere_notificacion'] = [
    'title' => t('Empresa requiere de notificacion'),
    'help' => t('Empresa requiere de notificacion'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['fecha_creacion'] = [
    'title' => t('Fecha de creación'),
    'help' => t('Fecha de creación'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

    $data['empresas']['fecha_actualizacion'] = [
    'title' => t('Fecha de actualización'),
    'help' => t('Fecha de actualización'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];
  
  return $data;
}
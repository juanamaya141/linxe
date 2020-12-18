<?php
/**
 * Implements hook_views_data().
 */
function contactoempmodule_views_data() {
  $data = [];
  // Base data.
  $data['contactoEmpresas']['table']['group'] = t('Contacto Empresas');
  $data['contactoEmpresas']['table']['base'] = [
    'title' => t('Contacto Empresas'),
    'help' => t('Registros del formulario de contacto empresas')
  ];
  // Fields.
  $data['contactoEmpresas']['id'] = [
    'title' => t('id'),
    'help' => t('Id Registro Contacto'),
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
  $data['contactoEmpresas']['nombre'] = [
    'title' => t('Nombre'),
    'help' => t('Nombre del contacto'),
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
  $data['contactoEmpresas']['apellido'] = [
    'title' => t('Apellido'),
    'help' => t('Apellido del contacto'),
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
  $data['contactoEmpresas']['telefono'] = [
    'title' => t('Teléfono'),
    'help' => t('Teléfono'),
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
  $data['contactoEmpresas']['email'] = [
    'title' => t('Email'),
    'help' => t('Email del contacto'),
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

  $data['contactoEmpresas']['empresa'] = [
    'title' => t('Empresa'),
    'help' => t('Empresa del contacto'),
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
  $data['contactoEmpresas']['cargo'] = [
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

  $data['contactoEmpresas']['tamanoempresa'] = [
    'title' => t('Tamaño empresa'),
    'help' => t('Tamaño empresa'),
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

  $data['contactoEmpresas']['asunto'] = [
    'title' => t('Asunto'),
    'help' => t('Asunto del contacto'),
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
  
  $data['contactoEmpresas']['mensaje'] = [
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
  $data['contactoEmpresas']['contactdate'] = [
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
  return $data;
}
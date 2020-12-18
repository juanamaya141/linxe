<?php
/**
 * Implements hook_views_data().
 */
function contactopermodule_views_data() {
  $data = [];
  // Base data.
  $data['contactoPersonas']['table']['group'] = t('Contacto Personas');
  $data['contactoPersonas']['table']['base'] = [
    'title' => t('Contacto Personas'),
    'help' => t('Registros del formulario de contacto personas')
  ];
  // Fields.
  $data['contactoPersonas']['id'] = [
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
  $data['contactoPersonas']['nombre'] = [
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
  $data['contactoPersonas']['apellido'] = [
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
  $data['contactoPersonas']['celular'] = [
    'title' => t('Celular'),
    'help' => t('Celular del contacto'),
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
  $data['contactoPersonas']['email'] = [
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

  $data['contactoPersonas']['empresa'] = [
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

  $data['contactoPersonas']['asunto'] = [
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
  
  $data['contactoPersonas']['mensaje'] = [
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
  $data['contactoPersonas']['contactdate'] = [
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
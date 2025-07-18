<?php

// config/modules.php
return [
    /*
    |--------------------------------------------------------------------------
    | Módulos de la Aplicación
    |--------------------------------------------------------------------------
    |
    | Aquí se definen todos los módulos de la aplicación.
    | La 'key' es el identificador único (usado en rutas, permisos, etc.).
    | El 'name' es el nombre legible para mostrar en las vistas.
    |
    */
    'jefatura' => [
        'name' => 'Jefatura',
    ],
    'dca' => [
        'name' => 'Dirección de Coordinación Administrativa',
        'display_name' => 'DCA',
    ],
    'tesoreria' => [
        'name' => 'Dirección de Tesorería',
        'display_name' => 'Tesorería',
    ],
    'contabilidad' => [
        'name' => 'Dirección de Contabilidad',
        'display_name' => 'Contabilidad',
    ],
    'art222' => [
        'name' => 'Oficina de Artículo 222',
        'display_name' => 'Art. 222',
    ],
    'asesoria_contable' => [
        'name' => 'Asesoría Contable',
        'display_name' => 'Ases. Contab.',
    ],
];

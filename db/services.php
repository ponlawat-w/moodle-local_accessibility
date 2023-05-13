<?php

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_accessibility_saveoptionconfig' => [
        'classname' => 'local_accessibility\external\saveoptionconfig',
        'description' => 'Save user option config',
        'type' => 'write',
        'ajax' => true,
        'services' => []
    ]
];

<?php

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_accessibility_savewidgetconfig' => [
        'classname' => 'local_accessibility\external\savewidgetconfig',
        'description' => 'Save user widget config',
        'type' => 'write',
        'ajax' => true,
        'services' => []
    ]
];

<?php

namespace local_accessibility\external;

use external_function_parameters;
use external_single_structure;
use external_value;
use moodle_exception;

defined('MOODLE_INTERNAL') or die();

require_once(__DIR__ . '/../../lib.php');

class savewidgetconfig extends \external_api {
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'widget' => new external_value(PARAM_ALPHANUMEXT, 'Widget Name', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
            'configvalue' => new external_value(PARAM_TEXT, 'Configuration Value', VALUE_OPTIONAL, null, NULL_ALLOWED)
        ]);
    }

    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'True if success')
        ]);
    }

    public static function execute(string $widget, $configvalue): array {
        self::validate_parameters(self::execute_parameters(), ['widget' => $widget, 'configvalue' => $configvalue]);
        $widget = local_accessibility_getwidgetinstancebyname($widget);
        $widget->setuserconfig($configvalue);
        return ['success' => true];
    }
}

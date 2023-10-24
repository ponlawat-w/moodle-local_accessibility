<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * External API to save user's widget config
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_accessibility\external;

defined('MOODLE_INTERNAL') or die();

require_once(__DIR__ . '/../../lib.php');
require_once(__DIR__ . '/../../../../lib/externallib.php');

/**
 * External API class to save user's widget config
 */
class savewidgetconfig extends \core_external\external_api {
    public static function execute_parameters(): \core_external\external_function_parameters {
        return new \core_external\external_function_parameters([
            'widget' => new \core_external\external_value(PARAM_ALPHANUMEXT, 'Widget Name', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),
            'configvalue' => new \core_external\external_value(PARAM_TEXT, 'Configuration Value', VALUE_OPTIONAL, null, NULL_ALLOWED)
        ]);
    }

    public static function execute_returns(): \core_external\external_single_structure {
        return new \core_external\external_single_structure([
            'success' => new \core_external\external_value(PARAM_BOOL, 'True if success')
        ]);
    }

    public static function execute(string $widget, $configvalue): array {
        self::validate_parameters(self::execute_parameters(), ['widget' => $widget, 'configvalue' => $configvalue]);
        $widget = local_accessibility_getwidgetinstancebyname($widget);
        $widget->setuserconfig($configvalue);
        return ['success' => true];
    }
}

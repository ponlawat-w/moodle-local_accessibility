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

use external_function_parameters;
use external_single_structure;
use external_value;

defined('MOODLE_INTERNAL') or die();

require_once(__DIR__ . '/../../lib.php');

/**
 * External API class to save user's widget config
 */
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

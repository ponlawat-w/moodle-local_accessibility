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
 * Accessibility plugin test base that requires default 4 widgets
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_accessibility;

/**
 * Base case for plugin test that requires default widgets
 */
abstract class testcase extends \advanced_testcase {
    /**
     * Get default required widgets for testing
     *
     * @return string[]
     */
    protected static function gettestwidgets() {
        return ['textcolour', 'fontsize', 'backgroundcolour', 'fontface'];
    }

    /**
     * Check if default widgets are installed for testing
     * CALL parent::setUpBeforeClass IF THERE ARE OVERRIDING
     *
     * @return void
     */
    public static function setUpBeforeClass(): void {
        $required = self::gettestwidgets();
        $widgets = \core_plugin_manager::instance()->get_plugins_of_type('accessibility');
        foreach ($widgets as $widget) {
            if (!in_array($widget->name, $required)) {
                continue;
            }
            if (!$widget->is_installed_and_upgraded()) {
                throw new \Exception("Widget {$widget->name} is not properly installed.");
            }
            $required = array_diff($required, [$widget->name]);
        }
        if (count($required)) {
            throw new \Exception(
                'The following required widgets are not installed for testing: '
                . implode(', ', $required)
            );
        }

        local_accessibility_addwidgetstodb(false);
    }
}

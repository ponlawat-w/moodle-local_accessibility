<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_accessibility\hooks\output;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../lib.php');

/**
 * Allow plugin to modify headers.
 *
 * @package    local_accessibility
 * @copyright  2024 Bartlomiej Jencz <bartlomiej.jencz@p.lodz.pl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class before_http_headers {
    /**
     * Callback to allow modify headers.
     *
     * @param \core\hook\output\before_http_headers $hook
     */
    public static function callback(\core\hook\output\before_http_headers $hook): void {
        global $PAGE;
        $widgetinstances = local_accessibility_getwidgetinstances();
        if (!count($widgetinstances)) {
            return;
        }
        /** @var \moodle_page $PAGE */
        $PAGE->requires->css('/local/accessibility/styles.css');
        $PAGE->requires->css('/local/accessibility/styles.php');
        foreach ($widgetinstances as $widgetinstance) {
            $widgetinstance->init();
        }
    }
}

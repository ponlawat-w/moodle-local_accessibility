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
 * Form for admin to enable a widget
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../lib/formslib.php');
require_once(__DIR__ . '/../../lib.php');

/**
 * Form class to enable a widget
 */
class enablewidgetform extends moodleform {
    /**
     * Get an array of disabled widgets, with key being widget plugin name and value being plugin full name
     * @return string[]
     */
    private function getdisabledwidgets() {
        $results = [];
        $enabledwidgets = local_accessibility_getenabledwidgetnames();
        $allwidgets = local_accessibility_getinstalledwidgetnames();
        foreach ($allwidgets as $widgetname) {
            if (in_array($widgetname, $enabledwidgets)) {
                continue;
            }
            $results[$widgetname] = get_string('pluginname', 'accessibility_' . $widgetname);
        }
        return $results;
    }

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        $widgetstoadd = $this->getdisabledwidgets();
        if (!count($widgetstoadd)) {
            return;
        }

        $mform->addElement('select', 'name', get_string('addwidget', 'local_accessibility'), $widgetstoadd);
        $mform->setType('name', PARAM_TEXT);

        $this->add_action_buttons(false, get_string('add'));
    }
}

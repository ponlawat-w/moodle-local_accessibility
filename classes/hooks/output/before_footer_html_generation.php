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

use moodle_url;

/**
 * Allow plugin to modify footer.
 *
 * @package    local_accessibility
 * @copyright  2024 Bartlomiej Jencz <bartlomiej.jencz@p.lodz.pl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class before_footer_html_generation {
    /**
     * Callback to allow modify footer.
     *
     * @param \core\hook\output\before_footer_html_generation $hook
     */
    public static function callback(\core\hook\output\before_footer_html_generation $hook): void {
        global $OUTPUT, $PAGE, $CFG;
        /** @var \core_renderer $OUTPUT */ $OUTPUT; /** @var \moodle_page $PAGE */ $PAGE;

        $widgetinstances = local_accessibility_getwidgetinstances();
        if (!count($widgetinstances)) {
            return;
        }

        $PAGE->requires->js_call_amd('local_accessibility/panel', 'init');

        $mainbutton = $OUTPUT->render_from_template('local_accessibility/mainbutton', null);

        $widgets = [];
        foreach ($widgetinstances as $widgetinstance) {
            $widgets[] = [
                'name' => $widgetinstance->getname(),
                'title' => $widgetinstance->gettitle(),
                'class' => $widgetinstance->getclass(),
                'content' => $widgetinstance->getcontent(),
                'hasicon' => file_exists(
                    $CFG->dirroot . '/local/accessibility/widgets/' . $widgetinstance->getname()
                    . '/pix/icon.svg'
                ),
            ];
        }
        $panel = $OUTPUT->render_from_template('local_accessibility/panel', [
            'widgets' => $widgets,
            'resetallurl' => (new moodle_url('/local/accessibility/resetall.php', [
                'returnurl' => $PAGE->url,
                'sesskey' => sesskey(),
            ]))->out(false),
        ]);

        $hook->add_html($mainbutton . $panel);
    }
}

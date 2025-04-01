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
 * Widget definition
 *
 * @package     accessibility_linkhighlight
 * @copyright   2025 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace accessibility_linkhighlight;

use local_accessibility\widgets\widgetbase;

/**
 * Link highlight accessibility widget definition
 */
class linkhighlight extends widgetbase {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(get_string('pluginname', 'accessibility_linkhighlight'), 'linkhighlight');
    }

    /**
     * Initialisation
     *
     * @return void
     */
    public function init() {
        global $PAGE;
        $userconfig = $this->getuserconfig();
        if ($userconfig) {
            $this->addbodyclass('accessibility-linkhighlight-enabled');
        }

        /** @var \moodle_page $PAGE */
        $PAGE->requires->strings_for_js(['enabled', 'disabled'], 'accessibility_linkhighlight');
        $PAGE->requires->js_call_amd('accessibility_linkhighlight/script', 'init', [$userconfig]);
    }

    /**
     * Get widget content
     *
     * @return string
     */
    public function getcontent() {
        global $OUTPUT;
        /** @var \core_renderer $OUTPUT */ $OUTPUT;
        return $OUTPUT->render_from_template('accessibility_linkhighlight/default', []);
    }
}

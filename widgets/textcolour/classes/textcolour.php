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
 * @package     accessibility_textcolour
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace accessibility_textcolour;

use local_accessibility\widgets\apply_style;
use local_accessibility\widgets\colourwidget;

/**
 * Text colour accessibility widget definition
 */
class textcolour extends colourwidget implements apply_style {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(get_string('pluginname', 'accessibility_textcolour'), 'textcolour');
    }

    /**
     * Initialisation
     *
     * @return void
     */
    public function init() {
        global $PAGE;
        /** @var \moodle_page $PAGE */ $PAGE;

        $userconfig = $this->getuserconfig();
        if ($userconfig) {
            $this->addbodyclass('accessibility-textcolour');
        }

        $PAGE->requires->js_call_amd('local_accessibility/colourwidget', 'init', [
            $this->getfullname(),
            $this->name,
            'color',
            'accessibility-textcolour',
        ]);
    }

    /**
     * Apply own css styles, will be served to all users when plugin is enabled.
     *
     * @return string
     */
    public function apply_style(): string {
        $userconfig = $this->getuserconfig();
        if (!$userconfig) {
            return "";
        }
        // Strip all special characters except # because its needed for hex colors.
        // A check for #XX.. could have been used but this would not allow named css colors such as red, or green.
        $color = preg_replace("/[^A-Za-z0-9#]/", '', $userconfig);
        return "body.accessibility-textcolour, body.accessibility-textcolour * { color: {$color} !important; }";
    }
}

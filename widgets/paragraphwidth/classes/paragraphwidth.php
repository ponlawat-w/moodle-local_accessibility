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
 * @package     accessibility_paragraphwidth
 * @copyright   2025 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace accessibility_paragraphwidth;

use local_accessibility\widgets\rangewidget;

/**
 * Paragraph width accessibility widget definition
 */
class paragraphwidth extends rangewidget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            get_string('pluginname', 'accessibility_paragraphwidth'),
            'paragraphwidth',
            0,
            100,
            25,
            0
        );
    }

    /**
     * Widget initialisation
     *
     * @return void
     */
    public function init() {
        global $PAGE;

        $userconfig = $this->getuserconfig();
        if ($userconfig) {
            $userconfig = clean_param($userconfig, PARAM_FLOAT);
            $this->addbodyclass('accessibility-paragraphwidth-' . round($userconfig * 100));
        }

        /** @var \moodle_page $PAGE */
        $PAGE->requires->js_call_amd('accessibility_paragraphwidth/script', 'init', [$userconfig]);
    }

    /**
     * Set active user's configuration of the widget
     *
     * @param null|string $value Configuration value, null to remove entry from database
     * @return void
     */
    public function setuserconfig($value) {
        parent::setuserconfig($value === '0' ? null : $value);
    }
}

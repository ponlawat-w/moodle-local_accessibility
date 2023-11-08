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
 * @package     accessibility_fontsize
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_accessibility\widgets;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../classes/rangewidget.php');

/**
 * Font size accessibility widget definition
 */
class fontsize extends rangewidget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            get_string('pluginname', 'accessibility_fontsize'),
            'fontsize',
            0.5,
            2,
            0.25,
            1
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
            $this->addbodyclass('accessibility-fontsize-' . round($userconfig * 100));
        }

        /** @var \moodle_page $PAGE */
        $PAGE->requires->js_call_amd('accessibility_fontsize/script', 'init', [$userconfig]);
    }
}

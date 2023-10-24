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
 * Abstract class of range-selector widgets
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_accessibility\widgets;

/**
 * Abstract class of range-selector widgets
 */
abstract class rangewidget extends widgetbase {
    protected $min;
    protected $max;
    protected $step;
    protected $default;

    /**
     * Constructor
     *
     * @param string $title widget title
     * @param string $name widget name
     * @param double $min minimum value
     * @param double $max maximum value
     * @param double $step step value of slider
     * @param double $default default value
     */
    public function __construct($title, $name, $min, $max, $step, $default) {
        parent::__construct($title, $name);
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->default = $default;
    }

    public function getcontent() {
        /**
         * @var \core_renderer $OUTPUT
         */
        global $OUTPUT;
        return $OUTPUT->render_from_template('local_accessibility/widgets/range', [
            'title' => $this->title,
            'name' => $this->getfullname(),
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'default' => $this->default
        ]);
    }
}

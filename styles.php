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
 * Apply the styles of the subplugins to the pages.
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Avoid explicit logic check, we serve the styles to all.
require_once(__DIR__ . '/../../config.php'); // @codingStandardsIgnoreLine
require_once(__DIR__ . '/lib.php');
header('Content-Type: text/css');
$widgetinstances = local_accessibility_getwidgetinstances();
if (!count($widgetinstances)) {
    return;
}
foreach ($widgetinstances as $widgetinstance) {
    if ($widgetinstance instanceof \local_accessibility\widgets\apply_style) {
        echo $widgetinstance->apply_style();
    }
}

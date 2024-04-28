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
 * This will return CSS style text to be added to Moodle page that will overwrite default theme by user configuration
 *
 * @package     accessibility_backgroundcolour
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php'); // @codingStandardsIgnoreLine ignore login check as guest is also allowed.
header('Content-Type: text/css');

require_once(__DIR__ . '/../../lib.php');
require_once(__DIR__ . '/accessibility_backgroundcolour.php');

$widget = new local_accessibility\widgets\backgroundcolour();

$userconfig = $widget->getuserconfig();
if (!$userconfig) {
    exit;
}

echo <<<EOL
body.accessibility-backgroundcolour, body.accessibility-backgroundcolour *:not(.mediaplugin, .mediaplugin *) {
    background-color: {$userconfig} !important;
}
EOL;

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
 * Plugin administration pages are defined here.
 *
 * @package     local_accessibility
 * @category    admin
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    /** @var admin_root $ADMIN */
    $ADMIN->add(
        'modules',
        new admin_category(
            'accessibilitywidgets',
            new lang_string('accessibilitywidgets', 'local_accessibility')
        )
    );
    $url = new moodle_url('/local/accessibility/admin/manageenabledwidgets.php');
    $settings = new admin_externalpage(
        'local_accessibility',
        new lang_string('manageenabledwidgets', 'local_accessibility'),
        $url
    );
    $ADMIN->add('accessibilitywidgets', $settings);
}

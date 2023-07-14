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
 * Admin page for enabling widgets in a site
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
admin_externalpage_setup('local_accessibility');

require_once(__DIR__ . '/../lib.php');
require_once(__DIR__ . '/../classes/admin/enablewidgetform.php');

/**
 * @var core_renderer $OUTPUT
 * @var moodle_database $DB
 */

$url = new moodle_url('/local/accessibility/admin/manageenabledwidgets.php');

$enablewidgetform = new enablewidgetform();
if ($enablewidgetform->is_submitted()) {
    $data = $enablewidgetform->get_data();
    $widgetname = $data->name;
    if ($widgetname) {
        local_accessibility_enablewidget($widgetname);
    }
    redirect($url);
    exit;
}

$action = optional_param('action', null, PARAM_TEXT);
if ($action) {
    $id = required_param('id', PARAM_INT);
    $widget = $DB->get_record('accessibility_enabledwidgets', ['id' => $id], '*', MUST_EXIST);
    if ($action == 'moveup') {
        local_accessibility_moveup($widget);
        redirect($url); exit;
    }
    if ($action == 'movedown') {
        local_accessibility_movedown($widget);
        redirect($url); exit;
    }
    if ($action == 'disable') {
        local_accessibility_disablewidget($widget->name);
        redirect($url); exit;
    }
    throw new moodle_exception("Inavlid action {$action}");
}

$widgets = local_accessibility_getenabledwidgets();
$context = [];
foreach ($widgets as $widget) {
    $context[] = [
        'id' => $widget->id,
        'name' => $widget->name,
        'displayname' => get_string('pluginname', 'accessibility_' . $widget->name),
    ];
}
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('manageenabledwidgets', 'local_accessibility'));
echo $OUTPUT->render_from_template('local_accessibility/admin/enabledwidgets', [
    'widgets' => $context,
    'baseurl' => $url
]);
echo html_writer::start_tag('hr');
$enablewidgetform->display();
echo $OUTPUT->footer();

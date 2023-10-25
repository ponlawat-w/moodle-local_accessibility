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
 * Plugin function library
 *
 * @package     local_accessibility
 * @category    string
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/classes/widgetbase.php');
require_once(__DIR__ . '/classes/rangewidget.php');
require_once(__DIR__ . '/classes/colourwidget.php');

/**
 * Return names of installed widgets
 *
 * @return string[]
 */
function local_accessibility_getinstalledwidgetnames() {
    $pluginmanager = core_plugin_manager::instance();
    $plugins = $pluginmanager->get_plugins_of_type('accessibility');
    return array_map(function($x) {
        return $x->name;
    }, $plugins);
}

/**
 * Return database records of enabled widgets
 *
 * @return stdClass[]
 */
function local_accessibility_getenabledwidgets() {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    return $DB->get_records('accessibility_enabledwidgets', [], 'sequence ASC');
}

/**
 * Return names of enabled widgets
 *
 * @return string[]
 */
function local_accessibility_getenabledwidgetnames() {
    return array_map(function($record) {
        return $record->name;
    }, local_accessibility_getenabledwidgets());
}

/**
 * Clean widget sequence from 1
 *
 * @return void
 */
function local_accessibility_resequence() {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    $widgets = local_accessibility_getenabledwidgets();
    $i = 1;
    foreach ($widgets as $widget) {
        $widget->sequence = $i++;
        $DB->update_record('accessibility_enabledwidgets', $widget);
    }
}

/**
 * Enable a widget
 *
 * @param string $widgetname
 * @return void
 */
function local_accessibility_enablewidget($widgetname) {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    if (!in_array($widgetname, local_accessibility_getinstalledwidgetnames())) {
        throw new moodle_exception("widget name {$widgetname} is not installed");
    }
    if ($DB->record_exists('accessibility_enabledwidgets', ['name' => $widgetname])) {
        throw new moodle_exception("widget {$widgetname} is already enabled");
    }
    $maxsequencerecord = $DB->get_record_sql('SELECT sequence FROM {accessibility_enabledwidgets} ORDER BY sequence DESC LIMIT 1');
    $record = new stdClass();
    $record->name = $widgetname;
    $record->sequence = $maxsequencerecord ? $maxsequencerecord->sequence + 1 : 1;
    return $DB->insert_record('accessibility_enabledwidgets', $record);
}

/**
 * Disable a widget
 *
 * @param string $widgetname
 * @return void
 */
function local_accessibility_disablewidget($widgetname) {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    $DB->delete_records('accessibility_enabledwidgets', ['name' => $widgetname]);
    $DB->delete_records('accessibility_userconfigs', ['widget' => $widgetname]);
    return local_accessibility_resequence();
}

/**
 * Swap display sequence of two widgets
 *
 * @param stdClass $widget1 widget record
 * @param stdClass $widget2 widget record
 * @return void
 */
function local_accessibility_swapsequence($widget1, $widget2) {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    $temp = $widget1->sequence;
    $widget1->sequence = $widget2->sequence;
    $widget2->sequence = $temp;
    return $DB->update_record('accessibility_enabledwidgets', $widget1)
        && $DB->update_record('accessibility_enabledwidgets', $widget2);
}

/**
 * Move widget sequence up
 *
 * @param stdClass $widget
 * @return void
 */
function local_accessibility_moveup($widget) {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    $previouswidget = $DB->get_record_sql(
        'SELECT * FROM {accessibility_enabledwidgets} WHERE sequence < ? ORDER BY sequence DESC LIMIT 1',
        [$widget->sequence]
    );
    if (!$previouswidget) {
        return;
    }
    local_accessibility_swapsequence($widget, $previouswidget);
}

/**
 * Move widget sequence down
 *
 * @param stdClass $widget
 * @return void
 */
function local_accessibility_movedown($widget) {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    $nextwidget = $DB->get_record_sql(
        'SELECT * FROM {accessibility_enabledwidgets} WHERE sequence > ? ORDER BY sequence ASC LIMIT 1',
        [$widget->sequence]
    );
    if (!$nextwidget) {
        return;
    }
    local_accessibility_swapsequence($widget, $nextwidget);
}

/**
 * Get enabled widget instances
 *
 * @return local_accessibility\widgets\widgetbase[]
 */
function local_accessibility_getwidgetinstances() {
    $enabledwidgetnames = local_accessibility_getenabledwidgetnames();
    return array_map(function($name) {
        return local_accessibility_getwidgetinstancebyname($name);
    }, $enabledwidgetnames);
}

/**
 * Get widget instance by name
 *
 * @param string $widgetname
 * @return local_accessibility\widgets\widgetbase
 */
function local_accessibility_getwidgetinstancebyname($widgetname) {
    global $CFG;
    $filepath = "{$CFG->dirroot}/local/accessibility/widgets/{$widgetname}/accessibility_{$widgetname}.php";
    if (!file_exists($filepath)) {
        throw new moodle_exception("File {$filepath} does not exist");
    }
    require_once($filepath);
    $classname = 'local_accessibility\widgets\\' . $widgetname;
    if (!class_exists($classname)) {
        throw new moodle_exception("Class {$classname} does not exist in {$filepath}");
    }
    return new $classname();
}

/**
 * Injector of widget initialisation before rendering page
 *
 * @return void
 */
function local_accessibility_before_http_headers() {
    global $PAGE;
    $widgetinstances = local_accessibility_getwidgetinstances();
    if (!count($widgetinstances)) {
        return;
    }
    /** @var \moodle_page $PAGE */
    $PAGE->requires->css('/local/accessibility/styles.css');
    foreach ($widgetinstances as $widgetinstance) {
        $widgetinstance->init();
    }
}

/**
 * Injector of widgets and panel initialisation before finish rendering page
 *
 * @return void
 */
function local_accessibility_before_footer() {
    global $OUTPUT, $PAGE;
    /** @var \core_renderer $OUTPUT */ $OUTPUT; /** @var \moodle_page $PAGE */ $PAGE;

    $widgetinstances = local_accessibility_getwidgetinstances();
    if (!count($widgetinstances)) {
        return '';
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
        ];
    }
    $panel = $OUTPUT->render_from_template('local_accessibility/panel', ['widgets' => $widgets]);

    return $mainbutton . $panel;
}

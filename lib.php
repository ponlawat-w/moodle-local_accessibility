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
    $results = [];
    foreach ($plugins as $plugin) {
        if ($plugin->is_installed_and_upgraded()) {
            $results[] = $plugin->name;
        }
    }
    return $results;
}

/**
 * Add installed widget into database table
 *
 * @param bool $enablenewplugin
 *
 * @return void
 */
function local_accessibility_addwidgetstodb($enablenewplugin = true) {
    global $DB;
    /** @var \moodle_database $DB */ $DB;

    $installedwidgets = local_accessibility_getinstalledwidgetnames();
    if (!count($installedwidgets)) {
        return;
    }

    $widgets = $DB->get_records('local_accessibility_widgets', [], 'sequence ASC', 'id,name,sequence');
    $widgetnames = array_map(function($record) {
        return $record->name;
    }, $widgets);
    $i = count($widgets) ? array_values($widgets)[count($widgets) - 1]->sequence : 0;

    $newrecords = [];
    foreach ($installedwidgets as $installedwidget) {
        if (in_array($installedwidget, $widgetnames)) {
            continue;
        }
        $newrecord = new stdClass();
        $newrecord->name = $installedwidget;
        $newrecord->enabled = $enablenewplugin ? 1 : 0;
        $newrecord->sequence = $enablenewplugin ? ++$i : -1;
        $newrecords[] = $newrecord;
    }

    if (!count($newrecords)) {
        return;
    }

    $DB->insert_records('local_accessibility_widgets', $newrecords);
    local_accessibility_resequence();
}

/**
 * Check if database structure of plugin is before version 1.0.0
 *
 * @return bool
 */
function local_accessiblity_before_1_0_0() {
    return core_plugin_manager::instance()->get_plugin_info('local_accessibility')->versiondb < 2023110101;
}

/**
 * Return database records of enabled widgets
 *
 * @return stdClass[]
 */
function local_accessibility_getenabledwidgets() {
    global $DB;
    /** @var moodle_database $DB */ $DB;

    // Table names have been changed after 1.0.0,
    // to prevent database exception and bring to upgrade page, return an empty array.
    if (local_accessiblity_before_1_0_0()) {
        return [];
    }

    local_accessibility_addwidgetstodb();
    return $DB->get_records('local_accessibility_widgets', ['enabled' => 1], 'sequence ASC');
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
        $DB->update_record('local_accessibility_widgets', $widget);
    }
}

/**
 * Enable a widget
 *
 * @param string $widgetname
 * @return bool
 */
function local_accessibility_enablewidget($widgetname) {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    local_accessibility_addwidgetstodb();
    $widget = $DB->get_record('local_accessibility_widgets', ['name' => $widgetname]);
    if (!$widget) {
        throw new moodle_exception("widget name {$widgetname} is not installed");
    }
    if ($widget->enabled) {
        throw new moodle_exception("widget {$widgetname} is already enabled");
    }
    $widget->enabled = 1;
    $widget->sequence = count(local_accessibility_getenabledwidgets()) + 1;
    return $DB->update_record('local_accessibility_widgets', $widget);
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
    local_accessibility_addwidgetstodb();
    $widget = $DB->get_record('local_accessibility_widgets', ['name' => $widgetname]);
    if (!$widget) {
        throw new moodle_exception("widget name {$widgetname} is not installed");
    }
    if (!$widget->enabled) {
        throw new moodle_exception("widget {$widgetname} is already disabled");
    }
    $widget->enabled = 0;
    $widget->sequence = -1;
    if (!$DB->update_record('local_accessibility_widgets', $widget)) {
        return false;
    }
    local_accessibility_resequence();
    return true;
}

/**
 * Swap display sequence of two widgets
 *
 * @param stdClass $widget1 widget record
 * @param stdClass $widget2 widget record
 * @return bool
 */
function local_accessibility_swapsequence($widget1, $widget2) {
    global $DB;
    /** @var moodle_database $DB */ $DB;
    $temp = $widget1->sequence;
    $widget1->sequence = $widget2->sequence;
    $widget2->sequence = $temp;
    return $DB->update_record('local_accessibility_widgets', $widget1)
        && $DB->update_record('local_accessibility_widgets', $widget2);
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
        'SELECT * FROM {local_accessibility_widgets} WHERE enabled = 1 AND sequence < ? ORDER BY sequence DESC LIMIT 1',
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
        'SELECT * FROM {local_accessibility_widgets} WHERE enabled = 1 AND sequence > ? ORDER BY sequence ASC LIMIT 1',
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
 * @deprecated since Moodle 4.3
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
 * @deprecated since Moodle 4.3
 * @return string
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
    $panel = $OUTPUT->render_from_template('local_accessibility/panel', [
        'widgets' => $widgets,
        'resetallurl' => (new moodle_url('/local/accessibility/resetall.php', [
            'returnurl' => $PAGE->url,
            'sesskey' => sesskey(),
        ]))->out(false),
    ]);

    return $mainbutton . $panel;
}

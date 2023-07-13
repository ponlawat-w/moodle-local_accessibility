<?php

require_once(__DIR__ . '/classes/widgetbase.php');
require_once(__DIR__ . '/classes/rangewidget.php');
require_once(__DIR__ . '/classes/colourwidget.php');

/**
 * @return string[]
 */
function local_accessibility_getinstalledwidgetnames() {
    $pluginmanager = core_plugin_manager::instance();
    $plugins = $pluginmanager->get_plugins_of_type('accessibility');
    return array_map(function($x) { return $x->name; }, $plugins);
}

/**
 * @return stdClass[]
 */
function local_accessibility_getenabledwidgets() {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    return $DB->get_records('accessibility_enabledwidgets', [], 'sequence ASC');
}

/**
 * @return string[]
 */
function local_accessibility_getenabledwidgetnames() {
    return array_map(function($record) { return $record->name; }, local_accessibility_getenabledwidgets());
}

function local_accessibility_resequence() {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $widgets = local_accessibility_getenabledwidgets();
    $i = 1;
    foreach ($widgets as $widget) {
        $widget->sequence = $i++;
        $DB->update_record('accessibility_enabledwidgets', $widget);
    }
}

function local_accessibility_enablewidget($widgetname) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
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

function local_accessibility_disablewidget($widgetname) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $DB->delete_records('accessibility_enabledwidgets', ['name' => $widgetname]);
    $DB->delete_records('accessibility_userconfigs', ['widget' => $widgetname]);
    return local_accessibility_resequence();
}

function local_accessibility_swapsequence($widget1, $widget2) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $temp = $widget1->sequence;
    $widget1->sequence = $widget2->sequence;
    $widget2->sequence = $temp;
    return $DB->update_record('accessibility_enabledwidgets', $widget1) && $DB->update_record('accessibility_enabledwidgets', $widget2);
}

function local_accessibility_moveup($widget) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $previouswidget = $DB->get_record_sql('SELECT * FROM {accessibility_enabledwidgets} WHERE sequence < ? ORDER BY sequence DESC LIMIT 1', [$widget->sequence]);
    if (!$previouswidget) {
        return;
    }
    local_accessibility_swapsequence($widget, $previouswidget);
}

function local_accessibility_movedown($widget) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $nextwidget = $DB->get_record_sql('SELECT * FROM {accessibility_enabledwidgets} WHERE sequence > ? ORDER BY sequence ASC LIMIT 1', [$widget->sequence]);
    if (!$nextwidget) {
        return;
    }
    local_accessibility_swapsequence($widget, $nextwidget);
}

/**
 * @return local_accessibility\widgets\widgetbase[]
 */
function local_accessibility_getwidgetinstances() {
    $enabledwidgetnames = local_accessibility_getenabledwidgetnames();
    return array_map(function($name) { return local_accessibility_getwidgetinstancebyname($name); }, $enabledwidgetnames);
}

/**
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

function local_accessibility_before_http_headers() {
    /**
     * @var \moodle_page $PAGE
     */
    global $PAGE;
    $widgetinstances = local_accessibility_getwidgetinstances();
    if (!count($widgetinstances)) {
        return;
    }
    $PAGE->requires->css('/local/accessibility/styles.css');
    foreach ($widgetinstances as $widgetinstance) {
        $widgetinstance->init();
    }
}

function local_accessibility_before_footer() {
    /**
     * @var \core_renderer $OUTPUT
     * @var \moodle_page $PAGE
     */
    global $OUTPUT, $PAGE;

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
            'content' => $widgetinstance->getcontent()
        ];
    }
    $panel = $OUTPUT->render_from_template('local_accessibility/panel', ['widgets' => $widgets]);

    return $mainbutton . $panel;
}

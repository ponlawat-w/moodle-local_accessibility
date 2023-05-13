<?php

require_once(__DIR__ . '/classes/optionbase.php');
require_once(__DIR__ . '/classes/optionrange.php');
require_once(__DIR__ . '/classes/optioncolour.php');

/**
 * @return string[]
 */
function local_accessibility_getinstalledoptionnames() {
    $pluginmanager = core_plugin_manager::instance();
    $plugins = $pluginmanager->get_plugins_of_type('accessibility');
    return array_map(function($x) { return $x->name; }, $plugins);
}

/**
 * @return stdClass[]
 */
function local_accessibility_getenabledoptions() {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    return $DB->get_records('accessibility_enabledoptions', [], 'sequence ASC');
}

/**
 * @return string[]
 */
function local_accessibility_getenabledoptionnames() {
    return array_map(function($record) { return $record->name; }, local_accessibility_getenabledoptions());
}

function local_accessibility_resequence() {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $options = local_accessibility_getenabledoptions();
    $i = 1;
    foreach ($options as $option) {
        $option->sequence = $i++;
        $DB->update_record('accessibility_enabledoptions', $option);
    }
}

function local_accessibility_enableoption($optionname) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    if (!in_array($optionname, local_accessibility_getinstalledoptionnames())) {
        throw new moodle_exception("Option name {$optionname} is not installed");
    }
    if ($DB->record_exists('accessibility_enabledoptions', ['name' => $optionname])) {
        throw new moodle_exception("Option {$optionname} is already enabled");
    }
    $maxsequencerecord = $DB->get_record_sql('SELECT sequence FROM {accessibility_enabledoptions} ORDER BY sequence DESC LIMIT 1');
    $record = new stdClass();
    $record->name = $optionname;
    $record->sequence = $maxsequencerecord ? $maxsequencerecord->sequence + 1 : 1;
    return $DB->insert_record('accessibility_enabledoptions', $record);
}

function local_accessibility_disableoption($optionname) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $DB->delete_records('accessibility_enabledoptions', ['name' => $optionname]);
    $DB->delete_records('accessibility_userconfigs', ['optionname' => $optionname]);
    return local_accessibility_resequence();
}

function local_accessibility_swapsequence($option1, $option2) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $temp = $option1->sequence;
    $option1->sequence = $option2->sequence;
    $option2->sequence = $temp;
    return $DB->update_record('accessibility_enabledoptions', $option1) && $DB->update_record('accessibility_enabledoptions', $option2);
}

function local_accessibility_moveup($option) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $previousoption = $DB->get_record_sql('SELECT * FROM {accessibility_enabledoptions} WHERE sequence < ? ORDER BY sequence DESC LIMIT 1', [$option->sequence]);
    if (!$previousoption) {
        return;
    }
    local_accessibility_swapsequence($option, $previousoption);
}

function local_accessibility_movedown($option) {
    /**
     * @var moodle_database $DB
     */
    global $DB;
    $nextoption = $DB->get_record_sql('SELECT * FROM {accessibility_enabledoptions} WHERE sequence > ? ORDER BY sequence ASC LIMIT 1', [$option->sequence]);
    if (!$nextoption) {
        return;
    }
    local_accessibility_swapsequence($option, $nextoption);
}

/**
 * @return local_accessibility\options\optionbase[]
 */
function local_accessibility_getoptioninstances() {
    $enabledoptionnames = local_accessibility_getenabledoptionnames();
    return array_map(function($name) { return local_accessibility_getoptioninstancebyname($name); }, $enabledoptionnames);
}

/**
 * @param string $optionname
 * @return local_accessibility\options\optionbase
 */
function local_accessibility_getoptioninstancebyname($optionname) {
    global $CFG;
    $filepath = "{$CFG->dirroot}/local/accessibility/options/{$optionname}/accessibility_{$optionname}.php";
    if (!file_exists($filepath)) {
        throw new moodle_exception("File {$filepath} does not exist");
    }
    require_once($filepath);
    $classname = 'local_accessibility\options\\' . $optionname;
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
    $optioninstances = local_accessibility_getoptioninstances();
    if (!count($optioninstances)) {
        return;
    }
    $PAGE->requires->css('/local/accessibility/styles.css');
    foreach ($optioninstances as $optioninstance) {
        $optioninstance->init();
    }
}

function local_accessibility_before_footer() {
    /**
     * @var \core_renderer $OUTPUT
     * @var \moodle_page $PAGE
     */
    global $OUTPUT, $PAGE;

    $optioninstances = local_accessibility_getoptioninstances();
    if (!count($optioninstances)) {
        return '';
    }
    
    $PAGE->requires->js_call_amd('local_accessibility/panel', 'init');
    
    $mainbutton = $OUTPUT->render_from_template('local_accessibility/mainbutton', null);
    
    $options = [];
    foreach ($optioninstances as $optioninstance) {
        $options[] = [
            'name' => $optioninstance->getname(),
            'title' => $optioninstance->gettitle(),
            'class' => $optioninstance->getclass(),
            'content' => $optioninstance->getcontent()
        ];
    }
    $panel = $OUTPUT->render_from_template('local_accessibility/panel', ['options' => $options]);

    return $mainbutton . $panel;
}

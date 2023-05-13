<?php

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
admin_externalpage_setup('local_accessibility');

require_once(__DIR__ . '/../lib.php');
require_once(__DIR__ . '/../classes/admin/enableoptionform.php');

/**
 * @var core_renderer $OUTPUT
 * @var moodle_database $DB
 */

$url = new moodle_url('/local/accessibility/admin/manageenabledoptions.php');

$enableoptionform = new enableoptionform();
if ($enableoptionform->is_submitted()) {
    $data = $enableoptionform->get_data();
    $optionname = $data->name;
    if ($optionname) {
        local_accessibility_enableoption($optionname);
    }
    redirect($url);
    exit;
}

$action = optional_param('action', null, PARAM_TEXT);
if ($action) {
    $id = required_param('id', PARAM_INT);
    $option = $DB->get_record('accessibility_enabledoptions', ['id' => $id], '*', MUST_EXIST);
    if ($action == 'moveup') {
        local_accessibility_moveup($option);
        redirect($url); exit;
    }
    if ($action == 'movedown') {
        local_accessibility_movedown($option);
        redirect($url); exit;
    }
    if ($action == 'disable') {
        local_accessibility_disableoption($option->name);
        redirect($url); exit;
    }
    throw new moodle_exception("Inavlid action {$action}");
}

$options = local_accessibility_getenabledoptions();
$context = [];
foreach ($options as $option) {
    $context[] = [
        'id' => $option->id,
        'name' => $option->name,
        'displayname' => get_string('pluginname', 'accessibility_' . $option->name),
    ];
}
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('manageenabledoptions', 'local_accessibility'));
echo $OUTPUT->render_from_template('local_accessibility/admin/enabledoptions', [
    'options' => $context,
    'baseurl' => $url
]);
echo html_writer::start_tag('hr');
$enableoptionform->display();
echo $OUTPUT->footer();

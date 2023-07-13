<?php

require_once(__DIR__ . '/../../../../lib/formslib.php');
require_once(__DIR__ . '/../../lib.php');

class enablewidgetform extends moodleform {
    /**
     * @return array
     */
    private function getdisabledwidgets() {
        $results = [];
        $enabledwidgets = local_accessibility_getenabledwidgetnames();
        $allwidgets = local_accessibility_getinstalledwidgetnames();
        foreach ($allwidgets as $widgetname) {
            if (in_array($widgetname, $enabledwidgets)) {
                continue;
            }
            $results[$widgetname] = get_string('pluginname', 'accessibility_' . $widgetname);
        }
        return $results;
    }

    public function definition() {
        $mform = $this->_form;

        $widgetstoadd = $this->getdisabledwidgets();
        if (!count($widgetstoadd)) {
            return;
        }

        $mform->addElement('select', 'name', get_string('addwidget', 'local_accessibility'), $widgetstoadd);
        $mform->setType('name', PARAM_TEXT);

        $this->add_action_buttons(false, get_string('add'));
    }
}

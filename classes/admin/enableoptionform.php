<?php

require_once(__DIR__ . '/../../../../lib/formslib.php');
require_once(__DIR__ . '/../../lib.php');

class enableoptionform extends moodleform {
    /**
     * @return array
     */
    private function getdisabledoptions() {
        $results = [];
        $enabledoptions = local_accessibility_getenabledoptionnames();
        $alloptions = local_accessibility_getinstalledoptionnames();
        foreach ($alloptions as $optionname) {
            if (in_array($optionname, $enabledoptions)) {
                continue;
            }
            $results[$optionname] = get_string('pluginname', 'accessibility_' . $optionname);
        }
        return $results;
    }

    public function definition() {
        $mform = $this->_form;

        $optionstoadd = $this->getdisabledoptions();
        if (!count($optionstoadd)) {
            return;
        }

        $mform->addElement('select', 'name', get_string('addoption', 'local_accessibility'), $optionstoadd);
        $mform->setType('name', PARAM_TEXT);

        $this->add_action_buttons(false, get_string('add'));
    }
}

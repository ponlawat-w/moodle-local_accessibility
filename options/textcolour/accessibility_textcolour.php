<?php

namespace local_accessibility\options;

defined('MOODLE_INTERNAL') or die();

require_once(__DIR__ . '/../../classes/optioncolour.php');

class textcolour extends optioncolour {
    public function __construct() {
        parent::__construct(get_string('pluginname', 'accessibility_textcolour'), 'textcolour');
    }

    public function init() {
        /**
         * @var \moodle_page $PAGE
         */
        global $PAGE;

        $userconfig = $this->getuserconfig();
        if ($userconfig) {
            $this->addbodyclass('accessibility-textcolour');
            $PAGE->requires->css('/local/accessibility/options/textcolour/styles.php');
        }

        $PAGE->requires->js_call_amd('local_accessibility/colouroption', 'init', [
            $this->getfullname(),
            $this->name,
            'color',
            'accessibility-textcolour'
        ]);
    }
}

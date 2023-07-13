<?php

namespace local_accessibility\widgets;

defined('MOODLE_INTERNAL') or die();

require_once(__DIR__ . '/../../classes/colourwidget.php');

class backgroundcolour extends colourwidget {
    public function __construct() {
        parent::__construct(get_string('pluginname', 'accessibility_backgroundcolour'), 'backgroundcolour');
    }

    public function init() {
        /**
         * @var \moodle_page $PAGE
         */
        global $PAGE;

        $userconfig = $this->getuserconfig();
        if ($userconfig) {
            $this->addbodyclass('accessibility-backgroundcolour');
            $PAGE->requires->css('/local/accessibility/widgets/backgroundcolour/styles.php');
        }

        $PAGE->requires->js_call_amd('local_accessibility/colourwidget', 'init', [
            $this->getfullname(),
            $this->name,
            'background-color',
            'accessibility-backgroundcolour'
        ]);
    }
}

<?php

namespace local_accessibility\widgets;

defined('MOODLE_INTERNAL') or die();

require_once(__DIR__ . '/../../classes/colourwidget.php');

class textcolour extends colourwidget {
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
            $PAGE->requires->css('/local/accessibility/widgets/textcolour/styles.php');
        }

        $PAGE->requires->js_call_amd('local_accessibility/colourwidget', 'init', [
            $this->getfullname(),
            $this->name,
            'color',
            'accessibility-textcolour'
        ]);
    }
}

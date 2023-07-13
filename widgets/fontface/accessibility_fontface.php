<?php

namespace local_accessibility\widgets;

defined('MOODLE_INTERNAL') or die();

class fontface extends widgetbase {
    public function __construct() {
        parent::__construct(get_string('pluginname', 'accessibility_fontface'), 'fontface');
    }

    public function init() {
        /**
         * @var \moodle_page $PAGE
         */
        global $PAGE;

        $userconfig = $this->getuserconfig();
        if ($userconfig) {
            $this->addbodyclass('accessibility-fontface-' . $userconfig);
        }

        $PAGE->requires->js_call_amd('accessibility_fontface/script', 'init');
    }

    public function getcontent() {
        /**
         * @var \core_renderer $OUTPUT
         */
        global $OUTPUT;
        return $OUTPUT->render_from_template('accessibility_fontface/default', []);
    }
}

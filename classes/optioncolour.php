<?php

namespace local_accessibility\options;

defined('MOODLE_INTERNAL') or die();

abstract class optioncolour extends optionbase {
    protected $class = 'col-12';

    public function getcontent() {
        /**
         * @var \core_renderer $OUTPUT
         * @var \moodle_page $PAGE
         */
        global $OUTPUT, $PAGE;
        $id = $this->getfullname() . '-picker';
        $icon = new \pix_icon('i/loading', '', 'moodle', ['class' => 'loadingicon']);
        $PAGE->requires->js_init_call('M.util.init_colour_picker', [$id, true]);
        return $OUTPUT->render_from_template('local_accessibility/options/colour', [
            'id' => $id,
            'name' => $this->getfullname(),
            'optionname' => $this->getfullname(),
            'value' => '',
            'icon' => $icon->export_for_template($OUTPUT),
            'haspreviewconfig' => false,
            'forceltr' => false,
            'readonly' => false
        ]);
    }
}

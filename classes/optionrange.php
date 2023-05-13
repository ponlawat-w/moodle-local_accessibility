<?php

namespace local_accessibility\options;

defined('MOODLE_INTERNAL') or die();

abstract class optionrange extends optionbase {
    protected $min;
    protected $max;
    protected $step;
    protected $default;

    public function __construct($title, $name, $min, $max, $step, $default) {
        parent::__construct($title, $name);
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->default = $default;
    }

    public function getcontent() {
        /**
         * @var \core_renderer $OUTPUT
         */
        global $OUTPUT;
        return $OUTPUT->render_from_template('local_accessibility/options/range', [
            'title' => $this->title,
            'name' => $this->getfullname(),
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'default' => $this->default
        ]);
    }
}

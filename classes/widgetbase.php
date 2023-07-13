<?php

namespace local_accessibility\widgets;

use stdClass;

defined('MOODLE_INTERNAL') or die();

abstract class widgetbase {
    protected $title;
    protected $name;
    protected $class = 'col-12 col-md-6';

    protected function __construct($title, $name) {
        $this->title = $title;
        $this->name = $name;
    }

    public function getname() {
        return $this->name;
    }

    public function getfullname() {
        return 'accessibility_' . $this->name;
    }

    public function gettitle() {
        return $this->title;
    }

    public function getclass() {
        return $this->class;
    }

    public function init() {
        return;
    }

    /**
     * @return null|string
     */
    public function getuserconfig() {
        /**
         * @var \moodle_database $DB
         */
        global $DB, $USER;
        
        if (!$USER || !$USER->id) {
            return null;
        }

        $record = $DB->get_record('accessibility_userconfigs', ['widget' => $this->name, 'userid' => $USER->id]);
        return $record ? $record->configvalue : null;
    }

    public function setuserconfig($value) {
        /**
         * @var \moodle_database $DB
         */
        global $DB, $USER;

        if (!$USER || !$USER->id) {
            return;
        }

        if (!$value) {
            return $DB->delete_records('accessibility_userconfigs', ['widget' => $this->name, 'userid' => $USER->id]);
        }

        $record = $DB->get_record('accessibility_userconfigs', ['widget' => $this->name, 'userid' => $USER->id]);
        if ($record) {
            $record->configvalue = $value;
            return $DB->update_record('accessibility_userconfigs', $record);
        }

        $record = new stdClass();
        $record->widget = $this->name;
        $record->userid = $USER->id;
        $record->configvalue = $value;
        return $DB->insert_record('accessibility_userconfigs', $record);
    }

    protected function addbodyclass($classname) {
        /**
         * @var \moodle_page $PAGE
         */
        global $PAGE;
        $PAGE->add_body_class($classname);
    }

    public abstract function getcontent();
}

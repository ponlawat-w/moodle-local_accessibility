<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Base class for widgets
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_accessibility\widgets;

use stdClass;

/**
 * Base class for widgets
 */
abstract class widgetbase {

    /** @var string $title widget title */
    protected $title;

    /** @var string $name widget name */
    protected $name;

    /** @var string $class widget column class in panel, this to indicates size of widget box in the card */
    protected $class = 'col-12 col-md-6';

    /**
     * Constructor
     *
     * @param string $title
     * @param string $name
     */
    protected function __construct($title, $name) {
        $this->title = $title;
        $this->name = $name;
    }

    /**
     * Get widget name
     *
     * @return string
     */
    public function getname() {
        return $this->name;
    }

    /**
     * Get widget plugin name (prefixed with "accessibility_")
     *
     * @return string
     */
    public function getfullname() {
        return 'accessibility_' . $this->name;
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function gettitle() {
        return $this->title;
    }

    /**
     * Get widget box class in panel card
     *
     * @return string
     */
    public function getclass() {
        return $this->class;
    }

    /**
     * Initialise widget
     *
     * @return void
     */
    public function init() {
        return;
    }

    /**
     * Get configuration of non-login user from guest session
     *
     * @return null|string
     */
    protected function getguestconfig() {
        global $SESSION;
        /** @var stdClass $SESSION */ $SESSION;

        if (!isset($SESSION->local_accessibility)
            || !isset($SESSION->local_accessibility->guestconfigs)
            || !isset($SESSION->local_accessibility->guestconfigs->{$this->name})
            || is_null($SESSION->local_accessibility->guestconfigs->{$this->name})
        ) {
            return null;
        }

        return $SESSION->local_accessibility->guestconfigs->{$this->name};
    }

    /**
     * Set non-login configuration into user's session
     *
     * @param null|string $value
     * @return void
     */
    protected function setguestconfig($value) {
        global $SESSION;
        /** @var stdClass $SESSION */ $SESSION;

        if (!isset($SESSION->local_accessibility)) {
            $SESSION->local_accessibility = new stdClass();
        }
        if (!isset($SESSION->local_accessibility->guestconfigs)) {
            $SESSION->local_accessibility->guestconfigs = new stdClass();
        }

        if (!$value) {
            unset($SESSION->local_accessibility->guestconfigs->{$this->name});
            return;
        }

        $SESSION->local_accessibility->guestconfigs->{$this->name} = $value;
    }

    /**
     * Get active user's configuration of the widget
     *
     * @return null|string
     */
    public function getuserconfig() {
        global $DB, $USER;
        /** @var \moodle_database $DB */ $DB; /** @var stdClass $USER */ $USER;

        if (!$USER || !$USER->id) {
            return $this->getguestconfig();
        }

        $record = $DB->get_record('local_accessibility_configs', ['widget' => $this->name, 'userid' => $USER->id]);
        return $record ? $record->configvalue : null;
    }

    /**
     * Set active user's configuration of the widget
     *
     * @param null|string $value Configuration value, null to remove entry from database
     * @return void
     */
    public function setuserconfig($value) {
        global $DB, $USER;
        /** @var \moodle_database $DB */ $DB; /** @var stdClass $USER */ $USER;

        if (!$USER || !$USER->id) {
            return $this->setguestconfig($value);
        }

        if (!$value) {
            return $DB->delete_records('local_accessibility_configs', ['widget' => $this->name, 'userid' => $USER->id]);
        }

        $record = $DB->get_record('local_accessibility_configs', ['widget' => $this->name, 'userid' => $USER->id]);
        if ($record) {
            $record->configvalue = $value;
            return $DB->update_record('local_accessibility_configs', $record);
        }

        $record = new stdClass();
        $record->widget = $this->name;
        $record->userid = $USER->id;
        $record->configvalue = $value;
        return $DB->insert_record('local_accessibility_configs', $record);
    }

    /**
     * Add CSS class to body tag
     *
     * @param string $classname
     * @return void
     */
    protected function addbodyclass($classname) {
        global $PAGE;
        /** @var \moodle_page $PAGE */
        $PAGE->add_body_class($classname);
    }

    /**
     * Get HTML content of widget box in accessibility panel
     *
     * @return void
     */
    abstract public function getcontent();
}

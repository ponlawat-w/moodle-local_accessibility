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

defined('MOODLE_INTERNAL') or die();

/**
 * Base class for widgets
 */
abstract class widgetbase {
    /**
     * widget title
     * 
     * @var string $title
     */
    protected $title;
    /**
     * widget name
     *
     * @var string $name
     */
    protected $name;
    /**
     * widget column class in panel, this to indicates size of widget box in the card
     *
     * @var string $class
     */
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
     * Get active user's configuration of the widget
     * 
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

    /**
     * Set active user's configuration of the widget
     *
     * @param null|string $value Configuration value, null to remove entry from database
     * @return void
     */
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

    /**
     * Add CSS class to body tag
     *
     * @param string $classname
     * @return void
     */
    protected function addbodyclass($classname) {
        /**
         * @var \moodle_page $PAGE
         */
        global $PAGE;
        $PAGE->add_body_class($classname);
    }

    /**
     * Get HTML content of widget box in accessibility panel
     *
     * @return void
     */
    public abstract function getcontent();
}

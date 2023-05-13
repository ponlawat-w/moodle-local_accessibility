<?php

namespace local_accessibility\plugininfo;

use core\plugininfo\base;

defined('MOODLE_INTERNAL') || die();

class accessibility extends base {
    public function is_uninstall_allowed() {
        return true;
    }

    public function uninstall_cleanup() {
        /**
         * @var \moodle_database $DB
         */
        global $DB;

        $DB->delete_records('accessibility_userconfigs', ['optionname' => $this->name]);

        parent::uninstall_cleanup();
    }
}

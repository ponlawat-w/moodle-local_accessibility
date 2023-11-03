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
 * Plugin upgrade steps are defined here.
 *
 * @package     local_accessibility
 * @category    upgrade
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute local_accessibility upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_accessibility_upgrade($oldversion) {
    global $DB, $CFG;
    /** @var \moodle_database $DB */ $DB;

    $dbman = $DB->get_manager();

    $dbxmlpath = $CFG->dirroot . '/local/accessibility/db/install.xml';

    if ($oldversion < 2023050600) {
        $dbman->install_from_xmldb_file($dbxmlpath);
        upgrade_plugin_savepoint(true, 2023050600, 'local', 'accessibility');
    }
    if ($oldversion < 2023051302) {
        $DB->delete_records('accessibility_userconfigs', []);
        if (!$dbman->table_exists('accessibility_enabledoptions')) {
            $dbman->install_one_table_from_xmldb_file($dbxmlpath, 'accessibility_enabledoptions');
        }
        upgrade_plugin_savepoint(true, 2023051302, 'local', 'accessibility');
    }
    if ($oldversion < 2023071300) {
        if ($dbman->table_exists('accessibility_enabledoptions')) {
            $dbman->drop_table(new xmldb_table('accessibility_enabledoptions'));
        }
        if ($dbman->table_exists('accessibility_userconfigs')) {
            $dbman->drop_table(new xmldb_table('accessibility_userconfigs'));
        }
        $dbman->install_from_xmldb_file($dbxmlpath);
        upgrade_plugin_savepoint(true, 2023071300, 'local', 'accessibility');
    }
    if ($oldversion < 2023103002) {
        $oldtable = new xmldb_table('accessibility_enabledwidgets');
        $newtable = new xmldb_table('accessibility_widgets');
        if ($dbman->table_exists($oldtable)) {
            $dbman->rename_table($oldtable, $newtable->getName());
        }
        if (!$dbman->field_exists($newtable, 'enabled')) {
            $field = new xmldb_field('enabled', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 1, 'name');
            $dbman->add_field($newtable, $field);
        }
        require_once(__DIR__ . '/../lib.php');
        local_accessibility_addwidgetstodb(false);
        upgrade_plugin_savepoint(true, 2023103002, 'local', 'accessibility');
    }
    if ($oldversion < 2023110101) {
        $oldtable = new xmldb_table('accessibility_widgets');
        $newtable = new xmldb_table('local_accessibility_widgets');
        if ($dbman->table_exists($oldtable) && !$dbman->table_exists($newtable)) {
            $dbman->rename_table($oldtable, $newtable->getName());
        }
        $oldtable1 = new xmldb_table('accessibility_userconfigs');
        $oldtable2 = new xmldb_table('local_accessibility_userconfigs');
        $newtable = new xmldb_table('local_accessibility_configs');
        if ($dbman->table_exists($oldtable1) && !$dbman->table_exists($newtable)) {
            $dbman->rename_table($oldtable1, $newtable->getName());
        }
        if ($dbman->table_exists($oldtable2) && !$dbman->table_exists($newtable)) {
            $dbman->rename_table($oldtable2, $newtable->getName());
        }
        upgrade_plugin_savepoint(true, 2023110101, 'local', 'accessibility');
    }

    return true;
}

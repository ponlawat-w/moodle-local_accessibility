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
 * Test for admin management of widgets
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_accessibility;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/base.php');
require_once(__DIR__ . '/../lib.php');

/**
 * Test for admin management of widgets
 */
class adminwidgets_test extends testcase {
    /**
     * Test if installed widgets are added to database
     *
     * @coversNothing
     *
     * @return void
     */
    public function test_dbwidgetstable() {
        global $DB;
        /** @var \moodle_database $DB */ $DB;

        $this->resetAfterTest(true);

        $requiredwidgets = self::gettestwidgets();
        foreach ($requiredwidgets as $requiredwidget) {
            $this->assertNotFalse($DB->get_record('local_accessibility_widgets', ['name' => $requiredwidget]), $requiredwidget);
        }
    }

    /**
     * Test enabling and disabling widgets
     *
     * @covers ::local_accessibility_enablewidget
     * @covers ::local_accessibility_disablewidget
     * @covers ::local_accessibility_getenabledwidgets
     *
     * @return void
     */
    public function test_enablingwidgets() {
        $this->resetAfterTest(true);

        $this->assertGreaterThanOrEqual(count(self::gettestwidgets()), count(local_accessibility_getenabledwidgets()));

        try {
            local_accessibility_enablewidget('fontsize');
            $this->assertTrue(false);
        } catch (\Exception $ex) {
            $this->assertTrue(true);
        }

        local_accessibility_disablewidget('fontsize');
        try {
            local_accessibility_disablewidget('fontsize');
            $this->assertTrue(false);
        } catch (\Exception $ex) {
            $this->assertTrue(true);
        }

        $this->assertNotContains('fontsize', local_accessibility_getenabledwidgetnames());
        local_accessibility_enablewidget('fontsize');
        $this->assertContains('fontsize', local_accessibility_getenabledwidgetnames());
    }

    /**
     * Test sequencing affect of enabling and disabling widgets
     *
     * @covers ::local_accessibility_getenabledwidgetnames
     * @covers ::local_accessibility_enablewidget
     * @covers ::local_accessibility_disablewidget
     *
     * @return void
     */
    public function test_sequencing() {
        $this->resetAfterTest(true);

        foreach (local_accessibility_getenabledwidgetnames() as $widgetname) {
            local_accessibility_disablewidget($widgetname);
        }

        local_accessibility_enablewidget('fontsize');
        local_accessibility_enablewidget('textcolour');
        local_accessibility_enablewidget('fontface');
        local_accessibility_enablewidget('backgroundcolour');

        $enabledwidgets = array_values(local_accessibility_getenabledwidgetnames());
        $this->assertEquals('fontsize', $enabledwidgets[0]);
        $this->assertEquals('textcolour', $enabledwidgets[1]);
        $this->assertEquals('fontface', $enabledwidgets[2]);
        $this->assertEquals('backgroundcolour', $enabledwidgets[3]);

        local_accessibility_disablewidget('fontface');

        $enabledwidgets = array_values(local_accessibility_getenabledwidgetnames());
        $this->assertEquals('fontsize', $enabledwidgets[0]);
        $this->assertEquals('textcolour', $enabledwidgets[1]);
        $this->assertEquals('backgroundcolour', $enabledwidgets[2]);
    }

    /**
     * Test sequencing affect of enabling and disabling widgets
     *
     * @covers ::local_accessibility_getenabledwidgets
     * @covers ::local_accessibility_moveup
     * @covers ::local_accessibility_movedown
     *
     * @return void
     */
    public function test_moveupdown() {
        global $DB;
        /** @var \moodle_database $DB */ $DB;

        $this->resetAfterTest(true);

        foreach (local_accessibility_getenabledwidgetnames() as $widgetname) {
            local_accessibility_disablewidget($widgetname);
        }

        local_accessibility_enablewidget('fontsize');
        local_accessibility_enablewidget('textcolour');
        local_accessibility_enablewidget('fontface');
        local_accessibility_enablewidget('backgroundcolour');

        $enabledwidgets = array_values(local_accessibility_getenabledwidgets());

        local_accessibility_moveup($enabledwidgets[2]); // Moving fontface up.
        $enabledwidgets = array_values(local_accessibility_getenabledwidgets());
        $this->assertEquals('fontsize', $enabledwidgets[0]->name);
        $this->assertEquals('fontface', $enabledwidgets[1]->name);
        $this->assertEquals('textcolour', $enabledwidgets[2]->name);
        $this->assertEquals('backgroundcolour', $enabledwidgets[3]->name);

        local_accessibility_moveup($enabledwidgets[0]); // Moving fontsize up, should take no affects.
        $enabledwidgets = array_values(local_accessibility_getenabledwidgets());
        $this->assertEquals('fontsize', $enabledwidgets[0]->name);
        $this->assertEquals('fontface', $enabledwidgets[1]->name);
        $this->assertEquals('textcolour', $enabledwidgets[2]->name);
        $this->assertEquals('backgroundcolour', $enabledwidgets[3]->name);

        local_accessibility_movedown($enabledwidgets[0]); // Moving fontsize down.
        $enabledwidgets = array_values(local_accessibility_getenabledwidgets());
        $this->assertEquals('fontface', $enabledwidgets[0]->name);
        $this->assertEquals('fontsize', $enabledwidgets[1]->name);
        $this->assertEquals('textcolour', $enabledwidgets[2]->name);
        $this->assertEquals('backgroundcolour', $enabledwidgets[3]->name);

        local_accessibility_movedown($enabledwidgets[3]); // Moving backgroundcolour down, should take no affects.
        $enabledwidgets = array_values(local_accessibility_getenabledwidgets());
        $this->assertEquals('fontface', $enabledwidgets[0]->name);
        $this->assertEquals('fontsize', $enabledwidgets[1]->name);
        $this->assertEquals('textcolour', $enabledwidgets[2]->name);
        $this->assertEquals('backgroundcolour', $enabledwidgets[3]->name);
    }
}

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
 * Test save widget config API
 *
 * @package     local_accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_accessibility\external;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../webservice/tests/helpers.php');

/**
 * Test save widget config API
 */
final class savewidgetconfig_test extends \advanced_testcase {
    /**
     * Test user configuration via external API class
     *
     * @runInSeparateProcess
     * @runTestsInSeparateProcesses
     * @covers \local_accessibility\external\savewidgetconfig::execute
     * @covers \local_accessibility\widgets::getuserconfig
     *
     * @return void
     */
    public function test_savewidgetconfig_user(): void {
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $response = savewidgetconfig::execute('fontsize', '1.5');
        $response = \core_external\external_api::clean_returnvalue(savewidgetconfig::execute_returns(), $response);
        $this->assertTrue($response['success']);

        $widget = local_accessibility_getwidgetinstancebyname('fontsize');

        $this->setUser(null);
        $this->assertNull($widget->getuserconfig());

        $this->setUser($user);
        $this->assertEquals('1.5', $widget->getuserconfig());

        $response = savewidgetconfig::execute('fontsize');
        $response = \core_external\external_api::clean_returnvalue(savewidgetconfig::execute_returns(), $response);
        $this->assertTrue($response['success']);
        $this->assertNull($widget->getuserconfig());
    }

    /**
     * Test guest configuration via external API class
     *
     * @runInSeparateProcess
     * @runTestsInSeparateProcesses
     * @covers \local_accessibility\external\savewidgetconfig::execute
     * @covers \local_accessibility\widgets::getuserconfig
     *
     * @return void
     */
    public function test_savewidgetconfig_guest(): void {
        $this->resetAfterTest(true);

        $widget = local_accessibility_getwidgetinstancebyname('fontsize');

        $this->assertNull($widget->getuserconfig());

        $response = savewidgetconfig::execute('fontsize', '1.5');
        $response = \core_external\external_api::clean_returnvalue(savewidgetconfig::execute_returns(), $response);
        $this->assertTrue($response['success']);
        $this->assertEquals('1.5', $widget->getuserconfig());

        $this->setUser($this->getDataGenerator()->create_user());
        $this->assertNull($widget->getuserconfig());
    }
}

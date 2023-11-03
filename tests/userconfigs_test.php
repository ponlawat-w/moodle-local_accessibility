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
 * Test user widget configuration saving and loading
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
 * Test user widget configuration saving and loading
 */
class userconfigs_test extends testcase {
    /**
     * Test user configuration
     *
     * @covers ::local_accessibility_getwidgetinstancebyname
     * @covers \local_accessibility\widgets::setuserconfig
     * @covers \local_accessibility\widgets::getuserconfig
     *
     * @return void
     */
    public function test_userconfig() {
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $widget = local_accessibility_getwidgetinstancebyname('fontsize');
        $widget->setuserconfig('1.5');

        $this->setUser(null);
        $this->assertNull($widget->getuserconfig());

        $this->setUser($user);
        $this->assertEquals('1.5', $widget->getuserconfig());

        $widget->setuserconfig(null);
        $this->assertNull($widget->getuserconfig());
    }

    /**
     * Test guest configuration
     *
     * @covers ::local_accessibility_getwidgetinstancebyname
     * @covers \local_accessibility\widgets::setuserconfig
     * @covers \local_accessibility\widgets::getuserconfig
     *
     * @return void
     */
    public function test_guestconfig() {
        $this->resetAfterTest(true);

        $widget = local_accessibility_getwidgetinstancebyname('fontsize');

        $this->assertNull($widget->getuserconfig());

        $widget->setuserconfig('1.5');
        $this->assertEquals('1.5', $widget->getuserconfig());

        $this->setUser($this->getDataGenerator()->create_user());
        $this->assertNull($widget->getuserconfig());
    }
}

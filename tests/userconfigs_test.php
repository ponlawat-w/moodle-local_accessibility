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
final class userconfigs_test extends testcase {
    /**
     * Test user configuration
     *
     * @covers ::local_accessibility_getwidgetinstancebyname
     * @covers \local_accessibility\widgets::setuserconfig
     * @covers \local_accessibility\widgets::getuserconfig
     *
     * @return void
     */
    public function test_userconfig(): void {
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
    public function test_guestconfig(): void {
        $this->resetAfterTest(true);
        $this->setGuestUser();

        $widget = local_accessibility_getwidgetinstancebyname('fontsize');

        $this->assertNull($widget->getuserconfig());

        $widget->setuserconfig('1.5');
        $this->assertEquals('1.5', $widget->getuserconfig());

        $this->setUser($this->getDataGenerator()->create_user());
        $this->assertNull($widget->getuserconfig());
    }

    /**
     * Test that the configuration of the guest account is not shared between sessions
     *
     * The guest account is a real user record with a real id, so unless it is explicitly
     * detected as a guest, its configuration is stored in the database like any other user
     * and every guest visitor of the site ends up sharing (and overwriting) the same values.
     *
     * @covers ::local_accessibility_getwidgetinstancebyname
     * @covers \local_accessibility\widgets::setuserconfig
     * @covers \local_accessibility\widgets::getuserconfig
     *
     * @return void
     */
    public function test_guestconfig_notsharedbetweensessions(): void {
        global $DB, $USER;

        $this->resetAfterTest(true);

        $widget = local_accessibility_getwidgetinstancebyname('fontsize');

        // A visitor logged in with the guest account sets a configuration.
        $this->setGuestUser();
        $this->assertTrue(isguestuser());
        $this->assertNull($widget->getuserconfig());
        $widget->setuserconfig('1.5');
        $this->assertEquals('1.5', $widget->getuserconfig());

        $guestid = $USER->id;

        // Another visitor logs in with the same guest account in a brand new session.
        $this->setGuestUser();
        $this->assertNull($widget->getuserconfig());

        // The value must have been kept in the session, not in the database.
        $this->assertFalse($DB->record_exists('local_accessibility_configs', ['userid' => $guestid]));
    }
}

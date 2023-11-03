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
 * Common JS functions
 *
 * @module      local/accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {call} from 'core/ajax';

/**
 * Save user's widget config
 * @param {string} widget widget name
 * @param {string} configvalue value to config, null to remove config
 * @returns {Promise[]} promises
 */
export const saveWidgetConfig = (
    widget,
    configvalue
) => call([{
    methodname: 'local_accessibility_savewidgetconfig',
    args: {widget, configvalue}
}])[0];

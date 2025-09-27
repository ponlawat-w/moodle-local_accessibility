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
 * Letter spacing widget JS
 *
 * @module      accessibility/letterspacing
 * @copyright   2025 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import { initrangewidget } from 'local_accessibility/rangewidget';
import { saveWidgetConfig } from 'local_accessibility/common';

/**
 * Initialisation
 * @param {number|undefined|null} userdefault
 */
export const init = (userdefault = undefined) => {
    const classnames = [
        'accessibility-letterspacing--10',
        'accessibility-letterspacing-10',
        'accessibility-letterspacing-20',
        'accessibility-letterspacing-30',
        'accessibility-letterspacing-40',
        'accessibility-letterspacing-50'
    ];

    $(() => {
        const $body = $('body');

        initrangewidget('accessibility_letterspacing', async(size) => {
            $body.removeClass(classnames);
            if (parseFloat(size) === 0) {
                await saveWidgetConfig('letterspacing', null);
                return;
            }
            const classname = 'accessibility-letterspacing-' + Math.round(parseFloat(size) * 100).toString();
            if (classnames.indexOf(classname) < 0) {
                return;
            }
            $body.addClass(classname);
            await saveWidgetConfig('letterspacing', size);
        }, userdefault);
    });
};

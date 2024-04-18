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
 * Font size widget JS
 *
 * @module      accessibility/fontsize
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
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
        'accessibility-fontsize-050',
        'accessibility-fontsize-075',
        'accessibility-fontsize-125',
        'accessibility-fontsize-150',
        'accessibility-fontsize-175',
        'accessibility-fontsize-200'
    ];

    $(() => {
        const $body = $('body');

        initrangewidget('accessibility_fontsize', async(size) => {
            $body.removeClass(classnames);
            if (parseFloat(size) === 1.0) {
                await saveWidgetConfig('fontsize', null);
                return;
            }
            const classname = 'accessibility-fontsize-' + Math.round(parseFloat(size) * 100).toString().padStart(3, '0');
            if (classnames.indexOf(classname) < 0) {
                return;
            }
            $body.addClass(classname);
            await saveWidgetConfig('fontsize', size);
        }, userdefault);
    });
};

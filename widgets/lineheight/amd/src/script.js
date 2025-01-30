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
 * @module      accessibility/lineheight
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
        'accessibility-lineheight-50',
        'accessibility-lineheight-60',
        'accessibility-lineheight-70',
        'accessibility-lineheight-80',
        'accessibility-lineheight-90',
        'accessibility-lineheight-100',
        'accessibility-lineheight-110',
        'accessibility-lineheight-120',
        'accessibility-lineheight-130',
        'accessibility-lineheight-140',
        'accessibility-lineheight-150',
        'accessibility-lineheight-160',
        'accessibility-lineheight-170',
        'accessibility-lineheight-180',
        'accessibility-lineheight-190',
        'accessibility-lineheight-200',
        'accessibility-lineheight-210',
        'accessibility-lineheight-220',
        'accessibility-lineheight-230',
        'accessibility-lineheight-240',
        'accessibility-lineheight-250',
        'accessibility-lineheight-260',
        'accessibility-lineheight-270',
        'accessibility-lineheight-280',
        'accessibility-lineheight-290',
        'accessibility-lineheight-300'
    ];

    $(() => {
        const $body = $('body');

        initrangewidget('accessibility_lineheight', async(size) => {
            $body.removeClass(classnames);
            if (parseFloat(size) === 0) {
                await saveWidgetConfig('lineheight', null);
                return;
            }
            const classname = 'accessibility-lineheight-' + Math.round(parseFloat(size) * 100).toString();
            if (classnames.indexOf(classname) < 0) {
                return;
            }
            $body.addClass(classname);
            await saveWidgetConfig('lineheight', size);
        }, userdefault);
    });
};

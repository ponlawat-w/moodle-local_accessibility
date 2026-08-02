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
 * Link highlight widget JS
 *
 * @module      accessibility/linkhighlight
 * @copyright   2025 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import { saveWidgetConfig } from 'local_accessibility/common';

/**
 * Initialisation
 *
 * @param {string|number|undefined} data
 */
export const init = (data) => {
    $(() => {
        const $body = $('body');
        const $container = $('#accessibility_linkhighlight-container');
        if (!$container.length) {
            return;
        }
        const $btn = $container.find('.btn-toggler');
        if (!$btn.length) {
            return;
        }

        let userdata = data;

        const updatebtn = () => {
            $btn.html(userdata ? M.str.accessibility_linkhighlight.enabled : M.str.accessibility_linkhighlight.disabled);
            $btn.removeClass('btn-primary');
            $btn.removeClass('btn-light');
            $btn.addClass(userdata ? 'btn-primary' : 'btn-light');
        };
        updatebtn();

        $btn.on('click', async() => {
            userdata = userdata ? undefined : 1;
            if (userdata) {
                $body.addClass('accessibility-linkhighlight-enabled');
            } else {
                $body.removeClass('accessibility-linkhighlight-enabled');
            }
            updatebtn();
            await saveWidgetConfig('linkhighlight', userdata);
        });
    });
};

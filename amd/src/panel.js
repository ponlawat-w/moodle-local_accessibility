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
 * Default script for accessibility panel
 *
 * @module      local/accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

/**
 * Initialise accessibility panel
 */
export const init = () => {
    $(() => {
        const $button = $('#local-accessibility-buttoncontainer button');
        const $panel = $('.local-accessibility-panel');
        const $closebtn = $('#local-accessibility-closebtn');

        if (!$button.length || !$panel.length) {
            return;
        }

        $panel.hide();

        $button.on('click', () => {
            $panel.toggle();
        });

        window.addEventListener('click', e => {
            if ($button[0].contains(e.target) || $panel[0].contains(e.target)) {
                return;
            }
            if ($panel.css('display') !== 'none') {
                $panel.hide();
            }
        });

        window.addEventListener('keyup', e => {
            if ($panel.css('display') !== 'none' && e.key === 'Escape') {
                $panel.hide();
            }
        });

        if ($closebtn.length) {
            $closebtn.on('click', () => {
                $panel.hide();
            });
        }
    });
};

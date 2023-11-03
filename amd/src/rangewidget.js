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
 * Default script for range-selector widgets
 *
 * @module      local/accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

/**
 * Callback type after range value being changed
 *
 * @callback valueChangedCallback
 * @param {number} newvalue new value
 * @returns {void}
 */

/**
 * Initialise JS for a range-selector widget
 *
 * @param {string} name widget name
 * @param {valueChangedCallback} callback callback function when value changed
 * @param {string|number} userdefault default value of user
 * @returns {void}
 */
export const initrangewidget = (name, callback, userdefault = undefined) => {
    const $inputrange = $(`#${name}-input`);
    const $label = $(`#${name}-label`);
    const $btnup = $(`#${name}-btnup`);
    const $btndown = $(`#${name}-btndown`);
    const $btnreset = $(`#${name}-btnreset`);

    if (!$inputrange.length) {
        return;
    }

    const min = parseFloat($inputrange.attr('min'));
    const max = parseFloat($inputrange.attr('max'));
    const step = parseFloat($inputrange.attr('step'));
    const defaultvalue = parseFloat($inputrange.attr('data-default'));

    $inputrange.on('input', () => {
        if ($label.length) {
            $label.html($inputrange.val());
        }
    });

    $inputrange.on('change', () => {
        if ($label.length) {
            $label.html($inputrange.val());
        }
        if (callback) {
            callback(parseFloat($inputrange.val()));
        }
    });

    if ($btnup.length) {
        $btnup.on('click', () => {
            $inputrange.val(Math.min(max, parseFloat($inputrange.val()) + step));
            $inputrange.trigger('change');
        });
    }

    if ($btndown.length) {
        $btndown.on('click', () => {
            $inputrange.val(Math.max(min, parseFloat($inputrange.val()) - step));
            $inputrange.trigger('change');
        });
    }

    if ($btnreset.length) {
        $btnreset.on('click', () => {
            $inputrange.val(defaultvalue);
            $inputrange.trigger('change');
        });
    }

    if (userdefault) {
        $inputrange.val(parseFloat(userdefault));
        $inputrange.trigger('change');
    }
};

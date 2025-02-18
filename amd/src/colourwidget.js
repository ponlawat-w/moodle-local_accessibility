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
 * Default script for colourpicker widgets
 *
 * @module      local/accessibility
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {saveWidgetConfig} from './common';
import $ from 'jquery';

/**
 * Function to initialise a colour-picker widget
 * @param {string} widget plugin full name
 * @param {string} savewidgetname widget name to be saved as user config
 * @param {string} stylename css attribute name to be applied the colour value
 * @param {string} bodyclassname class name of default value in body tag
 * @param {string} selector css selector of affected elements
 */
export const init = (widget, savewidgetname, stylename, bodyclassname = undefined, selector = 'body, body *') => {
    $(() => {
        const revokedefault = () => {
            if (bodyclassname) {
                $('body').removeClass(bodyclassname);
            }
        };

        const defaultattrname = `data-default-${stylename}`;
        const $container = $(`#${widget}-container`);
        if (!$container.length) {
            return;
        }

        const $input = $container.find(`input[name=color]`);
        if (!$input.length) {
            return;
        }
        $input.on('change input', async() => {
            const colour = $input.val();
            if (!colour) {
                return;
            }
            if (!/#[0-9a-f]{6}/gi.exec(colour)) {
                return;
            }
            revokedefault();
            for (const $element of [...$(selector)].map(e => $(e))) {
                if (!$element.attr(defaultattrname)) {
                    $element.attr(defaultattrname, $element.css(stylename));
                }
                $element.css(stylename, colour);
            }
            await saveWidgetConfig(savewidgetname, colour);
        });

        const $resetbtn = $container.find(`.${widget}-resetbtn`);
        if ($resetbtn.length) {
            $resetbtn.on('click', async() => {
                $input.val('');
                revokedefault();
                for (const element of [...$(selector)]) {
                    const $element = $(element);
                    const defaultcolour = $element.attr(defaultattrname) ?? '';
                    $element.css(stylename, defaultcolour);
                    $element.removeAttr(defaultattrname);
                }
                await saveWidgetConfig(savewidgetname, null);
            });
        }
    });
};

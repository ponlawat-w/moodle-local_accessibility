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
 * Text alignment widget JS
 *
 * @module      accessibility/textalignment
 * @copyright   2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import { saveWidgetConfig } from 'local_accessibility/common';

/**
 * Initialisation
 */
export const init = () => {
    $(() => {
        const $body = $('body');
        const $container = $('#accessibility_textalignment-container');
        if (!$container.length) {
            return;
        }

        const $optionbuttons = $container.find('.accessibility_textalignment-optionbtn');
        const options = [...$optionbuttons]
            .map(x => $(x).attr('data-value'))
            .filter(x => x)
            .map(x => 'accessibility-textalignment-' + x);
        if (!options.length) {
            return;
        }

        $optionbuttons.on('click', async(e) => {
            const value = $(e.currentTarget).attr('data-value');
            const classname = 'accessibility-textalignment-' + value;
            $body.removeClass(options);
            $body.addClass(classname);
            $optionbuttons.removeClass('active');
            $(e.currentTarget).addClass('active');
            await saveWidgetConfig('textalignment', value);
        });

        const $resetbutton = $container.find('.accessibility_textalignment-resetbtn');
        if ($resetbutton.length) {
            $resetbutton.on('click', async() => {
                $body.removeClass(options);
                $optionbuttons.removeClass('active');
                await saveWidgetConfig('textalignment', null);
            });
        }
    });
};

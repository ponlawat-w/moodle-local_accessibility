import { saveWidgetConfig } from './common';
import $ from 'jquery';

export const init = (widget, savewidgetname, stylename, bodyclassname = undefined) => {
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

        const $picker = $container.find(`#${widget}-picker`);
        if (!$picker.length) {
            return;
        }
        $picker.on('change input propertychange', async() => {
            const colour = $picker.val();
            if (!colour) {
                return;
            }
            if (!/#[0-9a-f]{6}/gi.exec(colour)) {
                return;
            }
            revokedefault();
            for (const element of [...$('*')]) {
                const $element = $(element);
                if (!$element.attr(defaultattrname)) {
                    $element.attr(defaultattrname, $element.css(stylename));
                }
                $element.css(stylename, colour);
            }
            await saveWidgetConfig(savewidgetname, colour);
        });

        const $img = $container.find('img.colourdialogue');
        if ($img.length) {
            $img.on('click', () => {
                $picker.trigger('change');
            });
        }

        const $resetbtn = $container.find(`.${widget}-resetbtn`);
        if ($resetbtn.length) {
            $resetbtn.on('click', async() => {
                $picker.val('');
                revokedefault();
                for (const element of [...$('*')]) {
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

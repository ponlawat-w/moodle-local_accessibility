import $ from 'jquery';
import { saveOptionConfig } from 'local_accessibility/common';

export const init = () => {
    $(() => {
        const $body = $('body');
        const $container = $('#accessibility_fontface-container');
        if (!$container.length) {
            return;
        }

        const $classbuttons = $container.find('.accessibility_fontface-classbtn');
        const classes = [...$classbuttons].map(x => $(x).attr('data-value')).filter(x => x).map(x => 'accessibility-fontface-' + x);
        if (!classes.length) {
            return;
        }

        $classbuttons.on('click', async(e) => {
            const fontfacename = $(e.target).attr('data-value');
            const classname = 'accessibility-fontface-' + fontfacename;
            $body.removeClass(classes);
            $body.addClass(classname);
            await saveOptionConfig('fontface', fontfacename);
        });

        const $resetbutton = $container.find('.accessibility_fontface-resetbtn');
        if ($resetbutton.length) {
            $resetbutton.on('click', async() => {
                $body.removeClass(classes);
                await saveOptionConfig('fontface', null);
            });
        }
    });
};

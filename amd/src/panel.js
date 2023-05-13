import $ from 'jquery';

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

import $ from 'jquery';

export const initoptionrange = (name, callback, userdefault = undefined) => {
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

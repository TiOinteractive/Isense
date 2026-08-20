/**
 * Front-end formularzy (modul Form).
 *
 * Dwie rzeczy poza zwyklym submitem AJAX:
 *  1. Kaskada widocznosci pol warunkowych (data-parent / data-parent-options),
 *     z dowolna glebokoscia zagniezdzenia.
 *  2. Wysylka przez FormData, zeby przeszly pliki. Globalny ajaxCall() nie
 *     nadaje sie — nie ustawia processData/contentType i jest wspoldzielony
 *     przez wszystkie moduly, wiec ma tu wlasna, lokalna wersje.
 */

/**
 * Pokaz/ukryj jedno pole. Ukrycie oznacza takze `disabled` i wyczyszczenie
 * wartosci — dzieki temu FormData i $_FILES w ogole nie zobacza tych pol,
 * czyli dokladnie tak, jak liczy je walidacja serwerowa.
 */
function formSetVisible($box, show) {
    $box.toggleClass('h-cond', !show).toggle(show);
    $box.find('input, select, textarea').prop('disabled', !show);
    if (!show) {
        $box.find('input[type=text], input[type=number], input[type=email], input[type=tel], textarea').val('');
        $box.find('select').val('');
        $box.find('input[type=checkbox], input[type=radio]').prop('checked', false);
        $box.find('input[type=file]').val('');
        $box.find('.error').removeClass('error');
        $box.find('.error-info').remove();
    }
}

/**
 * Przelicz widocznosc wszystkich pol warunkowych w formularzu.
 *
 * Petla „do ustabilizowania" obsluguje dowolna glebokosc zagniezdzenia bez
 * budowania drzewa (Temat -> Dostawa -> Adres). W praktyce zbiega sie w jednym
 * przebiegu, bo rodzic stoi w DOM przed dzieckiem; guard chroni przed
 * zapetleniem na uszkodzonych danych.
 */
function formApplyConditions($form) {
    var changed = true;
    var guard = 0;
    while (changed && guard++ < 10) {
        changed = false;
        $form.find('.field-box[data-parent]').each(function () {
            var $box = $(this);
            var $parent = $form.find('.field-box[data-field="' + $box.data('parent') + '"]');
            var parentVisible = $parent.length > 0 && !$parent.hasClass('h-cond');
            var allowed = String($box.data('parent-options') || '').split(',').filter(Boolean);
            var val = parentVisible ? ($parent.find('select').val() || '') : '';
            var show = parentVisible && val !== '' && allowed.indexOf(String(val)) !== -1;

            if (show === $box.hasClass('h-cond')) {
                changed = true;
            }
            formSetVisible($box, show);
        });
    }
}

/* Wysylka multipart. Odpowiedz trafia do window[response.callback]. */
function formAjaxSend(url, formData, $form) {
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data: formData,
        processData: false, // nie serializuj FormData
        contentType: false  // przegladarka sama ustawi multipart + boundary
    })
    .done(function (response) {
        if (response && response.callback && window[response.callback]) {
            window[response.callback](response);
        }
    })
    .fail(function (xhr) {
        // Najczestsza przyczyna: przekroczony post_max_size — PHP gubi wtedy
        // $_POST, Home::index() renderuje strone HTML i parsowanie JSON pada.
        $form.removeClass('sending block').addClass('error');
        $form.find('.form-result').remove();
        $form.find('.field-box.submit').before(
            $('<p></p>').addClass('form-result').text($form.data('msg-error') || 'Nie udalo sie wyslac formularza.')
        );
        console.log('form submit failed', xhr.status);
    });
}

$(function () {
    $('form.form').each(function () {
        formApplyConditions($(this));
    });

    $(document).on('change', 'form.form select', function () {
        formApplyConditions($(this).closest('form.form'));
    });

    $('form.form').on('submit', function (e) {
        e.preventDefault();
        var $f = $(this);
        if ($f.hasClass('block')) {
            return;
        }
        $f.removeClass('error success');
        $f.find('.error').removeClass('error');
        $f.find('.error-info').remove();
        $f.find('.form-result').remove();

        // Pola ukryte sa juz `disabled`, wiec FormData je pomija.
        var data = new FormData(this);
        $f.addClass('sending block');
        formAjaxSend($f.attr('action'), data, $f);
    });
});

function formCallback(response) {
    var $wrap = $('.form-' + response.form);
    $wrap.removeClass('sending');
    setTimeout(function () {
        $wrap.removeClass('block');
    }, 600);

    if (response.result) {
        $wrap.addClass('success');
        $wrap.find('input[type=text], input[type=number], textarea, select').val('');
        $wrap.find('input[type=checkbox]').prop('checked', false);
        $wrap.find('input[type=file]').val('');
        $wrap.filter('form').each(function () {
            formApplyConditions($(this));
        });
    } else {
        $wrap.addClass('error');
    }

    if (response.msg) {
        $wrap.find('.field-box.submit').before($('<p></p>').addClass('form-result').text(response.msg));
    }

    if (response.errors) {
        $.each(response.errors, function (label, error) {
            // Walidacja zwraca klucz `field_5`, ale input pliku nazywa sie `field_5[]`.
            var selector = 'input[name="' + label + '"],'
                         + 'input[name="' + label + '[]"],'
                         + 'textarea[name="' + label + '"],'
                         + 'select[name="' + label + '"]';
            var $el = $wrap.find(selector);
            $el.parent().addClass('error');
            $el.after($('<span></span>').addClass('error-info').text(error));
        });
    }
}

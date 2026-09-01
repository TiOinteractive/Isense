/**
 * Front-end formularzy (modul Form).
 *
 * Poza zwyklym submitem AJAX:
 *  1. Kaskada widocznosci pol warunkowych (data-parent / data-parent-options),
 *     z dowolna glebokoscia zagniezdzenia.
 *  2. Wysylka przez FormData, zeby przeszly pliki. Globalny ajaxCall() nie
 *     nadaje sie — nie ustawia processData/contentType i jest wspoldzielony
 *     przez wszystkie moduly, wiec ma tu wlasna, lokalna wersje.
 *  3. Walidacja po stronie przegladarki oparta o Constraint Validation API
 *     (required / type=email / pattern z Helpers/form_field_helper.php).
 *
 * DLACZEGO WLASNE KOMUNIKATY, A NIE NATYWNE DYMKI: dymek przegladarki znika po
 * kliknieciu, nie da sie go ostylowac ani przeczytac ponownie czytnikiem ekranu,
 * a jego jezyk idzie za przegladarka, nie za strona. Dlatego na starcie wlaczamy
 * `noValidate` i rysujemy dokladnie te same komunikaty co bledy z serwera
 * (.field-box.error + .error-info + aria-invalid + aria-describedby).
 * `noValidate` ustawiamy Z JS-a, a nie w HTML — przy wylaczonym JS zostaje
 * natywna walidacja jako siatka bezpieczenstwa. Walidacja serwerowa
 * (Libraries/Form::ajax()) dziala niezaleznie od obu.
 */

/**
 * Pokaz/ukryj jedno pole. Ukrycie oznacza takze `disabled` i wyczyszczenie
 * wartosci — dzieki temu FormData i $_FILES w ogole nie zobacza tych pol,
 * czyli dokladnie tak, jak liczy je walidacja serwerowa.
 */
function formSetVisible($box, show) {
    $box.toggleClass('h-cond', !show).toggle(show);
    $box.find('input, select, textarea').prop('disabled', !show);
    // Pole warunkowe dostaje `required` dopiero, gdy jest widoczne. Gdyby atrybut
    // siedzial w HTML od poczatku, przegladarka bez JS-a odmowilaby wyslania
    // formularza z powodu ukrytego pola, ktorego nie ma jak pokazac.
    $box.find('[data-required]').prop('required', show);
    if (!show) {
        $box.find('input[type=text], input[type=number], input[type=email], input[type=tel], textarea').val('');
        $box.find('select').val('');
        $box.find('input[type=checkbox], input[type=radio]').prop('checked', false);
        $box.find('input[type=file]').val('');
        formClearError($box.find('input, select, textarea'));
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

/* --- Stany bledu pojedynczego pola ------------------------------------- */

/**
 * Element, ktory dostaje klase `error` (czerwona ramka). Dla checkboxa jest to
 * otaczajacy <label> — patrz assets/isense/css/form.css.
 */
function formErrorHost($el) {
    return $el.parent();
}

/* Oznacz pole bledem i podepnij komunikat przez aria-describedby. */
function formSetError($el, message) {
    var el = $el.get(0);
    if (!el) {
        return;
    }
    formClearError($el);

    var id = (el.id || el.name || 'field') + '-error';
    $el.attr('aria-invalid', 'true');
    // Zachowujemy istniejacy opis pola, jesli kiedys dojdzie.
    var described = ($el.attr('aria-describedby') || '').split(/\s+/).filter(Boolean);
    if (described.indexOf(id) === -1) {
        described.push(id);
    }
    $el.attr('aria-describedby', described.join(' '));

    formErrorHost($el).addClass('error');
    $el.after($('<span></span>').addClass('error-info').attr('id', id).text(message));
}

/* Zdejmij stan bledu z pola (lub ze zbioru pol). */
function formClearError($els) {
    $els.each(function () {
        var $el = $(this);
        var id = (this.id || this.name || 'field') + '-error';
        $el.removeAttr('aria-invalid');
        var described = ($el.attr('aria-describedby') || '').split(/\s+/)
                .filter(function (v) { return v && v !== id; });
        if (described.length) {
            $el.attr('aria-describedby', described.join(' '));
        } else {
            $el.removeAttr('aria-describedby');
        }
        formErrorHost($el).removeClass('error');
        $el.siblings('.error-info').remove();
    });
    // Bledy niepowiazane z konkretnym polem (captcha, laczny rozmiar zalacznikow)
    // wstawia formCallback() poza polem — te sprzatamy razem z reszta.
    return $els;
}

/**
 * Sprawdz jedno pole. Zwraca true, gdy poprawne.
 *
 * Komunikaty biora sie z atrybutow data-msg-* formularza (jezyk strony),
 * a przy niezgodnosci z `pattern` — z `title` pola, o ile jest ustawiony.
 */
function formValidateField(el) {
    var $el = $(el);
    var $form = $el.closest('form');
    if (el.disabled || el.type === 'hidden' || el.name === 'field_h') {
        return true;
    }

    var message = '';
    if (el.willValidate && !el.checkValidity()) {
        if (el.validity.valueMissing) {
            message = el.type === 'checkbox'
                    ? ($form.data('msg-checkbox') || $form.data('msg-required') || el.validationMessage)
                    : ($form.data('msg-required') || el.validationMessage);
        } else {
            message = el.title || $form.data('msg-invalid') || el.validationMessage;
        }
    }

    // Limit liczby plikow — ta sama granica, ktora egzekwuje Libraries/Form::ajax().
    if (!message && el.type === 'file' && el.files) {
        var max = parseInt($el.attr('data-max-files'), 10);
        if (max > 0 && el.files.length > max) {
            message = String($form.data('msg-files') || 'Max {0}').replace('{0}', max);
        }
    }

    if (message) {
        formSetError($el, message);
        return false;
    }
    formClearError($el);
    return true;
}

/* Sprawdz caly formularz; ustawia fokus na pierwszym bledzie. */
function formValidate($form) {
    var invalid = null;
    $form.find('input, select, textarea').each(function () {
        if (!formValidateField(this) && !invalid) {
            invalid = this;
        }
    });
    if (invalid) {
        invalid.focus();
        if (invalid.scrollIntoView) {
            invalid.scrollIntoView({block: 'center', behavior: 'smooth'});
        }
        return false;
    }
    return true;
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
        // Klasa stanu musi trafic takze na zewnetrzny .isense-form — to on nosi
        // selektor kolorujacy komunikat. Na samym <form> nic by nie pokolorowala.
        // `.closest()` jest puste dla szablonu form1.php, wiec `.add()` zostawia
        // wtedy sam formularz i nic sie nie psuje.
        $form.removeClass('sending block');
        $form.add($form.closest('.isense-form')).removeClass('success').addClass('error');
        $form.find('.form-result').remove();
        $form.find('.field-box.submit').before(
            formResultMessage($form.data('msg-error') || 'Nie udalo sie wyslac formularza.', false)
        );
        console.log('form submit failed', xhr.status);
    });
}

/**
 * Komunikat zbiorczy nad przyciskiem wysylki.
 *
 * role="alert" dla bledu (czytnik przerywa i czyta od razu — uzytkownik musi
 * wiedziec, ze wysylka sie nie udala) i role="status" dla sukcesu (czyta po
 * skonczeniu biezacej wypowiedzi).
 */
function formResultMessage(text, ok) {
    return $('<p></p>')
            .addClass('form-result')
            .attr('role', ok ? 'status' : 'alert')
            .attr('tabindex', '-1')
            .text(text);
}

$(function () {
    $('form.form').each(function () {
        // Natywne dymki off — komunikaty rysujemy sami (patrz naglowek pliku).
        this.noValidate = true;
        formApplyConditions($(this));
    });

    $(document).on('change', 'form.form select', function () {
        formApplyConditions($(this).closest('form.form'));
    });

    // Walidacja PO interakcji: pole sprawdzamy dopiero, gdy uzytkownik je opusci,
    // a nie w trakcie pisania — inaczej „Jan" byloby czerwone przy pierwszej
    // literze. Gdy blad juz wisi, kazda zmiana probuje go zdjac od razu.
    $(document).on('blur', 'form.form input, form.form select, form.form textarea', function () {
        formValidateField(this);
    });
    $(document).on('input change', 'form.form input, form.form select, form.form textarea', function () {
        if ($(this).attr('aria-invalid')) {
            formValidateField(this);
        }
    });

    /* reCAPTCHA v3 wiesza wlasny handler na przycisku i dopiero jej callback
       wywoluje submit(). Bez tego sprawdzenia niepoprawny formularz najpierw
       zuzywalby token, a bledy pokazalby dopiero po jego weryfikacji.
       Faza przechwytywania (`true`) jest tu konieczna — delegacja jQuery
       zadzialalaby juz PO handlerze Google. */
    document.addEventListener('click', function (e) {
        var btn = e.target && e.target.closest ? e.target.closest('button.g-recaptcha') : null;
        if (!btn) {
            return;
        }
        var $f = $(btn).closest('form.form');
        if ($f.length && !formValidate($f)) {
            e.preventDefault();
            e.stopPropagation();
        }
    }, true);

    $('form.form').on('submit', function (e) {
        e.preventDefault();
        var $f = $(this);
        if ($f.hasClass('block')) {
            return;
        }
        $f.removeClass('error success');
        $f.find('.form-result').remove();

        if (!formValidate($f)) {
            // Fokus i komunikaty ustawil formValidate() — nic nie wysylamy.
            return;
        }
        $f.find('.error').removeClass('error');
        $f.find('.error-info').remove();

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

    // Zdejmujemy OBIE klasy stanu, zanim nadamy wlasciwa. Handler `submit`
    // czysci je tylko na samym <form>, a $wrap lapie takze zewnetrzny
    // <div class="form form-N isense-form"> — to on nosi klase `isense-form`,
    // od ktorej zalezy kolor komunikatu. Bez tego reset div zostawal na zawsze
    // w stanie `error` i kazda kolejna UDANA wysylka miala czerwona ramke.
    $wrap.removeClass('success error');

    if (response.result) {
        $wrap.addClass('success');
        formClearError($wrap.find('input, select, textarea'));
        $wrap.find('input[type=text], input[type=email], input[type=tel], input[type=number], textarea, select').val('');
        $wrap.find('input[type=checkbox]').prop('checked', false);
        $wrap.find('input[type=file]').val('');
        $wrap.filter('form').each(function () {
            formApplyConditions($(this));
        });
    } else {
        $wrap.addClass('error');
    }

    if (response.msg) {
        var $msg = formResultMessage(response.msg, !!response.result);
        $wrap.find('.field-box.submit').before($msg);
        // Fokus na komunikacie: czytnik i klawiatura trafiaja na potwierdzenie
        // wysylki bez szukania go po stronie.
        if (response.result) {
            $msg.get(0).focus();
        }
    }

    if (response.errors) {
        $.each(response.errors, function (label, error) {
            // Walidacja zwraca klucz `field_5`, ale input pliku nazywa sie `field_5[]`.
            var selector = 'input[name="' + label + '"],'
                         + 'input[name="' + label + '[]"],'
                         + 'textarea[name="' + label + '"],'
                         + 'select[name="' + label + '"]';
            var $el = $wrap.find(selector);
            if ($el.length) {
                formSetError($el, error);
            }
        });
    }
}

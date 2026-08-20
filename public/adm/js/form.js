/**
 * Panel admina — konfiguracja pol formularza (modul Form).
 *
 * Trzy mechanizmy poza podstawowym dodawaniem/usuwaniem pol:
 *  1. Przelaczanie sekcji konfiguracji zaleznie od wybranego typu pola.
 *  2. Repeater opcji selecta (wzorzec <template> + placeholder __i__).
 *  3. Sekcja „Pokaz gdy" — listy rodzicow i ich opcji budowane z BIEZACEGO DOM,
 *     po KLUCZACH LOKALNYCH (data-key / data-opt-key), zeby pole dodane przed
 *     chwila AJAX-em — jeszcze bez ID w bazie — dalo sie od razu wskazac
 *     jako rodzica. Przeklad klucz -> ID robi FormModel przy zapisie.
 *
 * Wszystkie bindy ida przez delegacje na document, bo wiersze pol dokladane sa
 * asynchronicznie.
 */

/* Etykieta wiersza pola na listach rodzicow, np. "#3 — Temat". */
function formRowLabel($row) {
    var name = $row.find('input.field-name').first().val() || '';
    return '#' + $row.data('no') + (name ? ' — ' + name : '');
}

/* Przebudowa listy „Pokaz gdy: [pole]" we WSZYSTKICH wierszach. */
function formRefreshParentSelects() {
    var $box = $('.form .form-fields-box');
    var $rows = $box.find('.form-group');
    var none = $box.data('lang-none') || '';

    // 1) Wszystkie wiersze typu `select`, w kolejnosci DOM.
    var parents = [];
    $rows.each(function (idx) {
        var $r = $(this);
        if ($r.find('select.field-type').val() !== 'select') { return; }
        parents.push({idx: idx, key: String($r.data('key')), label: formRowLabel($r)});
    });

    // 2) Odbudowa kazdego selecta rodzica.
    $rows.each(function (idx) {
        var $row = $(this);
        var $ps = $row.find('select.parent-select');
        if (!$ps.length) { return; }

        // Przy pierwszym renderze stan bierzemy z data-selected, potem z biezacego wyboru.
        var current = $ps.data('bound') ? ($ps.val() || '') : String($ps.data('selected') || '');
        $ps.data('bound', true).empty().append($('<option></option>').val('').text(none));

        parents.forEach(function (p) {
            if (p.key === String($row.data('key'))) { return; }   // nie sam siebie
            if (p.idx >= idx) { return; }                         // tylko selecty PRZED tym polem
            $ps.append($('<option></option>').val(p.key).text(p.label));
        });

        $ps.val(current);
        // Rodzic usuniety albo o zmienionym typie -> warunek sam sie kasuje.
        if ($ps.val() === null) { $ps.val(''); }
        formRefreshParentOptions($row);
    });
}

/* Przebudowa listy „Pokaz gdy: [opcje]" dla JEDNEGO wiersza. */
function formRefreshParentOptions($row) {
    var $pv = $row.find('select.parent-values');
    if (!$pv.length) { return; }

    var parentKey = $row.find('select.parent-select').val() || '';
    var selected = $pv.data('bound')
        ? ($pv.val() || [])
        : String($pv.data('selected') || '').split(',').filter(Boolean);
    $pv.data('bound', true).empty();

    $row.find('.parent-values-row').toggle(!!parentKey);
    if (!parentKey) { return; }

    var $parent = $('.form .form-fields-box .form-group[data-key="' + parentKey + '"]');
    $parent.find('[data-opt-items] [data-opt-row]').each(function () {
        var $o = $(this);
        var label = $o.find('input.opt-name').first().val() || '(…)';
        // Wartoscia jest KLUCZ, nie etykieta — zmiana etykiety nie gubi zaznaczenia.
        $pv.append($('<option></option>').val(String($o.data('opt-key'))).text(label));
    });
    $pv.val(selected);
}

/* Pokaz/ukryj sekcje konfiguracji zaleznie od typu pola. */
function formToggleTypeCfg($row) {
    var type = $row.find('select.field-type').val();
    $row.find('.field-type-cfg').each(function () {
        $(this).toggle(String($(this).data('for')).split(',').indexOf(type) !== -1);
    });
}

$(function () {
    $('.form .form-fields-box .form-group').each(function () { formToggleTypeCfg($(this)); });
    formRefreshParentSelects();

    $('.form .add-field').on('click', function (e) {
        e.preventDefault();
        let no = 0;
        $('.form .form-fields-box .form-group').each(function () {
            let n = parseInt($(this).data('no'));
            if (n >= no) {
                no = n + 1;
            }
        });
        let data = {no: no};
        ajaxCall($(this).attr('href'), data, 'formAddField');
    });

    $(document).on('click', '.form .delete-field', function (e) {
        e.preventDefault();
        let field = this;
        $.confirm({
            title: $(field).data('title'),
            content: $(field).data('message'),
            type: 'red',
            autoClose: 'cancelAction|8000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(field).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        $(field).parents('.form-group').remove();
                        formRefreshParentSelects();
                    }
                },
                cancelAction: {
                    text: $(field).data('btn-cancel')
                }
            }
        });
    });

    // Typ pola -> inne sekcje konfiguracji i inna lista rodzicow.
    $(document).on('change', '.form .form-fields-box select.field-type', function () {
        formToggleTypeCfg($(this).closest('.form-group'));
        formRefreshParentSelects();
    });

    // Zmiana rodzica -> przebuduj liste jego opcji.
    $(document).on('change', '.form .form-fields-box select.parent-select', function () {
        formRefreshParentOptions($(this).closest('.form-group'));
    });

    // Zmiana nazwy pola / etykiety opcji -> odswiez etykiety na listach.
    var formLabelTimer = null;
    $(document).on('input', '.form .form-fields-box input.field-name, .form .form-fields-box input.opt-name', function () {
        clearTimeout(formLabelTimer);
        formLabelTimer = setTimeout(formRefreshParentSelects, 250);
    });

    // Repeater opcji. Wlasne atrybuty data-opt-* (nie data-repeater*) i BRAK klasy
    // .order-box na kontenerze — inaczej globalne $(".order-box").sortable()
    // zrobiloby zagniezdzony drag&drop.
    $(document).on('click', '.form [data-opt-add]', function (e) {
        e.preventDefault();
        var $rep = $(this).closest('[data-opt-repeater]');
        var $items = $rep.find('[data-opt-items]');
        var tpl = $rep.find('template[data-opt-template]')[0];
        if (!tpl) { return; }

        var counter = ($rep.data('opt-counter') || $items.find('[data-opt-row]').length) + 1;
        $rep.data('opt-counter', counter);
        $items.append($(tpl.innerHTML.replace(/__i__/g, 'new' + counter)));
        formRefreshParentSelects();
    });

    $(document).on('click', '.form [data-opt-remove]', function (e) {
        e.preventDefault();
        $(this).closest('[data-opt-row]').remove();
        formRefreshParentSelects();
    });

    // Reorder pol zmienia „kto stoi przed kim" — odswiez po fixOrder().
    $('.form .form-fields-box').on('sortstop', function () {
        setTimeout(formRefreshParentSelects, 0);
    });
});

function formAddField(obj) {
    if (obj.status) {
        $('.form .form-fields-box').append(obj.html);
        fixOrder($('.form .order-box'));
        var $row = $('.form .form-fields-box .form-group').last();
        formToggleTypeCfg($row);
        formRefreshParentSelects();
        $('html, body').animate({
            scrollTop: $row.offset().top - 20
        }, 500);
    }
}

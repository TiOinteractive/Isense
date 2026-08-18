<?php
$cfg = [
    'title'  => 'Isense.SectionContact',
    'fields' => [
        ['name' => 'left_heading', 'label' => 'Nagłówek (lewa kolumna)'],
        ['name' => 'address', 'label' => 'Adres (może być wielolinijkowy)', 'type' => 'textarea'],
        ['name' => 'phone', 'label' => 'Telefon'],
        ['name' => 'email', 'label' => 'E-mail'],
        ['name' => 'hours', 'label' => 'Godziny otwarcia (wielolinijkowe)', 'type' => 'textarea'],
        ['name' => 'map', 'label' => 'Mapa — adres osadzenia (Google Maps embed URL)', 'type' => 'textarea'],
        ['name' => 'map_link', 'label' => 'Link „Otwórz w Google Maps" pod mapą (opcjonalnie)'],
        ['name' => 'right_heading', 'label' => 'Nagłówek (formularz)'],
        ['name' => 'recipient', 'label' => 'E-mail odbiorcy zgłoszeń (puste = adres z Ustawień serwisu)'],
        ['name' => 'consent_text', 'label' => 'Treść zgody RODO w formularzu (puste = bez pola zgody)', 'type' => 'textarea'],
    ],
    'lists' => [[
        'key' => 'subjects', 'label' => 'Tematy formularza', 'add' => '+ dodaj temat',
        'item' => [['name' => 'label', 'label' => 'Temat']],
    ]],
];
include __DIR__ . '/_form.php';

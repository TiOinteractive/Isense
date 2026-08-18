<?php
$cfg = [
    'title'  => 'Isense.SectionPickup',
    'fields' => [
        ['name' => 'badge', 'label' => 'Etykieta (badge)'],
        ['name' => 'heading', 'label' => 'Nagłówek (lewa kolumna)'],
        ['name' => 'lead', 'label' => 'Wprowadzenie', 'type' => 'textarea'],
        ['name' => 'form_heading', 'label' => 'Nagłówek formularza (prawa kolumna)'],
        ['name' => 'recipient', 'label' => 'E-mail odbiorcy zgłoszeń (puste = adres z Ustawień serwisu)'],
    ],
    'lists' => [[
        'key' => 'benefits', 'label' => 'Korzyści (lewa kolumna)', 'add' => '+ dodaj korzyść',
        'item' => [
            ['name' => 'icon', 'label' => 'Ikona (nazwa)'],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'text', 'label' => 'Opis', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

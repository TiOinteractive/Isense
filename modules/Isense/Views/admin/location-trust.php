<?php
$cfg = [
    'title'  => 'Isense.SectionLocation',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek (można HTML/<br>)'],
        ['name' => 'lead', 'label' => 'Wstęp (pogrubiony)', 'type' => 'textarea'],
        ['name' => 'body', 'label' => 'Treść (akapity oddziel pustą linią)', 'type' => 'textarea'],
        ['name' => 'closing', 'label' => 'Zakończenie (pogrubione)', 'type' => 'textarea'],
        ['name' => 'cta_label', 'label' => 'Tekst przycisku'],
        ['name' => 'cta_url', 'label' => 'Link przycisku (URL)'],
    ],
    'lists' => [[
        'key' => 'options', 'label' => 'Karty lokalizacji/opcji', 'add' => '+ dodaj kartę',
        'item' => [
            ['name' => 'image', 'label' => 'Obraz (ścieżka)'],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'description', 'label' => 'Opis', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

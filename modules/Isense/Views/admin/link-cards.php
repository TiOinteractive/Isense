<?php
$cfg = [
    'title'  => 'Isense.SectionLinkCards',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek (opcjonalnie)'],
        ['name' => 'lead', 'label' => 'Wprowadzenie (opcjonalnie)', 'type' => 'textarea'],
        ['name' => 'columns', 'label' => 'Liczba kolumn', 'type' => 'select', 'options' => ['2' => '2', '3' => '3', '4' => '4']],
    ],
    'lists' => [[
        'key' => 'cards', 'label' => 'Karty', 'add' => '+ dodaj kartę',
        'item' => [
            ['name' => 'img', 'label' => 'Obraz (nazwa pliku w /assets/isense/img)'],
            ['name' => 'icon', 'label' => 'Ikona (nazwa)'],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'desc', 'label' => 'Opis', 'type' => 'textarea'],
            ['name' => 'url', 'label' => 'Link (slug lub URL)'],
            ['name' => 'link_label', 'label' => 'Tekst linku'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

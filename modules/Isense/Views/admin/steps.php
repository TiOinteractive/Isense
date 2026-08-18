<?php
$cfg = [
    'title'  => 'Isense.SectionSteps',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek sekcji'],
        ['name' => 'style', 'label' => 'Styl', 'type' => 'select', 'options' => ['icon' => 'Ikony', 'number' => 'Numery']],
        ['name' => 'columns', 'label' => 'Liczba kolumn', 'type' => 'select', 'options' => ['2' => '2', '3' => '3', '4' => '4']],
        ['name' => 'bg', 'label' => 'Tło', 'type' => 'select', 'options' => ['white' => 'Białe', 'gray' => 'Szare']],
    ],
    'lists' => [[
        'key' => 'items', 'label' => 'Kroki', 'add' => '+ dodaj krok',
        'item' => [
            ['name' => 'icon', 'label' => 'Ikona (nazwa)'],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'text', 'label' => 'Opis', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

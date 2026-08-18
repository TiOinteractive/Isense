<?php
$cfg = [
    'title'  => 'Isense.SectionWhyBrand',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'para1', 'label' => 'Akapit 1', 'type' => 'textarea'],
        ['name' => 'para2', 'label' => 'Akapit 2', 'type' => 'textarea'],
        ['name' => 'para3', 'label' => 'Akapit 3', 'type' => 'textarea'],
        ['name' => 'phone', 'label' => 'Telefon (przycisk)'],
    ],
    'lists' => [[
        'key' => 'stats', 'label' => 'Statystyki', 'add' => '+ dodaj statystykę',
        'item' => [
            ['name' => 'val', 'label' => 'Wartość'],
            ['name' => 'label', 'label' => 'Etykieta'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

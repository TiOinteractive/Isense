<?php
$cfg = [
    'title'  => 'Isense.SectionStats',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
    ],
    'lists' => [[
        'key' => 'items', 'label' => 'Statystyki', 'add' => '+ dodaj statystykę',
        'item' => [
            ['name' => 'number', 'label' => 'Liczba / wartość'],
            ['name' => 'label', 'label' => 'Opis'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

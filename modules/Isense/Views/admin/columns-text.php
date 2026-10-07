<?php
$cfg = [
    'title'  => 'Isense.SectionColumnsText',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek (opcjonalnie)'],
        ['name' => 'lead', 'label' => 'Tekst pod nagłówkiem (każda linia = osobny akapit; **tekst** = pogrubienie)', 'type' => 'textarea'],
        ['name' => 'bg', 'label' => 'Tło sekcji', 'type' => 'select', 'options' => ['white' => 'Białe', 'gray' => 'Szare']],
    ],
    'lists' => [[
        'key' => 'columns', 'label' => 'Kolumny', 'add' => '+ dodaj kolumnę',
        'item' => [
            ['name' => 'title', 'label' => 'Nagłówek kolumny'],
            ['name' => 'text', 'label' => 'Treść (każda linia = osobny akapit; **tekst** = pogrubienie)', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

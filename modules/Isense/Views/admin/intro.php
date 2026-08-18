<?php
$cfg = [
    'title'  => 'Isense.SectionIntro',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'subtitle', 'label' => 'Podtytuł (kursywa)'],
    ],
    'lists' => [[
        'key' => 'cards', 'label' => 'Karty (2)', 'add' => '+ dodaj kartę',
        'item' => [
            ['name' => 'image', 'label' => 'Obraz (ścieżka)'],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'text', 'label' => 'Tekst', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

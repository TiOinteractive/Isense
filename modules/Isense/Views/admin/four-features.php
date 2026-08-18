<?php
$cfg = [
    'title' => 'Isense.SectionFeatures',
    'lists' => [[
        'key' => 'features', 'label' => 'Atuty', 'add' => '+ dodaj atut',
        'item' => [
            ['name' => 'icon', 'label' => 'Ikona (ścieżka obrazu)'],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'description', 'label' => 'Opis', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

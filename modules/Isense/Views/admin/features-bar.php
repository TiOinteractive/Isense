<?php
$cfg = [
    'title'  => 'Isense.SectionFeaturesBar',
    'fields' => [],
    'lists' => [[
        'key' => 'items', 'label' => 'Atuty', 'add' => '+ dodaj atut',
        'item' => [
            ['name' => 'icon', 'label' => 'Ikona (nazwa)'],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'desc', 'label' => 'Opis'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

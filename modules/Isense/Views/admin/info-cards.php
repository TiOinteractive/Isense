<?php
$icons = ['award', 'users', 'target', 'heart', 'shield', 'check-circle', 'star', 'clock', 'wrench', 'package', 'truck', 'search', 'zap', 'map-pin', 'phone', 'mail', 'clipboard-list', 'refresh-cw', 'credit-card', 'banknote', 'battery', 'circle-help', 'home', 'trending-up', 'gift', 'dollar-sign'];
$cfg = [
    'title'  => 'Isense.SectionInfoCards',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek (opcjonalnie)'],
        ['name' => 'columns', 'label' => 'Liczba kolumn', 'type' => 'select', 'options' => ['2' => '2', '3' => '3', '4' => '4']],
        ['name' => 'bg', 'label' => 'Tło sekcji', 'type' => 'select', 'options' => ['gray' => 'Szare', 'white' => 'Białe']],
    ],
    'lists' => [[
        'key' => 'cards', 'label' => 'Karty', 'add' => '+ dodaj kartę',
        'item' => [
            ['name' => 'icon', 'label' => 'Ikona', 'type' => 'select', 'options' => array_combine($icons, $icons)],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'text', 'label' => 'Opis', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

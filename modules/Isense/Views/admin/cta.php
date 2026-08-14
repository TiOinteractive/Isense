<?php
$cfg = [
    'title'  => 'Isense.SectionCta',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'text', 'label' => 'Tekst', 'type' => 'textarea'],
        ['name' => 'bg', 'label' => 'Tło sekcji', 'type' => 'select', 'options' => ['gray' => 'Szare', 'white' => 'Białe', 'dark' => 'Ciemne (czarne)', 'promo' => 'Jasne, kompaktowe z ikonami']],
    ],
    'lists' => [[
        'key' => 'buttons', 'label' => 'Przyciski', 'add' => '+ dodaj przycisk',
        'item' => [
            ['name' => 'label', 'label' => 'Tekst przycisku'],
            ['name' => 'url', 'label' => 'Link (URL)'],
            ['name' => 'style', 'label' => 'Styl', 'type' => 'select', 'options' => ['primary' => 'Niebieski', 'outline' => 'Obramowanie']],
        ],
    ]],
];
include __DIR__ . '/_form.php';

<?php
$iconVals = ['clipboard-list', 'package-search', 'refresh-cw', 'arrow-right', 'phone', 'mail', 'map-pin', 'clock', 'check-circle', 'star', 'smartphone', 'tablet', 'monitor', 'laptop', 'watch', 'zap'];
$iconOpts = array_combine($iconVals, $iconVals);
$cfg = [
    'title' => 'Isense.SectionCtaBoxes',
    'lists' => [[
        'key' => 'boxes', 'label' => 'Boxy CTA', 'add' => '+ dodaj box',
        'item' => [
            ['name' => 'icon', 'label' => 'Ikona', 'type' => 'select', 'options' => $iconOpts],
            ['name' => 'title', 'label' => 'Tytuł'],
            ['name' => 'description', 'label' => 'Opis', 'type' => 'textarea'],
            ['name' => 'cta', 'label' => 'Tekst przycisku'],
            ['name' => 'link', 'label' => 'Link (URL)'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

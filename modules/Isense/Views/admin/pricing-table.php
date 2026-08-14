<?php
$cfg = [
    'title'  => 'Isense.SectionPricingTable',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'lead', 'label' => 'Wprowadzenie', 'type' => 'textarea'],
        ['name' => 'note', 'label' => 'Adnotacja pod tabelą', 'type' => 'textarea'],
        ['name' => 'layout', 'label' => 'Układ', 'type' => 'select', 'options' => ['by_device' => 'Karta na grupę modeli (kolumny = modele)', 'by_service' => 'Karta na usługę']],
    ],
    'lists' => [[
        'key' => 'groups', 'label' => 'Grupy cenowe', 'add' => '+ dodaj grupę',
        'item' => [
            ['name' => 'group_title', 'label' => 'Tytuł grupy'],
            ['name' => 'models_csv', 'label' => 'Kolumny modeli (oddziel przecinkami)'],
            ['name' => 'rows_text', 'label' => 'Wiersze — jeden na linię, format: Usługa | cena1 | cena2 ...', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

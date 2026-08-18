<?php
$cfg = [
    'title'  => 'Isense.SectionServicesGrid',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'lead', 'label' => 'Wprowadzenie', 'type' => 'textarea'],
    ],
    'lists' => [[
        'key' => 'items', 'label' => 'Usługi', 'add' => '+ dodaj usługę',
        'item' => [
            ['name' => 'name', 'label' => 'Nazwa usługi'],
            ['name' => 'desc', 'label' => 'Opis'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

<?php
$cfg = [
    'title'  => 'Isense.SectionTestimonials',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'subtitle', 'label' => 'Podtytuł'],
    ],
    'lists' => [[
        'key' => 'items', 'label' => 'Opinie klientów', 'add' => '+ dodaj opinię',
        'item' => [
            ['name' => 'name', 'label' => 'Imię'],
            ['name' => 'text', 'label' => 'Treść opinii', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

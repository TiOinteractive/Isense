<?php
$cfg = [
    'title'  => 'Isense.SectionFaq',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'lead', 'label' => 'Wprowadzenie', 'type' => 'textarea'],
    ],
    'lists' => [[
        'key' => 'items', 'label' => 'Pytania', 'add' => '+ dodaj pytanie',
        'item' => [
            ['name' => 'question', 'label' => 'Pytanie'],
            ['name' => 'answer', 'label' => 'Odpowiedź', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

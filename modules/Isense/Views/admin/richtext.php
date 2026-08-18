<?php
$cfg = [
    'title'  => 'Isense.SectionRichtext',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek (opcjonalnie)'],
        ['name' => 'body', 'label' => 'Treść (HTML)', 'type' => 'textarea'],
        ['name' => 'align', 'label' => 'Wyrównanie', 'type' => 'select', 'options' => ['center' => 'Wyśrodkowane', 'left' => 'Do lewej']],
        ['name' => 'bg', 'label' => 'Tło sekcji', 'type' => 'select', 'options' => ['white' => 'Białe', 'gray' => 'Szare']],
    ],
];
include __DIR__ . '/_form.php';

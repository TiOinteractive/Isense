<?php
$cfg = [
    'title'  => 'Isense.SectionTextImage',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'body', 'label' => 'Treść (akapity oddziel pustą linią)', 'type' => 'textarea'],
        ['name' => 'image', 'label' => 'Obraz (ścieżka)'],
        ['name' => 'image_side', 'label' => 'Strona obrazu', 'type' => 'select', 'options' => ['right' => 'Prawa', 'left' => 'Lewa']],
        ['name' => 'bg', 'label' => 'Tło sekcji', 'type' => 'select', 'options' => ['white' => 'Białe', 'gray' => 'Szare']],
    ],
];
include __DIR__ . '/_form.php';

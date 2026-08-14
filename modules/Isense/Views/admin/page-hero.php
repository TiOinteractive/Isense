<?php
$cfg = [
    'title'  => 'Isense.SectionPageHero',
    'fields' => [
        ['name' => 'variant', 'label' => 'Wariant', 'type' => 'select', 'options' => ['light' => 'Jasny (ciemny tekst)', 'dark' => 'Ciemny (biały tekst)', 'contact' => 'Kompaktowy, bez zdjęcia']],
        ['name' => 'bg', 'label' => 'Obraz tła (ścieżka, opcjonalnie)'],
        ['name' => 'eyebrow', 'label' => 'Tekst nad tytułem (opcjonalnie)'],
        ['name' => 'title', 'label' => 'Tytuł'],
        ['name' => 'subtitle', 'label' => 'Podtytuł', 'type' => 'textarea'],
    ],
];
include __DIR__ . '/_form.php';

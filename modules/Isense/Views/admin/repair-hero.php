<?php
$cfg = [
    'title'  => 'Isense.SectionRepairHero',
    'fields' => [
        ['name' => 'eyebrow', 'label' => 'Nadtytuł'],
        ['name' => 'title', 'label' => 'Tytuł (linia 1)'],
        ['name' => 'title2', 'label' => 'Tytuł (linia 2)'],
        ['name' => 'lead', 'label' => 'Wprowadzenie', 'type' => 'textarea'],
        ['name' => 'lead2', 'label' => 'Wprowadzenie (druga część)', 'type' => 'textarea'],
        ['name' => 'layout', 'label' => 'Układ', 'type' => 'select', 'options' => ['split' => 'Tekst + obraz obok', 'bg_image' => 'Zdjęcie w tle']],
        ['name' => 'image', 'label' => 'Obraz produktu obok tekstu (ścieżka) — układ „Tekst + obraz obok"'],
        ['name' => 'bg_image', 'label' => 'Zdjęcie w tle (ścieżka) — układ „Zdjęcie w tle"'],
        ['name' => 'anchor', 'label' => 'Kotwica przycisku „Zobacz cennik" (np. cennik)'],
    ],
];
include __DIR__ . '/_form.php';

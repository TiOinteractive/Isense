<?php
$cfg = [
    'title'  => 'Isense.SectionDevice',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'body', 'label' => 'Treść (akapity oddziel pustą linią)', 'type' => 'textarea'],
        ['name' => 'image', 'label' => 'Obraz (ścieżka)'],
        ['name' => 'image_side', 'label' => 'Strona obrazu', 'type' => 'select', 'options' => ['right' => 'Prawa', 'left' => 'Lewa']],
        ['name' => 'columns', 'label' => 'Kolumny listy', 'type' => 'select', 'options' => ['2' => '2 kolumny', '1' => '1 kolumna']],
        ['name' => 'bg', 'label' => 'Tło sekcji', 'type' => 'select', 'options' => ['gray' => 'Szare', 'white' => 'Białe']],
        ['name' => 'link_label', 'label' => 'Tekst linku'],
        ['name' => 'link_url', 'label' => 'Link (URL)'],
    ],
    'lists' => [[
        'key' => 'repairs', 'label' => 'Lista usterek/usług', 'add' => '+ dodaj pozycję',
        'item' => [['name' => 'text', 'label' => 'Pozycja']],
    ]],
];
include __DIR__ . '/_form.php';

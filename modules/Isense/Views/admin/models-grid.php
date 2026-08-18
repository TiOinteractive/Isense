<?php
$cfg = [
    'title'  => 'Isense.SectionModelsGrid',
    'fields' => [
        ['name' => 'heading', 'label' => 'Nagłówek'],
        ['name' => 'lead', 'label' => 'Wprowadzenie', 'type' => 'textarea'],
        ['name' => 'base', 'label' => 'Bazowy adres linków (np. naprawy/iphone)'],
        ['name' => 'services_intro', 'label' => 'Cennik modelu — podtytuł (strona naprawy/<kat>/<model>)'],
        ['name' => 'services_text', 'label' => 'Cennik DOMYŚLNY (gdy model nie ma własnego): Usługa | Cena | Czas | Gwarancja', 'type' => 'textarea'],
    ],
    'lists' => [[
        'key' => 'items', 'label' => 'Modele', 'add' => '+ dodaj model',
        'item' => [
            ['name' => 'name', 'label' => 'Nazwa modelu'],
            ['name' => 'slug', 'label' => 'Slug (końcówka adresu)'],
            ['name' => 'services_text', 'label' => 'Cennik TEGO modelu: Usługa | Cena | Czas | Gwarancja (jeden na linię)', 'type' => 'textarea'],
        ],
    ]],
];
include __DIR__ . '/_form.php';

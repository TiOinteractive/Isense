<?php
$cfg = [
    'title'  => 'Isense.SectionTradeIn',
    'fields' => [
        ['name' => 'badge', 'label' => 'Etykieta (badge)'],
        ['name' => 'heading', 'label' => 'Nagłówek (lewa kolumna)'],
        ['name' => 'lead', 'label' => 'Wprowadzenie', 'type' => 'textarea'],
        ['name' => 'wizard_title', 'label' => 'Tytuł kreatora (prawa kolumna)'],
        ['name' => 'currency', 'label' => 'Waluta (np. zł)'],
        ['name' => 'round', 'label' => 'Zaokrąglenie ceny do (np. 10)'],
        ['name' => 'cta1_label', 'label' => 'Przycisk 1 — tekst'],
        ['name' => 'cta1_url', 'label' => 'Przycisk 1 — adres (slug)'],
        ['name' => 'cta2_label', 'label' => 'Przycisk 2 — tekst'],
        ['name' => 'cta2_url', 'label' => 'Przycisk 2 — adres (slug)'],
    ],
    'lists' => [
        [
            'key' => 'benefits', 'label' => 'Korzyści (lewa kolumna)', 'add' => '+ dodaj korzyść',
            'item' => [
                ['name' => 'icon', 'label' => 'Ikona (nazwa)'],
                ['name' => 'title', 'label' => 'Tytuł'],
                ['name' => 'text', 'label' => 'Opis', 'type' => 'textarea'],
            ],
        ],
        [
            'key' => 'devices', 'label' => 'Kreator — typy urządzeń i modele (z cenami bazowymi)', 'add' => '+ dodaj typ urządzenia',
            'item' => [
                ['name' => 'type_label', 'label' => 'Nazwa typu (np. iPhone)'],
                ['name' => 'models_text', 'label' => 'Modele — jeden na linię, format: Model | cena bazowa (zł)', 'type' => 'textarea'],
            ],
        ],
        [
            'key' => 'conditions', 'label' => 'Kreator — stany techniczne (mnożnik ceny)', 'add' => '+ dodaj stan',
            'item' => [
                ['name' => 'label', 'label' => 'Nazwa stanu (np. Idealny)'],
                ['name' => 'description', 'label' => 'Opis'],
                ['name' => 'factor', 'label' => 'Procent ceny bazowej (100 = pełna cena, 65 = 65%)'],
            ],
        ],
    ],
];
include __DIR__ . '/_form.php';

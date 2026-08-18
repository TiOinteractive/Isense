<?php
$cfg = [
    'title'  => 'Isense.SectionStatusTracker',
    'fields' => [
        ['name' => 'title', 'label' => 'Tytuł (nagłówek)'],
        ['name' => 'bg', 'label' => 'Obraz tła hero (ścieżka, opcjonalnie)'],
        ['name' => 'subtitle', 'label' => 'Podtytuł'],
        ['name' => 'hint1', 'label' => 'Wskazówka 1 (pod wyszukiwarką)'],
        ['name' => 'hint2', 'label' => 'Wskazówka 2 — tekst (np. „Przykładowy numer testowy:")'],
        ['name' => 'hint2_number', 'label' => 'Wskazówka 2 — numer (wyróżniony na niebiesko)'],
    ],
];
include __DIR__ . '/_form.php';
?>
<div class="form-row">
    <div class="form-field" style="width:100%;">
        <p class="s" style="margin:0;padding:10px 12px;background:#e8f0fe;border:1px solid #c7dbfd;border-radius:6px;">
            Zlecenia serwisowe (numery, urządzenia, oś czasu) dodajesz i edytujesz w module
            <strong><a href="<?= ($locale ? '/' . $locale : '') . '/' . env('ADMIN_PANEL_SLUG'); ?>/orders">Zlecenia</a></strong>.
            Ta sekcja odpowiada tylko za wygląd wyszukiwarki na stronie.
        </p>
    </div>
</div>

<?php
helper(['url', 'isense']);
$assets = rtrim(base_url('assets/isense'), '/');
// „Popularne działy" — zarządzane w panelu → Menu → „404 — Popularne działy" (id 10).
$popularMenu = 10;
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>404 — nie znaleziono strony | iSense</title>
    <link rel="icon" type="image/png" href="<?= $assets ?>/img/favicon.png">
    <link rel="stylesheet" href="<?= $assets ?>/css/isense.css">
</head>
<body class="bg-white text-[#1D1D1F] antialiased">
<div class="isense-theme min-h-screen flex flex-col">
    <?= view('isense/partials/header') ?>

    <main class="flex-1 flex items-center">
        <div class="max-w-[1300px] mx-auto px-4 lg:px-12 w-full py-20 lg:py-28">
            <div class="max-w-2xl mx-auto text-center">
                <p class="text-[120px] lg:text-[160px] font-bold leading-none text-[#3b81f7]">404</p>
                <h1 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mt-2 mb-4">Ups! Nie znaleźliśmy tej strony</h1>
                <p class="text-lg text-[#6E6E73] mb-10">
                    Strona, której szukasz, mogła zostać przeniesiona lub usunięta. Sprawdź adres albo wróć na stronę główną — pomożemy Ci znaleźć to, czego potrzebujesz.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="<?= site_url('/') ?>" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-8 py-4 rounded hover:bg-[#2563eb] transition-colors font-medium text-lg shadow-lg">
                        <?= isense_icon('home', 'w-5 h-5') ?>
                        Wróć na stronę główną
                    </a>
                    <a href="<?= site_url('kontakt') ?>" class="inline-flex items-center gap-2 bg-white text-[#1D1D1F] border border-[#1D1D1F] px-8 py-4 rounded hover:bg-[#F5F5F7] transition-colors font-medium text-lg">
                        Skontaktuj się z nami
                        <?= isense_icon('arrow-right', 'w-5 h-5') ?>
                    </a>
                </div>

                <?= view_cell('\App\Libraries\IsenseMenu::inlineLinks', ['id_menu' => $popularMenu, 'heading' => 'Popularne działy:']) ?>
            </div>
        </div>
    </main>

    <?= view('isense/partials/footer') ?>
</div>
<script src="<?= $assets ?>/js/isense.js" defer></script>
</body>
</html>

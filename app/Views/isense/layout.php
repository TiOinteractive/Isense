<?php helper(['url', 'isense']); $assets = $assets ?? rtrim(base_url('assets/isense'), '/'); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'iSense — Serwis i naprawa sprzętu Apple') ?></title>
    <?php if (! empty($description)): ?>
        <meta name="description" content="<?= esc($description, 'attr') ?>">
    <?php endif; ?>
    <link rel="icon" type="image/png" href="<?= $assets ?>/img/favicon.png">
    <link rel="apple-touch-icon" href="<?= $assets ?>/img/favicon-180.png">
    <link rel="stylesheet" href="<?= $assets ?>/css/isense.css">
    <link rel="stylesheet" href="<?= $assets ?>/css/hero.css">
    <?= isense_jsonld() ?>
</head>
<body class="bg-background text-foreground antialiased">
    <div class="min-h-screen flex flex-col">
        <?= $this->include('isense/partials/header') ?>
        <main class="flex-1">
            <?= $this->renderSection('content') ?>
        </main>
        <?= $this->include('isense/partials/footer') ?>
    </div>
    <script src="<?= $assets ?>/js/isense.js" defer></script>
</body>
</html>

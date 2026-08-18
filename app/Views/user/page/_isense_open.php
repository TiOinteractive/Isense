<?php helper(['url', 'isense']); $isAssets = rtrim(base_url('assets/isense'), '/'); ?>
<link rel="stylesheet" href="<?= $isAssets ?>/css/isense.css">
<div class="isense-theme min-h-screen flex flex-col bg-white text-[#1D1D1F]">
    <?= view('isense/partials/header') ?>
    <main class="flex-1">

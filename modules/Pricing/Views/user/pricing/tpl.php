<?php
/*
Cennik — zakładki kategorii, usługi po lewej, modele z cenami po prawej
 */
use Modules\Pricing\Libraries\Pricing;

$d          = $data ?? [];
$categories = $d['categories'] ?? [];
$currency   = $d['currency'] ?? 'zł';
$showTabs   = ! empty($d['show_tabs']);
$uid        = 'pricing-' . (int) ($d['id'] ?? 0);
?>
<section class="section section-<?= $id_cont; ?> pricing-block" id="<?= $uid; ?>" data-pricing>
    <div class="container">
        <?php if (!empty($title)): ?>
            <h2 class="head"><span><?= $title; ?></span></h2>
        <?php endif; ?>
        <?php if (!empty($subtitle)): ?>
            <p class="pricing-lead"><?= $subtitle; ?></p>
        <?php endif; ?>

        <?php if ($showTabs): ?>
            <div class="pricing-tabs" role="tablist">
                <?php foreach ($categories as $i => $category): ?>
                    <button type="button" class="pricing-tab<?= $i === 0 ? ' is-active' : ''; ?>" role="tab" id="<?= $uid; ?>-tab-<?= $category['id']; ?>" aria-controls="<?= $uid; ?>-panel-<?= $category['id']; ?>" aria-selected="<?= $i === 0 ? 'true' : 'false'; ?>" tabindex="<?= $i === 0 ? '0' : '-1'; ?>" data-pricing-tab="<?= $category['id']; ?>"><?= esc($category['name']); ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php foreach ($categories as $i => $category): ?>
            <div class="pricing-panel<?= $i === 0 ? ' is-active' : ''; ?>" id="<?= $uid; ?>-panel-<?= $category['id']; ?>"<?= $showTabs ? ' role="tabpanel" aria-labelledby="' . $uid . '-tab-' . $category['id'] . '"' : ''; ?> data-pricing-panel="<?= $category['id']; ?>"<?= $i === 0 ? '' : ' hidden'; ?>>
                <?php if (empty($category['services'])): ?>
                    <p class="pricing-empty"><?= lang('Pricing.NoServices'); ?></p>
                <?php else: ?>
                    <div class="pricing-cols">
                        <div class="pricing-col-services">
                            <ul class="pricing-services" role="tablist" aria-orientation="vertical">
                                <?php foreach ($category['services'] as $s => $service): ?>
                                    <li role="presentation">
                                        <button type="button" class="pricing-service<?= $s === 0 ? ' is-active' : ''; ?>" role="tab" id="<?= $uid; ?>-service-<?= $service['id']; ?>" aria-controls="<?= $uid; ?>-models-<?= $service['id']; ?>" aria-selected="<?= $s === 0 ? 'true' : 'false'; ?>" tabindex="<?= $s === 0 ? '0' : '-1'; ?>" data-pricing-service="<?= $service['id']; ?>"><?= esc($service['name']); ?></button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="pricing-col-models">
                            <?php foreach ($category['services'] as $s => $service): ?>
                                <div class="pricing-models<?= $s === 0 ? ' is-active' : ''; ?>" id="<?= $uid; ?>-models-<?= $service['id']; ?>" role="tabpanel" aria-labelledby="<?= $uid; ?>-service-<?= $service['id']; ?>" tabindex="0" data-pricing-models="<?= $service['id']; ?>"<?= $s === 0 ? '' : ' hidden'; ?>>
                                    <?php if (empty($service['models'])): ?>
                                        <p class="pricing-empty"><?= lang('Pricing.NoModels'); ?></p>
                                    <?php else: ?>
                                        <ul class="pricing-model-list">
                                            <?php foreach ($service['models'] as $model): ?>
                                                <?php $price = Pricing::formatPrice($model['price'], $currency); ?>
                                                <li class="pricing-model">
                                                    <span class="pricing-model-name"><?= esc($model['name']); ?></span>
                                                    <span class="pricing-model-price<?= $price === '' ? ' is-empty' : ''; ?>"><?= $price !== '' ? esc($price) : lang('Pricing.PriceOnRequest'); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

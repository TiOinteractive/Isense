<div class="main-cont">
    <?php
    if (isset($breadcrumbs)) {
        echo $breadcrumbs;
    }
    ?>
    <div class="c">
        <div class="head"><?= lang('Banner.ZoneList'); ?></div>
        <p><a class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/add_zone" title="<?= lang('Banner.AddZone'); ?>"><?= lang('Banner.AddZone'); ?></a> &nbsp;&nbsp; <a class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/add" title="<?= lang('Banner.AddBaner'); ?>"><?= lang('Banner.AddBaner'); ?></a></p>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?= lang('Banner.Name'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.NumerToShow'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.Sort'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.Number'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.Edit'); ?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?= lang('Banner.Publish'); ?>
                </div>
                <div class="list-col center w100">
            <?= lang('Banner.Delete'); ?>
                </div>
            </div>
<?php if (!empty($zones)): ?>
    <?php foreach ($zones as $zone): ?>
                    <div class="list-row list-row-<?= $zone['id']; ?>">
                        <div class="list-col">
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/zone/<?= $zone['id']; ?>" title="<?= $zone['lang']->name; ?>"><?= $zone['lang']->name; ?></a>
                        </div>
                        <div class="list-col w100 center hide-1200"><?= $zone['number_to_show']; ?></div>
                        <div class="list-col w100 center hide-1200"><?= lang('Banner.sort.' . $zone['order_sort']); ?></div>
                        <div class="list-col w100 center hide-1200"><a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/zone/<?= $zone['id']; ?>" title="<?= $zone['lang']->name; ?>"><?= $zone['number']; ?></a></div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/edit_zone/<?= $zone['id']; ?>" title="<?= lang('Banner.Edit'); ?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/publish_zone/<?= $zone['id']; ?>" title="<?= lang('Banner.Publish'); ?>"><?php if (!empty($zone['publish']) && $zone['publish']): ?><i class="fa-solid fa-square-check fa-2x"></i><?php else: ?><i class="fa-regular fa-square fa-2x"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                            <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?> <a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/delete_zone/<?= $zone['id']; ?>" data-title="<?= lang('Banner.DeleteZone'); ?>" data-message="<?= lang('Banner.DeleteZoneConfirm') . ': <b>' . $zone['lang']->name . '</b>'; ?>" data-btn-ok="<?= lang('Slider.Remove'); ?>" data-btn-cancel="<?= lang('Banner.Cancel'); ?>" title="<?= lang('Banner.Delete'); ?>"><i class="fa-solid fa-trash-can fa-2x"></i></a><?php } ?>
                        </div>
                    </div>
    <?php endforeach; ?>
<?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($left_submodules)): ?>
    <div class="head"><?= lang('Admin.page.SubModulesList'); ?></div>
    <div class="list" style="margin-bottom:40px;">
        <?php foreach ($left_submodules as $k => $left): ?>
            <div class="list-row list-row-34 level-0 full">
                <div class="list-col">
                    <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/<?= strtolower($left['slug_module']); ?><?= $left['slug'] ? '/' . $left['slug'] : ''; ?>" title="<?= $left['name']; ?>"><?= $left['name']; ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
 
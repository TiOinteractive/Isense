<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Admin.modules.ModulesList');?></div>
        <div class="list list-boxes">
            <?php if(!empty($modules)): ?>
                <?php foreach($modules as $module): ?>
                    <div class="list-box">
                        <div class="bc trans400">
                            <div class="ico"><a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/<?=strtolower($module['slug']); ?>" title="<?=$module['name']; ?>"><?=$module['ico']; ?> <span><?=$module['name']; ?></span></a></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
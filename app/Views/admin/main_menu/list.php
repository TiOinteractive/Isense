<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Admin.administration.MainMenu');?></div>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Admin.menu.Name');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.menu.Show');?>
                </div>
            </div>
            <?php if(!empty($modules)): ?>
                <?php foreach($modules as $k=>$module): ?>
                    <div class="list-row list-row-<?=$module['id']; ?>">
                        <div class="list-col">
                            <?=$module['name']; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/main-menu/show/<?=$module['id']; ?>" title="<?=lang('Admin.menu.Show');?>"><?php if(!empty($module['main']) && $module['main']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                    </div>
                <?php endforeach;  ?>
            <?php endif; ?>
        </div>
    </div>
</div>

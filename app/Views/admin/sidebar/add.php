<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($sidebar) &&!empty($sidebar['id'])): ?>
                <?= $sidebar['name']; ?>
            <span>
            <?= lang('Admin.sidebar.SidebarEdit'); ?>
            </span>
            <?php else: ?>
            <?= lang('Admin.sidebar.NewSidebarAdd'); ?>
        <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form sidebar-config-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/sidebar/<?php echo $action; ?><?= !empty($sidebar['id']) ? '/' . $sidebar['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Admin.sidebar.BasicInformation'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l = 0;
                        foreach($languages as $lang): ?>
                        <div class="tab<?= $l==0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                        <?php ++$l;
                        endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                <?php $l = 0; foreach($languages as $lang): ?>
                    <div class="link-box lang-<?= $lang['id']; ?> tab-item<?= $l==0 ? ' active' : ''; ?>">
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Admin.sidebar.Name'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][name]" value="<?= !empty($sidebar['lang']) ? esc($sidebar['lang'][$lang['id']]['name']) : ''; ?>" />
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.sidebar.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($sidebar['publish']) && $sidebar['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.sidebar.SidebarSettings'); ?></h3>
            </div>
            
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.sidebar.AssignedModules'); ?></label>
                </div>
                <div class="form-field">
                    <div class="module-list order-box">
                        <?php if(!empty($sidebar['content'])): ?>
                            <?php foreach($sidebar['content'] as $no=>$content): ?>
                                <?= view('admin/sidebar/add_module_el', array('module_elements'=>$module_elements, 'content'=>$content, 'no'=>$no)); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?= view('admin/sidebar/add_module_el', array('module_elements'=>$module_elements, 'no'=>0)); ?>
                        <?php endif; ?>
                    </div>
                    <a class="btn add-module-element" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/sidebar/add-module-el" title="<?=lang('Admin.sidebar.AddModule'); ?>"><?=lang('Admin.sidebar.AddModule'); ?></a>
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Event.Save'); ?></button>
            </div>
        </form>
    </div>
</div>

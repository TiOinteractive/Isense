<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($menu)): ?>
                <?=lang('Admin.menu.MenuEdit'); ?>
            <?php else: ?>
                <?=lang('Admin.menu.MenuAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form menu-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/menu/<?php echo $action; ?><?=!empty($menu['id']) ? '/' . $menu['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Admin.menu.BasicInformation'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                    <?php $l=0; foreach($languages as $lang): ?>
                        <div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.menu.Name');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($menu['lang']) ? $menu['lang'][$lang['id']]['name'] : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.menu.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($menu['publish']) && $menu['publish']):?>checked="checked"<?php endif; ?> value="1" />
                </div>
            </div>
            <?php if(!empty($menu) && !empty($menu['id'])): ?>
                <div class="form-row-space"></div>
                <div class="form-row nag">
                    <h3><?=lang('Admin.menu.MenuStructure'); ?></h3>
                </div>
                <div class="form-row menu-elements-box">
                    <div class="available-elements">
                        <div class="available-elements-cont">
                            <?php if(!empty($available_elements)): ?>
                                <?php $i=0; foreach($available_elements as $m=>$available_module): ?>
                                    <?php if(!empty($available_module)): ?>
                                        <div class="module<?php if($i): ?> minimized<?php endif; ?>">
                                            <div class="header">
                                                <?=lang('Admin.menu.available.' . $m); ?>
                                                <div class="expand"><i class="fa-solid fa-chevron-up"></i></div>
                                            </div>
                                            <div class="available-elements-list">
                                                <?php foreach($available_module as $available_element): ?>
                                                    <div class="element checkbox">
                                                        <input type="checkbox" name="available[<?=$m; ?>][]" value="<?=$available_element['id']; ?>" id="<?='element-' . $m . '-' . $available_element['id']; ?>" />
                                                        <label for="<?='element-' . $m . '-' . $available_element['id']; ?>"><?=$available_element['name']; ?></label>
                                                            <?php 
                                                            if(isset($available_element['list'])) {
                                                              echo view('admin/menu/menu_page_list', array('list' => $available_element['list'], 'level' => $available_element['level'], 'm' => $m));
                                                            }
                                                            ?>
                                                    </div>
                                                <?php endforeach;?>
                                                <div class="buttons-box">
                                                    <a class="btn add-to-menu-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/menu/add-elements<?=!empty($menu['id']) ? '/' . $menu['id'] : '' ; ?>" title="<?=lang('Admin.menu.AddToMenu'); ?>"><?=lang('Admin.menu.AddToMenu'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php ++$i; endforeach;?>
                            <?php endif; ?>
                            <div class="module minimized">
                                <div class="header">
                                    <?=lang('Admin.menu.OwnLinks'); ?>
                                    <div class="expand"><i class="fa-solid fa-chevron-up"></i></div>
                                </div>
                                <div class="available-elements-list">
                                    <div class="element text">
                                        <input type="hidden" name="available[own]" value="" />
                                    </div>
                                    <div class="buttons-box">
                                        <a class="btn add-to-menu-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/menu/add-elements<?=!empty($menu['id']) ? '/' . $menu['id'] : '' ; ?>" title="<?=lang('Admin.menu.AddToMenu'); ?>"><?=lang('Admin.menu.AddToMenu'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="menu-elements" id="menu-elements">
                        <div class="menu-elements-list menu-item-group">
                            <?php if(!empty($menu['element'])): ?>
                                <?php foreach($menu['element'] as $no=>$el): ?>
                                    <?=view('admin/menu/menu_items', array('menu_item'=>$el, 'no'=>$no + 1, 'parents' => $parents)); ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Admin.menu.Save'); ?></button>
            </div>  
        </form>
    </div>
</div>
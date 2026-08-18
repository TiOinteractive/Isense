<?php if(!empty($menu_item)): ?>
    <div class="menu-item minimized level-<?=!empty($menu_item['level']) ? $menu_item['level'] : 0; ?>" data-no="<?php echo isset($no) ? $no : 0; ?>" data-level="<?=!empty($menu_item['level']) ? $menu_item['level'] : 0; ?>">
        <div class="header">
            <div class="expand"><i class="fa-solid fa-chevron-up"></i></div>
            <div class="no">#<?=$no; ?></div>
            <div class="name"><?=!empty($menu_item['name']) ? $menu_item['name'] : ''; ?></div>
            <div class="type"><?=!empty($menu_item['type']) ? lang('Admin.menu.available.' . $menu_item['type']) : ''; ?></div>
            <div class="delete">
               <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?> <a href="#" class="delete-menu-item" title="<?=lang('Admin.menu.DeleteMenuItem');?>" data-title="<?=lang('Admin.menu.DeleteMenuItem');?>" data-message="<?=lang('Admin.menu.ItemConfirmInfo') . (!empty($menu_item['name']) ? ': <b>' . $menu_item['name'] . '</b>' : '');?>" data-btn-ok="<?=lang('Admin.menu.Remove');?>" data-btn-cancel="<?=lang('Admin.menu.Cancel');?>"><i class="fa-regular fa-trash-can"></i></a> <?php } ?>
            </div>
        </div>
        <div class="menu-item-content">
            <input type="hidden" class="id-field" name="element[<?php echo isset($no) ? $no : 0; ?>][id]" value="<?=!empty($menu_item['id']) ? $menu_item['id'] : ''; ?>" />
            <input type="hidden" name="element[<?php echo isset($no) ? $no : 0; ?>][name]" value="<?=!empty($menu_item['name']) ? $menu_item['name'] : ''; ?>" />
            <input type="hidden" class="order-field" name="element[<?php echo isset($no) ? $no : 0; ?>][order]" value="<?=!empty($menu_item['order']) ? $menu_item['order'] : 0; ?>" />
            <input type="hidden" class="parent-field" name="element[<?php echo isset($no) ? $no : 0; ?>][id_parent]" value="<?=!empty($menu_item['id_parent']) ? $menu_item['id_parent'] : ''; ?>" />
            <input type="hidden" name="element[<?php echo isset($no) ? $no : 0; ?>][id_target]" value="<?=!empty($menu_item['id_target']) ? $menu_item['id_target'] : ''; ?>" />
            <input type="hidden" name="element[<?php echo isset($no) ? $no : 0; ?>][type]" value="<?=!empty($menu_item['type']) ? $menu_item['type'] : ''; ?>" />
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
                        <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-field-box">
                                <label><?=lang('Admin.menu.Name');?></label>
                                <input class="link-name" type="text" name="element[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][name]" value="<?=!empty($menu_item['lang']) ? $menu_item['lang'][$lang['id']]['name'] : ''; ?>" />
                            </div>
                            <div class="form-field-box">
                                <label><?=lang('Admin.menu.UrlAddress');?></label>
                                <input class="link-name" type="text" name="element[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][url]" value="<?=!empty($menu_item['lang']) ? $menu_item['lang'][$lang['id']]['url'] : ''; ?>" <?=!empty($menu_item['type']) && $menu_item['type'] != 'own' ? 'readonly="readonly"' : ''; ?> />
                            </div>
                            <div class="form-field-box">
                                <label><?=lang('Admin.menu.TitleAtribute');?></label>
                                <input class="link-name" type="text" name="element[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][title]" value="<?=!empty($menu_item['lang']) ? $menu_item['lang'][$lang['id']]['title'] : ''; ?>" />
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-field-box">
                <label><?=lang('Admin.menu.Target');?></label>
                <select name="element[<?php echo isset($no) ? $no : 0; ?>][target]">
                    <option value=""><?=lang('Admin.menu.TheSameWindow'); ?></option>
                    <option value="_blank"<?=!empty($menu_item['target']) && $menu_item['target']=='_blank' ? 'selected="selected"' : ''; ?>><?=lang('Admin.menu.NewWindow'); ?></option>
                </select>
            </div>
            <div class="form-field-box">
                <label><?=lang('Admin.menu.CssClass');?></label>
                <input type="text" name="element[<?php echo isset($no) ? $no : 0; ?>][class]" value="<?=!empty($menu_item['class']) ? $menu_item['class'] : ''; ?>" />
            </div>
        </div>
    </div>
    <?php if(!empty($menu_item['list'])): ?>
        <?php foreach($menu_item['list'] as $l=>$it): ?>
            <?=view('admin/menu/menu_item', array('menu_item'=>$it, 'no'=>$no . '.' . $l)); ?>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif ;?>
<?php if(!empty($menu_item)): ?>
<div class="menu-item-group">
    <div class="menu-item minimized" data-no="<?php echo isset($no) ? $no : 0; ?>">
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
            <input type="hidden" class="id-field" name="element[<?=$menu_item['id']; ?>][id]" value="<?=!empty($menu_item['id']) ? $menu_item['id'] : ''; ?>" />
            <input type="hidden" name="element[<?=$menu_item['id']; ?>][name]" value="<?=!empty($menu_item['name']) ? $menu_item['name'] : ''; ?>" />
            <input type="hidden" class="order-field" name="element[<?=$menu_item['id']; ?>][order]" value="<?=!empty($menu_item['order']) ? $menu_item['order'] : 0; ?>" />
            <input type="hidden" class="parent-field" name="element[<?=$menu_item['id']; ?>][id_parent]" value="<?=!empty($menu_item['id_parent']) ? $menu_item['id_parent'] : ''; ?>" />
            <input type="hidden" class="parent-key" name="element[<?=$menu_item['id']; ?>][parent_key]" value="" />
            <input type="hidden" name="element[<?=$menu_item['id']; ?>][id_target]" value="<?=!empty($menu_item['id_target']) ? $menu_item['id_target'] : ''; ?>" />
            <input type="hidden" name="element[<?=$menu_item['id']; ?>][type]" value="<?=!empty($menu_item['type']) ? $menu_item['type'] : ''; ?>" />
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
                                <input class="link-name" type="text" name="element[<?=$menu_item['id']; ?>][lang][<?=$lang['id']; ?>][name]" value="<?=!empty($menu_item['lang']) ? $menu_item['lang'][$lang['id']]['name'] : ''; ?>" />
                            </div>
                            <div class="form-field-box">
                                <label><?=lang('Admin.menu.UrlAddress');?></label>
                                <input class="link-name" type="text" name="element[<?=$menu_item['id']; ?>][lang][<?=$lang['id']; ?>][url]" value="<?=!empty($menu_item['lang']) ? $menu_item['lang'][$lang['id']]['url'] : ''; ?>" <?=!empty($menu_item['type']) && $menu_item['type'] != 'own' ? 'readonly="readonly"' : ''; ?> />
                            </div>
                            <div class="form-field-box">
                                <label><?=lang('Admin.menu.TitleAtribute');?></label>
                                <input class="link-name" type="text" name="element[<?=$menu_item['id']; ?>][lang][<?=$lang['id']; ?>][title]" value="<?=!empty($menu_item['lang']) ? $menu_item['lang'][$lang['id']]['title'] : ''; ?>" />
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-field-box">
                <label><?=lang('Admin.menu.Target');?></label>
                <select name="element[<?=$menu_item['id']; ?>][target]">
                    <option value=""><?=lang('Admin.menu.TheSameWindow'); ?></option>
                    <option value="_blank"<?=!empty($menu_item['target']) && $menu_item['target']=='_blank' ? 'selected="selected"' : ''; ?>><?=lang('Admin.menu.NewWindow'); ?></option>
                </select>
            </div>
            <div class="form-field-box">
                <label><?=lang('Admin.menu.CssClass');?></label>
                <input type="text" name="element[<?=$menu_item['id']; ?>][class]" value="<?=!empty($menu_item['class']) ? $menu_item['class'] : ''; ?>" />
            </div>
            <div class="form-field-box">
                <label><?=lang('Admin.menu.SVGCode');?></label>
                <textarea name="element[<?=$menu_item['id']; ?>][svg]"><?=!empty($menu_item['svg']) ? $menu_item['svg'] : ''; ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.FeaturedImage');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                            <?php if(!empty($menu_item['photo'])): ?>
                                <?=view('admin/filemenager/files_list', array('files'=>array($menu_item['photo']), 'name'=>'element['.$menu_item['id'].'][photo]','key_name'=>'')); ?>
                            <?php endif; ?>
                        </div>
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Admin.file-menager.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-type="image" data-key="" data-field-name="element[<?=$menu_item['id']; ?>][photo]" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                    <input type="hidden" name="id_photo" value="<?=!empty($page) ? $page['id_photo'] : ''; ?>" />
                </div>
            </div>
            <?php if(!empty($menu_item['id_parent'])): ?>
                <div class="form-field-box">
                    <label><?=lang('Admin.menu.ParentActive');?></label>
                    
                    <select name="element[<?=$menu_item['id']; ?>][id_parent_active]">
                        <option value=""><?=lang('Admin.menu.Auto'); ?></option>
                        <?php if(!empty($parents)): ?>
                            <?php foreach($parents as $k=>$p): ?>
                                <?= view('admin/menu/select_parents', array('item'=>$p, 'id_parent'=>!empty($menu_item['id_parent_active']) ? $menu_item['id_parent_active'] : 0, 'count'=>count($parents), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if(!empty($menu_item['list'])): ?>
        <?php foreach($menu_item['list'] as $l=>$it): ?>
            <?=view('admin/menu/menu_items', array('menu_item' => $it, 'no' => $no . '.' . $l)); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
<?php endif ;?>
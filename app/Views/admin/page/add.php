<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($page) && !empty($page['id'])): ?>
                <?= $page['name'] ?? '' ?>
                <span><?=lang('Admin.page.PageConfiguration'); ?></span>
            <?php else: ?>
                <?=lang('Admin.page.NewPageAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form page-config-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/<?php echo $action; ?><?=!empty($page['id']) ? '/' . $page['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Admin.page.BasicInformation'); ?></h3>
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
                                    <label><?=lang('Admin.page.Name');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($page['lang']) ? $page['lang'][$lang['id']]['name'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.DirectLink');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_link]" value="<?=!empty($page['lang']) ? $page['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                    <input class="link-id-lang" type="hidden" value="<?=$lang['id']; ?>" />
                                    <input class="link-field" type="text" name="lang[<?=$lang['id']; ?>][link]" value="<?=!empty($page['lang']) ? $page['lang'][$lang['id']]['link'] : ''; ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.Header');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][header]" value="<?=!empty($page['lang']) ? $page['lang'][$lang['id']]['header'] : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.page.Metatags'); ?></h3>
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
                        <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.MetaTitle');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="meta[lang][<?=$lang['id']; ?>][title]" value="<?=!empty($page['meta']['lang']) ? $page['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.MetaDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][description]"><?=!empty($page['meta']['lang']) ? $page['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.MetaKeywords');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][keywords]"><?=!empty($page['meta']['lang']) ? $page['meta']['lang'][$lang['id']]['keywords'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Admin.page.MetaPhoto');?></label>
                    </div>
                    <div class="form-field">
                        <div class="files-list">
                            <?php if(!empty($page['meta_photo'])): ?>
                                <?=view('admin/filemenager/files_list', array('files'=>array($page['meta_photo']), 'name'=>'meta_photo','key_name'=>'')); ?>
                            <?php endif; ?>
                        </div>
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Admin.file-menager.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-type="image" data-key="" data-field-name="meta_photo" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                        <input type="hidden" name="id_meta_photo" value="<?=!empty($page) ? $page['id_meta_photo'] : ''; ?>" />
                    </div>
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.page.PageSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.FeaturedImage');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                            <?php if(!empty($page['photo'])): ?>
                                <?=view('admin/filemenager/files_list', array('files'=>array($page['photo']), 'name'=>'photo','key_name'=>'')); ?>
                            <?php endif; ?>
                        </div>
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Admin.file-menager.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-type="image" data-key="" data-field-name="photo" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                    <input type="hidden" name="id_photo" value="<?=!empty($page) ? $page['id_photo'] : ''; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.ParentElement');?></label>
                </div>
                <div class="form-field">
                    <select name="re_id" class="link-page-id">
                        <option value="0">(<?=lang('Admin.page.ThereIsNoParent'); ?>)</option>
                        <?php if(!empty($pages)): ?>
                            <?php foreach($pages as $k=>$p): ?>
                                <?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($page['re_id']) ? $page['re_id'] : 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.Template');?></label>
                </div>
                <div class="form-field">
                    <select name="template">
                        <?php if(!empty($templates)): ?>
                            <?php foreach($templates as $template): ?>
                                <option value="<?=$template['file']; ?>"<?=!empty($page) && $page['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?=$template['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.AssignedModules'); ?></label>
                </div>
                <div class="form-field">
                    <div class="module-list order-box">
                        <?php if(!empty($page['content'])): ?>
                            <?php foreach($page['content'] as $no=>$content): ?>
                                <?= view('admin/page/add_module_el', array('module_elements'=>$module_elements, 'content'=>$content, 'no'=>$no)); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?= view('admin/page/add_module_el', array('module_elements'=>$module_elements, 'no'=>0)); ?>
                        <?php endif; ?>
                    </div>
                    <a class="btn add-module-element" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/add-module-el" title="<?=lang('Admin.page.AddModule'); ?>"><?=lang('Admin.page.AddModule'); ?></a>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.Sidebar');?></label>
                </div>
                <div class="form-field">
                    <select name="id_sidebar" class="edit-sidebar-handler">
                        <option value=""></option>
                            <?php foreach($sidebars as $sidebar): ?>
                                <option value="<?=$sidebar['id']; ?>"<?=!empty($page) && $page['id_sidebar'] == $sidebar['id'] ? ' selected="selected"' : ''; ?> data-link="<?=!empty($sidebar['link']) ? $sidebar['link'] : ''; ?>"><?=$sidebar['name']; ?></option>
                            <?php endforeach; ?>
                    </select>
                    <a class="edit-sidebar-link" href="<?=!empty($sidebars) && !empty($page) && !empty($page['id_sidebar']) && !empty($sidebars[$page['id_sidebar']]) ? $sidebars[$page['id_sidebar']]['link'] : ''; ?>" title="<?=lang('Admin.page_content.GoToEdition'); ?>" target="_blank"><?=lang('Admin.page_content.GoToEdition'); ?>: "<strong><?=!empty($sidebars) && !empty($page) && !empty($page['id_sidebar']) && !empty($sidebars[$page['id_sidebar']]) ? $sidebars[$page['id_sidebar']]['name'] : ''; ?></strong>" <i class="fa-solid fa-angles-right"></i></a>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.NoIndex');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="no_index" <?php if(!empty($page['no_index']) && $page['no_index']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($page['publish']) && $page['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Admin.page.Save'); ?></button>
            </div>     
        </form>
    </div>
</div>

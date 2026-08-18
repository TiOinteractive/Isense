<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=$page_info['name']; ?>
            <span>
                <?php if(!empty($page_content)): ?>
                    <?=lang('Admin.page.PageContentEdit'); ?>
                <?php else: ?>
                    <?=lang('Admin.page.PageContent'); ?>
                <?php endif; ?>
            </span>
        </div>
        <?php if(!empty($content_elements)): ?>
            <div class="tabs page-content-tabs">
                <div class="tabs-head">
                    <div class="tabs-head-content">
                        <?php foreach($content_elements as $k=>$cont_element): ?>
                            <?php if($cont_element['id']==$id_content): ?>
                                <div class="tab active">
                                    <input class="page-content-name" type="text" value="<?=!empty($cont_element['name2']) ? $cont_element['name2'] : $cont_element['name']; ?>" placeholder="<?=$cont_element['name']; ?>" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$id_page; ?>/<?=$cont_element['id']; ?>" /> 
                                </div>
                            <?php else: ?>
                                <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$id_page; ?>/<?=$cont_element['id']; ?>" title="<?=!empty($cont_element['name2']) ? $cont_element['name2'] : $cont_element['name']; ?>" class="tab<?=$cont_element['id']==$id_content ? ' active' : ''; ?>"><span class="name"><?=!empty($cont_element['name2']) ? $cont_element['name2'] : $cont_element['name']; ?></span></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if(!empty($module_data)): ?>
            <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
            <form class="form page-content" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$id_page; ?>/<?=$id_content; ?>" method="post">
                <div class="form-row nag">
                    <h3><?=lang('Admin.page_content.SectionSettings'); ?></h3>
                    <div class="expand<?php if(!empty($module_data['list_view'])): ?> rotate<?php endif; ?>"><i class="fa-solid fa-chevron-up"></i></div>
                </div>
                <div class="expand-content<?php if(!empty($module_data['list_view'])): ?> hidden<?php endif; ?>">
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
                                            <label><?=lang('Admin.page_content.Title');?></label>
                                        </div>
                                        <div class="form-field">
                                            <input type="text" name="lang[<?=$lang['id']; ?>][title]" value="<?=!empty($page_content['lang'][$lang['id']]['title']) ? esc($page_content['lang'][$lang['id']]['title']) : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <label><?=lang('Admin.page_content.Subtitle');?></label>
                                        </div>
                                        <div class="form-field">
                                            <input type="text" name="lang[<?=$lang['id']; ?>][subtitle]" value="<?=!empty($page_content['lang'][$lang['id']]['subtitle']) ? esc($page_content['lang'][$lang['id']]['subtitle']) : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <label><?=lang('Admin.page_content.Url');?></label>
                                        </div>
                                        <div class="form-field">
                                            <input type="text" name="lang[<?=$lang['id']; ?>][url]" value="<?=!empty($page_content['lang'][$lang['id']]['url']) ? esc($page_content['lang'][$lang['id']]['url']) : ''; ?>" />
                                        </div>
                                    </div>
                                    <?php if(!empty($module_data['form_lang_view'])): ?>
                                        <?= view($module_data['form_lang_view'], array('lang' => $lang)); ?>
                                    <?php endif; ?>
                                </div>
                            <?php ++$l; endforeach; ?>
                        <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
                    </div>
                    <?php if(!empty($module_data['elements'])): ?>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?=lang('Admin.page_content.Element'); ?></label>
                            </div>
                            <div class="form-field">
                                <select name="id_element" class="edit-element-handler">
                                    <option value=""></option>
                                    <?php $current = ''; if(!empty($module_data['elements'])): ?>
                                        <?php foreach($module_data['elements'] as $element): ?>
                                            <option value="<?=$element['id']; ?>"<?=!empty($page_content) && $page_content['id_element'] == $element['id'] ? ' selected="selected"' : ''; ?> data-link="<?=!empty($element['link']) ? $element['link'] : ''; ?>"><?=$element['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <a class="edit-element-link" href="<?=!empty($module_data['elements']) && !empty($page_content) && !empty($page_content['id_element']) && !empty($module_data['elements'][$page_content['id_element']]) ? $module_data['elements'][$page_content['id_element']]['link'] : ''; ?>" title="<?=lang('Admin.page_content.GoToEdition'); ?>" target="_blank"><?=lang('Admin.page_content.GoToEdition'); ?>: "<strong><?=!empty($module_data['elements']) && !empty($page_content) && !empty($page_content['id_element']) && !empty($module_data['elements'][$page_content['id_element']]) ? $module_data['elements'][$page_content['id_element']]['name'] : ''; ?></strong>" <i class="fa-solid fa-angles-right"></i></a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($module_data['pc_templates'])): ?>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?=lang('Admin.page_content.Template');?></label>
                            </div>
                            <div class="form-field">
                                <select name="template">
                                    <?php if(!empty($module_data['pc_templates'])): ?>
                                        <?php foreach($module_data['pc_templates'] as $template): ?>
                                            <option value="<?=$template['file']; ?>"<?=!empty($page_content) && $page_content['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?=$template['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($sidebars)): ?>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?=lang('Admin.page_content.Sidebar'); ?></label>
                            </div>
                            <div class="form-field">
                                <select name="id_sidebar" class="edit-sidebar-handler">
                                    <option value=""></option>
                                        <?php foreach($sidebars as $sidebar): ?>
                                            <option value="<?=$sidebar['id']; ?>"<?=!empty($page_content) && $page_content['id_sidebar'] == $sidebar['id'] ? ' selected="selected"' : ''; ?> data-link="<?=!empty($sidebar['link']) ? $sidebar['link'] : ''; ?>"><?=$sidebar['name']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                                <a class="edit-sidebar-link" href="<?=!empty($sidebars) && !empty($page_content) && !empty($page_content['id_sidebar']) && !empty($sidebars[$page_content['id_sidebar']]) ? $sidebars[$page_content['id_sidebar']]['link'] : ''; ?>" title="<?=lang('Admin.page_content.GoToEdition'); ?>" target="_blank"><?=lang('Admin.page_content.GoToEdition'); ?>: "<strong><?=!empty($sidebars) && !empty($page_content) && !empty($page_content['id_sidebar']) && !empty($sidebars[$page_content['id_sidebar']]) ? $sidebars[$page_content['id_sidebar']]['name'] : ''; ?></strong>" <i class="fa-solid fa-angles-right"></i></a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($module_data['form_view'])): ?>
                        <?= view($module_data['form_view'], $module_data); ?>
                    <?php endif; ?>
                    <div class="form-row submit">
                        <button type="submit" class=""><?=lang('Admin.page_content.Save');?></button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
        <?php if(!empty($module_data['list_view'])): ?>
            <?= view($module_data['list_view'], $module_data); ?>
        <?php endif; ?>
    </div>
</div>
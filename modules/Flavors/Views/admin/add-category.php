<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($category) && !empty($category['id'])): ?>
                <?=$category['name']; ?>
                <span><?=lang('Flavors.CategoryEdit'); ?></span>
            <?php else: ?>
                <?=lang('Flavors.AddCategory'); ?>
            <?php endif; ?>
        </div>
		<?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
		 <form class="form news-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/<?php echo $action; ?><?=!empty($category['id']) ? '/' . $category['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Flavors.BasicInformation'); ?></h3>
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
                                    <label><?=lang('Flavors.CategoryName');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($category['lang']) ? esc($category['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.DirectLink');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-direct-links" type="hidden" value="<?=$direct_links[$lang['id']];?>">
                                    <input class="link-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_link]" value="<?=!empty($category['lang']) ? $category['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                    <input class="link-id-lang" type="hidden" value="<?=$lang['id']; ?>" />
                                    <input class="link-field" type="text" name="lang[<?=$lang['id']; ?>][link]" value="<?=!empty($category['lang']) ? esc($category['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.CategoryDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][description]"><?=!empty($category['lang']) ? $category['lang'][$lang['id']]['description'] : ''; ?></textarea>
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
                                    <input type="text" name="meta[lang][<?=$lang['id']; ?>][title]" value="<?=!empty($category['meta']['lang']) ? $category['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.MetaDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][description]"><?=!empty($category['meta']['lang']) ? $category['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.MetaKeywords');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][keywords]"><?=!empty($category['meta']['lang']) ? $category['meta']['lang'][$lang['id']]['keywords'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
					<div class="form-row-space"></div>
					<div class="form-row nag">
						<h3><?=lang('Flavors.CategoryParameters'); ?></h3>
					</div>
					<div class="form-row" style="display:block;">
					
					<p><a class="btn small add-cat-parameter" data-ok="<?=lang('Flavors.AddParameter');?>" data-cancel="<?=lang('Flavors.Cancel');?>" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/add-category-parameter" title="<?=lang('Flavors.AddParameter');?>"><i class="fa-solid fa-plus"></i> <?=lang('Flavors.AddParameter');?></a></p>
					  		<div class="list order-sortable cat-param-list">
								<div class="list-row list-head">
					                <div class="list-col w200">
										<?=lang('Flavors.ParameterName');?>
									</div>
									<div class="list-col w200">
										<?=lang('Flavors.ParameterFilterName');?>
									</div>
									<div class="list-col">
										<?=lang('Flavors.ParameterValues');?>
									</div>
									<div class="list-col w100">
										<?=lang('Flavors.Delete');?>
									</div>
					            </div>
							  <?php if(!empty($category['params'])):?>
									<?= view('Modules\Flavors\Views\admin\category_parameter_modal_save', array('params'=>$category['params'])); ?>
							  <?php endif;?>
					        </div>
					</div>
			<div class="form-row nag">
                <h3><?=lang('News.NewsMedia'); ?></h3>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.MenuIcon');?></label>
                </div>
                <div class="form-field">
                   <textarea name="svg"><?=!empty($category['svg']) ? $category['svg'] : ''; ?></textarea>
                </div>
            </div>
				<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.MapIcon');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($category['mapicon'])): ?>
                            <?=view('admin/filemenager/upload_file', array('field' => 'mapicon', 'file' => $category['mapicon'], 'multi' => false)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="mapicon" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
					 <div class="form-row-space"></div>
					<div class="form-row nag">
						<h3><?=lang('Flavors.CategorySettings'); ?></h3>
					</div>
					  <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.ParentCategory');?></label>
                </div>
                <div class="form-field">
                    <select name="re_id" class="link-category-id">
                        <option value="0">(<?=lang('Admin.page.ThereIsNoParent'); ?>)</option>
                        <?php if(!empty($pages)): ?>
                            <?php foreach($pages as $k=>$p): ?>
                                <?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($category['re_id']) ? $category['re_id'] : 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.page.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($category['publish']) && $category['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Admin.page.Save'); ?></button>
            </div>    
			</form>
	</div>
</div>	
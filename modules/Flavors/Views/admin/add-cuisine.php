<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($cuisine) && !empty($cuisine['id'])): ?>
                <?=$cuisine['name']; ?>
                <span><?=lang('Flavors.CuisineEdit'); ?></span>
            <?php else: ?>
                <?=lang('Flavors.AddCuisine'); ?>
            <?php endif; ?>
        </div>
		<?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
		 <form class="form news-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/<?php echo $action; ?><?=!empty($cuisine['id']) ? '/' . $cuisine['id'] : '' ; ?>" method="post">
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
                                    <label><?=lang('Flavors.CuisineName');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($cuisine['lang']) ? esc($cuisine['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.DirectLink');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-direct-links" type="hidden" value="<?=$direct_links[$lang['id']];?>">
                                    <input class="link-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_link]" value="<?=!empty($cuisine['lang']) ? $cuisine['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                    <input class="link-id-lang" type="hidden" value="<?=$lang['id']; ?>" />
                                    <input class="link-field" type="text" name="lang[<?=$lang['id']; ?>][link]" value="<?=!empty($cuisine['lang']) ? esc($cuisine['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.CuisineDenmark');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="lang[<?=$lang['id']; ?>][denmark]"><?=!empty($cuisine['lang']) ? esc($cuisine['lang'][$lang['id']]['denmark']) : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.CuisineDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][description]"><?=!empty($cuisine['lang']) ? $cuisine['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
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
                                    <input type="text" name="meta[lang][<?=$lang['id']; ?>][title]" value="<?=!empty($cuisine['meta']['lang']) ? $cuisine['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.MetaDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][description]"><?=!empty($cuisine['meta']['lang']) ? $cuisine['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.page.MetaKeywords');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][keywords]"><?=!empty($cuisine['meta']['lang']) ? $cuisine['meta']['lang'][$lang['id']]['keywords'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
					<div class="form-row-space"></div>
		   <div class="form-row nag">
                <h3><?=lang('Flavors.Media'); ?></h3>
            </div>
			  <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.CuisineIcoSvg');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="ico_svg"><?=!empty($cuisine['ico_svg']) ? $cuisine['ico_svg'] : ''; ?></textarea>
                                </div>
                            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.PrimaryPhoto');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($cuisine['photo'])): ?>
                            <?=view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $cuisine['photo'], 'multi' => false)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photo" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
			  <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Flavors.CuisineSettings'); ?></h3>
            </div>           
               <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($cuisine['publish']) && $cuisine['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                 <button type="submit" class=""><?=lang('Admin.settings.Save'); ?></button>
            </div>   
		</form>
	</div>
</div>	
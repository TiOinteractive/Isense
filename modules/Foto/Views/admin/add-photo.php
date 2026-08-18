<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Foto.PhotoAdd'); ?>
	   </div>
	   <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
	           <form class="form news-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/<?php echo $action; ?>/<?=$id_content;?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('News.BasicInformation'); ?></h3>
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
                                    <label><?=lang('Foto.PhotoName');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($gallery['lang']) ? esc($gallery['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Foto.PhotoDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][description]"><?=!empty($gallery['lang']) ? $gallery['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
							 <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Foto.Keywords');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="tags_input" name="lang[<?=$lang['id']; ?>][keywords]" value="<?=!empty($gallery['lang']) ? esc($gallery['lang'][$lang['id']]['keywords']) : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Foto.GalleryCategory');?></label>
                </div>
                <div class="form-field">
						<select name="id_category" class="category_id">
								<option value="0">(<?=lang('Admin.page.ThereIsNoParent'); ?>)</option>
								<?php if(!empty($pages)): ?>
									<?php foreach($pages as $k=>$p): ?>
										<?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($file['id_category']) ? $file['id_category'] : 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
									<?php endforeach; ?>
								<?php endif; ?>
						</select>
				</div>
			</div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Foto.ChooseUser');?></label>
                </div>
                <div class="form-field">
                    <select name="id_user">
					  <?php foreach($users as $user):?>
					  <option value="<?=$user['id'];?>" <?php if($user['mail']=='albert@tio.pl'):?> selected="selected" <?php endif;?>><?=$user['mail'];?> <?=$user['nick'];?></option>
					  <?php endforeach;?>
					</select>
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.SelectAsHome');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="home" <?php if(!empty($gallery['home']) && $gallery['home']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($gallery['publish']) && $gallery['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.PrimaryPhoto');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($news['photos'])): ?>
                            <?php foreach($news['photos'] as $k=>$photo): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $photo, 'multi' =>false, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photo" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp">
                    </span>
                </div>
            </div>
			<div class="form-row submit">
                <button type="submit" class=""><?=lang('Foto.PhotoAddBtn'); ?></button>
            </div>
		</form>	
	</div>
</div>	
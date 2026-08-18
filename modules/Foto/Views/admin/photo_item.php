<div class="file-box order-item">
    <div class="file">
        <div class="flex">
            <div class="preview" style="width:170px;">
                <?php if(!empty($file['type']) && $file['type'] == 'image'): ?>
                    <a href="/foto/original/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                        <img src="/image/c/180/180/<?=$file['path']; ?>" alt="<?= $file['name']; ?>" style="width:100%;max-width:100%;" />
                    </a>
                <?php endif; ?>
            </div>
			<div class="fields">
			    <div class="flex" style="position:relative;">	
					<label class="name"> <input type="checkbox" name="photo[<?=$file['id'];?>][publish]" value="1" <?php if(!empty($file['publish'])):?>checked="checked"<?php endif;?> /> <?= $file['name']; ?></label>
					<a href="#" class="remove-file photo-remove" title="<?=lang('Foto.RemovePhoto');?>" data-title="<?=lang('Foto.RemovePhoto');?>" data-message="<?=lang('Foto.RemovePhotoConfirm');?>: <strong><?= $file['name']; ?></strong>" data-btn-ok="<?=lang('Foto.Remove');?>" data-btn-cancel="<?=lang('Foto.Cancel');?>" style="top:auto;"><i class="fa-solid fa-xmark"></i></a>
				</div>	
                <div class="tabs sm">
                    <div class="tabs-head">
                        <?php if(!empty($languages) && count($languages) > 1): ?>
                            <?php $l=0; foreach($languages as $lang): ?>
                            <div class="tab<?=$l==0 ? ' active' : ''; ?>"><?=$lang['short_name']; ?></div>
                            <?php ++$l; endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="tabs-content">
                        <?php $l=0; foreach($languages as $lang): ?>
                            <div class="lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Admin.file-menager.Caption');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="photo[<?=$file['id'];?>][lang][<?=$lang['id'];?>][caption]" value="<?=!empty($file['lang']) ? esc($file['lang'][$lang['id']]['caption']) : ''; ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Admin.file-menager.Author');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="photo[<?=$file['id'];?>][lang][<?=$lang['id'];?>][author]" value="<?=!empty($file['lang']) ? esc($file['lang'][$lang['id']]['author']) : ''; ?>" />
                                    </div>
                                </div>
                            </div>
                        <?php ++$l; endforeach; ?>
							<div class="form-row" style="padding-top:0px;">
                                    <div class="form-label">
                                        <label><?=lang('Foto.MainPhoto');?></label>
                                    </div>
                                    <div class="form-field">
									    <input type="hidden" name="photo_order[]" value="<?=$file['id'];?>" />
                                        <input type="radio" name="photo_main" value="<?=$file['id'];?>" <?php if(!empty($file['main'])):?>checked="checked"<?php endif;?> />
                                    </div>
                            </div>
                    </div>
                </div>
            </div>
     </div>			
    </div>
</div>
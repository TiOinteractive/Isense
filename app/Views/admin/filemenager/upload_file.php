<div class="file-box order-item"<?php if($multi): ?>data-no="<?=!empty($no) ? $no : 0; ?>"<?php endif; ?>>
    <div class="file">
        <a href="#" class="remove-file filemenager-file-remove" title="<?=lang('Admin.file-menager.Delete');?>" data-title="<?=lang('Admin.file-menager.DeleteFile');?>" data-message="<?=lang('Admin.file-menager.ConfirmInfo') . ': <strong>' . $file['basename'] . '</strong>';?>" data-btn-ok="<?=lang('Admin.file-menager.Delete');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><i class="fa-solid fa-xmark"></i></a>
        <input type="checkbox" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[publish]"<?=!empty($file['publish']) && $file['publish'] ? 'checked="checked"' : ''; ?> value="1" />
        <?php
            if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
                $size = filesize(WRITEPATH . 'uploads/' . $file['path']);
                $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
                $power = $size > 0 ? floor(log($size, 1024)) : 0;
                $file['size'] = number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
            }
        ?>
        <label class="name"><?= $file['basename']; ?><?php if(!empty($file['size'])): ?> (<?=$file['size']; ?>)<?php endif; ?></label>
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[id]" value="<?= !empty($file['id']) ? $file['id'] : ''; ?>" />
        <input class="order-field" type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[order]" value="<?= !empty($file['order']) ? $file['order'] : ''; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[path]" value="<?= $file['path']; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[name]" value="<?= $file['name']; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[basename]" value="<?= $file['basename']; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[type]" value="<?= $file['type']; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[ext]" value="<?= $file['ext']; ?>" />
        <div class="flex">
            <div class="preview">
                <?php if(!empty($file['type']) && $file['type'] == 'image'): ?>
                    <a href="/image/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                        <img src="/image/c/250/250/<?=$file['path']; ?>" alt="<?= $file['name']; ?>" />
                    </a>
                <?php else: ?>
                    <?php if(!empty($file['type']) && $file['type'] == 'audio'): ?>
                        <a href="/audio/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php elseif(!empty($file['type']) && $file['type'] == 'video'): ?>
                        <a href="/video/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php else: ?>
                        <a href="/file/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php endif; ?>
                    <span class="ext"><?=!empty($file['ext']) ? $file['ext'] : '&nbsp;'; ?></span>
                    </a>
                <?php endif; ?>
                <?php if(!empty($crop) and !empty($file)): ?>
                    <div class="crop_photo">
                        <a href="/tiocms/file-menager/crop" title="Kadruj zdjęcie" class="btn file-crop" data-file-id="<?=!empty($file['id']) ? $file['id'] : ''; ?>" data-file-path="<?=$file['path'];?>" data-title="<?=lang('Admin.file-menager.CropImage');?>" data-btn-ok="<?=lang('Admin.file-menager.CropImage');?>" data-width="<?=lang('Admin.file-menager.Width');?>" data-height="<?=lang('Admin.file-menager.Height');?>"  data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>" data-choose="<?=lang('Admin.file-menager.ChooseSize');?>"><?=lang('Admin.file-menager.CropImage');?></a>
                        <div class="list">
                            <?php if(!empty($file['crop_dimension'])):?>
                                <?php
                                $crop_data['file_path']=$file['crop_dimension']->path;
                                $crop_data['width']=$file['crop_dimension']->width;
                                $crop_data['height']=$file['crop_dimension']->height;
                                $crop_data['x']=$file['crop_dimension']->x;
                                $crop_data['y']=$file['crop_dimension']->y;
                                ?>
                                <?=view('admin/filemenager/crop_save', array('post'=>$crop_data)); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="fields">
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
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][caption]" value="<?=!empty($file['lang']) ? esc($file['lang'][$lang['id']]['caption']) : ''; ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Admin.file-menager.Author');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][author]" value="<?=!empty($file['lang']) ? esc($file['lang'][$lang['id']]['author']) : ''; ?>" />
                                    </div>
                                </div>
                            </div>
                        <?php ++$l; endforeach; ?>
						<?php  if(!empty($choose_main)):?>
							<div class="form-row" style="padding-top:0px;">
                                    <div class="form-label">
                                        <label><?=lang('Foto.MainPhoto');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="radio" name="photo_main" value="<?=(!empty($no) ? $no : 0);?>" />
                                    </div>
                            </div>
						<?php endif;?>	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 	
if(!empty($files)): $no=0; ?>
    <?php foreach($files as $file): ?>
        <div class="file-box order-item" data-no="<?=!empty($no) ? $no : 0; ?>" style="margin:10px;border-bottom:1px solid #ccc;">
            <div class="file">
			<input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][id]" value="<?=!empty($file) ? $file['id'] : ''; ?>" />
            <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][name]" value="<?=!empty($file) ? $file['name'] : ''; ?>" />
            <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][ext]" value="<?=!empty($file) ? $file['ext'] : ''; ?>" />
            <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][type]" value="<?=!empty($file) ? $file['type'] : ''; ?>" />
            <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][path]" value="<?=!empty($file) ? $file['path'] : ''; ?>" />
            <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][basename]" value="<?=!empty($file) ? $file['basename'] : ''; ?>" />
            <input class="order-field" type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][order]" value="<?=!empty($no) ? $no : 0; ?>">
			<input type="checkbox" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][publish]" checked="checked" value="1"> <label class="name"><?=$file['basename']; ?></label>
			<a class="remove-file" href="#" title="<?=lang('Admin.file-menager.Delete'); ?>" data-title="<?=lang('Admin.file-menager.DeleteFile');?>" data-message="<?=lang('Admin.file-menager.ConfirmInfo') . ': <strong>' . $file['basename'] . '</strong>';?>" data-btn-ok="<?=lang('Admin.file-menager.Delete');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><i class="fa-solid fa-xmark"></i></a>
                   <div class="form-row">
                       <div class="form-label">
                            <label><?=lang('Admin.file-menager.Caption'); ?></label>
                       </div>
                       <div class="form-field">
                           <input type="text" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[<?=!empty($no) ? $no : 0; ?>][caption]" value="">
                       </div>
                  </div>
            </div>
        </div>
    <?php 
	$no=$no+1;
	endforeach; ?>
<?php endif; ?>

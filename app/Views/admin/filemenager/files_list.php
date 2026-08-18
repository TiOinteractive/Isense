<?php 	
if(!empty($files)): ?>
    <?php foreach($files as $file): ?>
        <div class="file-box">
            <div class="file">
                <p class="name m"><?=$file['basename']; ?></p>
                <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[id]" value="<?=!empty($file) ? $file['id'] : ''; ?>" />
                <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[name]" value="<?=!empty($file) ? $file['name'] : ''; ?>" />
                <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[ext]" value="<?=!empty($file) ? $file['ext'] : ''; ?>" />
                <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[type]" value="<?=!empty($file) ? $file['type'] : ''; ?>" />
                <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[path]" value="<?=!empty($file) ? $file['path'] : ''; ?>" />
                <input type="hidden" name="<?=$name; ?><?php if(!empty($key_name)): ?>[<?=$key_name;?>]<?php endif; ?>[basename]" value="<?=!empty($file) ? $file['basename'] : ''; ?>" />
                <?php if($file['type'] == 'image'): ?>
                    <a href="/image/<?=$file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                        <img src="/image/c/250/250/<?=$file['path']; ?>" alt="<?=$file['name']; ?>" />
                    </a>
                <?php else: ?>
                    <?php if($file['type'] == 'audio'): ?>
                        <a href="/audio/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php elseif($file['type'] == 'video'): ?>
                        <a href="/video/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php else: ?>
                        <a href="/file/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php endif; ?>
                    <span class="ext"><?=$file['ext']; ?></span>
                    </a>
                <?php endif; ?>
                <div class="buttons"><a class="remove-file" href="#" title="<?=lang('Admin.file-menager.Delete'); ?>" data-title="<?=lang('Admin.file-menager.DeleteFile');?>" data-message="<?=lang('Admin.file-menager.ConfirmInfo') . ': <strong>' . $file['basename'] . '</strong>';?>" data-btn-ok="<?=lang('Admin.file-menager.Delete');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><i class="fa-solid fa-xmark"></i></a></div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

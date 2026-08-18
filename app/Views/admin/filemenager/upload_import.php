<div class="file-box order-item"<?php if($multi): ?>data-no="<?=!empty($no) ? $no : 0; ?>"<?php endif; ?>>
    <div class="file">
        <a href="#" class="remove-file filemenager-file-remove" title="<?=lang('Admin.file-menager.Delete');?>" data-title="<?=lang('Admin.file-menager.DeleteFile');?>" data-message="<?=lang('Admin.file-menager.ConfirmInfo') . ': <strong>' . $file['basename'] . '</strong>';?>" data-btn-ok="<?=lang('Admin.file-menager.Delete');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><i class="fa-solid fa-xmark"></i></a>
        <input type="checkbox" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[publish]"<?=!empty($file['publish']) && $file['publish'] ? 'checked="checked"' : ''; ?> value="1" /> <label class="name"><?= $file['basename']; ?></label>
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[id]" value="<?= !empty($file['id']) ? $file['id'] : ''; ?>" />
        <input class="order-field" type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[order]" value="<?= !empty($file['order']) ? $file['order'] : ''; ?>" />
        <input class="import-file" type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[path]" value="<?= $file['path']; ?>" />
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
            </div>
            <div class="fields">
                
            </div>
        </div>
    </div>
</div>
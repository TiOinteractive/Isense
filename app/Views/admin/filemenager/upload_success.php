<div class="file-box<?=!empty($server) ? ' file-server' : ' uploaded'; ?>">
    <div class="file-header">
        <?php if($file['type'] == 'image'): ?>
            <a class="preview" href="/image/<?=$file['path']; ?>" title="<?=lang('Admin.file-menager.Preview') ; ?>" target="_blank"><i class="fa-solid fa-magnifying-glass"></i></a>
        <?php elseif($file['type'] == 'audio'): ?>
            <a class="preview" href="/audio/<?=$file['path']; ?>" title="<?=lang('Admin.file-menager.Preview') ; ?>" target="_blank"><i class="fa-solid fa-magnifying-glass"></i></a>
        <?php elseif($file['type'] == 'video'): ?>
            <a class="preview" href="/video/<?=$file['path']; ?>" title="<?=lang('Admin.file-menager.Preview') ; ?>" target="_blank"><i class="fa-solid fa-magnifying-glass"></i></a>
        <?php else: ?>
            <a class="preview" href="/file/<?=$file['path']; ?>" title="<?=lang('Admin.file-menager.Preview') ; ?>" target="_blank"><i class="fa-solid fa-magnifying-glass"></i></a>
        <?php endif; ?>
        <a class="file-menager-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/delete_file/<?=!empty($module) ? $module : 'file-menager'; ?>/<?= $file['id']; ?>" data-title="<?=lang('Admin.file-menager.DeleteFile');?>" data-message="<?=lang('Admin.file-menager.ConfirmInfo');?>:<b><?= $file['name']; ?></b>" data-btn-ok="<?=lang('Admin.file-menager.DeleteFile');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>" title="<?=lang('Admin.file-menager.DeleteFile');?>"><i class="fa-solid fa-xmark"></i></a>
    </div>
    <div class="file">
        <?php if($file['type'] == 'image'): ?>
            <img src="/image/c/250/250/<?= $file['path']; ?>" alt="<?= $file['name']; ?>" />
        <?php else: ?>
            <span class="ext"><?=$file['ext']; ?></span>
        <?php endif; ?>
        <p class="name"><?= $file['name']; ?></p>
        <input type="<?=!empty($multi) && $multi ? 'checkbox' : 'radio'; ?>" name="file" value="<?= $file['id']; ?>" id="file-<?= $file['id']; ?>" class="file-input" />
        <label for="file-<?= $file['id']; ?>"></label>
    </div>
</div>
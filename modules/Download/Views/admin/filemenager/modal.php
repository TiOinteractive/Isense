<div class="file-menager modal">
    <div class="uploader-box">
        <form id="fileupload" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/download" method="POST" enctype="multipart/form-data">
            <div class="fileupload-buttonbar">
                <span class="drag-info"><?=lang('Admin.file-menager.DragAndDropFilesHereOr');?></span>
                <div class="buttons">
                  <span class="btn fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span><?=lang('Admin.file-menager.AddFiles');?></span>
                    <input type="file" name="file" multiple />
                  </span>
                  <!-- The global file processing state -->
                  <span class="fileupload-process"></span>
                </div>
                <!-- The global progress state -->
                <div class="col-lg-5 fileupload-progress fade">
                  <!-- The global progress bar -->
                  <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
                  </div>
                  <!-- The extended global progress state -->
                  <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            
            <div role="presentation" class="upload-results">
                <div class="files"></div>
            </div>
        </form>
    </div>
    <form class="form-list" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/add-file">
        <div class="files-list">
            <?php if(!empty($files)): ?>
                <?php foreach($files as $file): ?>
                    <div class="file-box">	<a class="file-menager-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/delete_file/<?=!empty($module) ? $module : 'file-menager'; ?>/<?= $file['id']; ?>" data-title="<?=lang('Admin.file-menager.DeleteFile');?>" data-message="<?=lang('Admin.file-menager.ConfirmInfo');?>:<b><?= $file['name']; ?></b>" data-btn-ok="<?=lang('Admin.file-menager.DeleteFile');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>" title="<?=lang('Admin.file-menager.DeleteFile');?>"><i class="fa-solid fa-xmark"></i></a>
                        <div class="file"> 
                            <?php if($file['type'] == 'image'): ?>
                                <img src="/image/c/250/250/<?= $file['path']; ?>" alt="<?= $file['name']; ?>" />
                            <?php else: ?>
                                <span class="ext"><?=$file['ext']; ?></span>
                            <?php endif; ?>
                            <p class="name"><?= $file['name']; ?></p>
                            <input class="file-input" type="checkbox" name="file" value="<?= $file['id']; ?>" id="file-<?= $file['id']; ?>" />
                            <label for="file-<?= $file['id']; ?>"></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </form>
</div>
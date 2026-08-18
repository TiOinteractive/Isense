<div class="file-menager modal">
    <div class="uploader-box">
        <form id="fileupload" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload" method="POST" enctype="multipart/form-data" data-multi="<?=!empty($multi) ? $multi : 0; ?>">
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
    <div class="filters">
        <form id="form-list-filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/filter-files">
            <input type="hidden" name="multi" value="<?=!empty($multi) ? $multi : 0; ?>" />
            <div class="filter">
                <label><?=lang('Admin.file-menager.FileTypes'); ?></label>
                <select name="type">
                    <option value=""><?=lang('Admin.file-menager.All'); ?></option>
                    <option value="image"<?php if(!empty($type) && $type == 'image'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.Images'); ?></option>
                    <option value="audio"<?php if(!empty($type) && $type == 'audio'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.AudioFiles'); ?></option>
                    <option value="video"<?php if(!empty($type) && $type == 'video'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.Videos'); ?></option>
                    <option value="document"<?php if(!empty($type) && $type == 'document'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.Documents'); ?></option>
                    <option value="presentation"<?php if(!empty($type) && $type == 'presentation'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.Presentations'); ?></option>
                    <option value="spreadsheet"<?php if(!empty($type) && $type == 'spreadsheet'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.Spreadsheets'); ?></option>
                    <option value="archive"<?php if(!empty($type) && $type == 'archive'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.Archives'); ?></option>
                    <option value="other"<?php if(!empty($type) && $type == 'other'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.Other'); ?></option>
                    <?php /*<option value="detached"<?php if(!empty($type) && $type == 'detached'): ?> selected="selected"<?php endif; ?>><?=lang('Admin.file-menager.Detached'); ?></option>*/ ?>
                </select>
            </div>
            <div class="filter">
                <label><?=lang('Admin.file-menager.UploadDate'); ?></label>
                <input class="datepicker-range" type="text" name="date" value="" autocomplete="off" />
            </div>
            <div class="filter">
                <label><?=lang('Admin.file-menager.Search'); ?></label>
                <input type="text" name="search" value="" />
            </div>
            <div class="filter">
                <button type="submit"><?=lang('Admin.file-menager.Filter'); ?></button>
            </div>
        </form>
    </div>
    <form class="form-list" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/add">
        <div class="files-list">
            <?php if(!empty($files)): ?>
                <?php foreach($files as $file): ?>
                    <?= view('admin/filemenager/upload_success', array('file' => $file, 'multi' => $multi, 'server' => true)); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-files"><?=lang('Admin.file-menager.NoFiles'); ?></p>
            <?php endif; ?>
        </div>
        <?php if(!empty($count) && !empty($count_all)): ?>
            <div class="count-box">
                <p><?=lang('Admin.file-menager.CountDisplayedFiles', [$count, $count_all]); ?></p>
                <a class="btn load-more<?=$count>=$count_all ? ' hidden' : ''; ?>" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/load-more" title="<?=lang('Admin.file-menager.LoadMore'); ?>"><?=lang('Admin.file-menager.LoadMore'); ?></a>
            </div>
        <?php endif; ?>
    </form>
</div>
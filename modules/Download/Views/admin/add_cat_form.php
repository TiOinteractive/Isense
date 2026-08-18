<div class="main-cont">
    <?php if (isset($breadcrumbs)) {
        echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if (!empty($category) && !empty($category['id'])): ?>
                <?=$category['name']; ?>
                <span><?= lang('Download.CategoryEdit'); ?></span>
            <?php else: ?>
            <?= lang('Download.NewCategoryAdd'); ?>
        <?php endif; ?>
        </div>
<?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form news-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/<?php echo $action; ?>/<?= $id_content; ?><?= !empty($category['id']) ? '/' . $category['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Download.BasicInformation'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                <div class="tabs-head">
                    <?php $l = 0;
                    foreach ($languages as $lang): ?>
                        <div class="tab<?= $l == 0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                        <?php ++$l;
                    endforeach; ?>
                </div>
                <div class="tabs-content">
                <?php endif; ?>
<?php $l = 0;
foreach ($languages as $lang): ?>
                        <div class="link-box lang-<?= $lang['id']; ?> tab-item<?= $l == 0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?= lang('Download.Name'); ?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][name]" value="<?= !empty($category['lang']) ? esc($category['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-field">
                                    <div class="files-list order-box" style="margin-bottom:20px;">
                                    <?php if (!empty($category['files'][$lang['id']])): ?>
                                        <?= view('Modules\Download\Views\admin\filemenager\upload_file', array('files' => $category['files'][$lang['id']], 'field' => 'files')); ?>
    <?php endif; ?>
                                    </div> 
                        <?php if (!empty($category)): ?>							   
                                        <a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/open-menager" title="<?= lang('Download.AddFiles'); ?>" class="btn file-menager" data-multi="true" data-field-name="files[lang][<?= $lang['id']; ?>]" data-title="<?= lang('Admin.file-menager.FileMenager'); ?>" data-btn-ok="<?= lang('Admin.file-menager.Select'); ?>" data-btn-cancel="<?= lang('Admin.file-menager.Cancel'); ?>"><?= lang('Download.AddFiles'); ?></a>
    <?php endif; ?>	
                                </div>
                            </div>
                        </div>
    <?php ++$l;
endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Download.CategorySettings'); ?></h3>
            </div>

            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Download.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if (!empty($category['publish']) && $category['publish']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Download.Save'); ?></button>
            </div>
        </form>
    </div>
</div>	
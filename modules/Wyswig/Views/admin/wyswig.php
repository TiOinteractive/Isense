<div class="form-row nag">
    <h3><?=lang('Wyswig.SectionContent'); ?></h3>
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
            <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Wyswig.Content');?></label>
                    </div>
                    <div class="form-field">
                        <textarea class="wyswig-textarea" name="form_data[lang][<?=$lang['id']; ?>][content]"><?=!empty($form_data['lang'][$lang['id']]['content']) ? $form_data['lang'][$lang['id']]['content'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
        <?php ++$l; endforeach; ?>
    <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
</div>
<!-- Grafika tła sekcji — wspólna dla wszystkich wersji językowych (poza zakładkami języków) -->
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Wyswig.BackgroundImage');?></label>
    </div>
    <div class="form-field">
        <div class="files-list">
            <?php if(!empty($form_data['photo'])): ?>
                <?=view('admin/filemenager/files_list', array('files'=>array($form_data['photo']), 'name'=>'form_data', 'key_name'=>'photo')); ?>
            <?php endif; ?>
        </div>
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Wyswig.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="photo" data-type="image" data-field-name="form_data" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Wyswig.AddChangePhoto');?></a>
        <span class="s">(<?=lang('Wyswig.BackgroundImageInfo');?>)</span>
    </div>
</div>

<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.config.TitleCinema');?></label>
    </div>
    <div class="form-field">
        <input type="text" name="config[lang][<?=$lang['id']; ?>][title2]" value="<?=!empty($page_content['config']) && !empty($page_content['config']['lang'][$lang['id']]['title2']) ? esc($page_content['config']['lang'][$lang['id']]['title2']) : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.config.SubtitleCinema');?></label>
    </div>
    <div class="form-field">
        <input type="text" name="config[lang][<?=$lang['id']; ?>][subtitle2]" value="<?=!empty($page_content['config']) && !empty($page_content['config']['lang'][$lang['id']]['subtitle2']) ? esc($page_content['config']['lang'][$lang['id']]['subtitle2']) : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.config.UrlCinema');?></label>
    </div>
    <div class="form-field">
        <input type="text" name="config[lang][<?=$lang['id']; ?>][url2]" value="<?=!empty($page_content['config']) && !empty($page_content['config']['lang'][$lang['id']]['url2']) ? esc($page_content['config']['lang'][$lang['id']]['url2']) : ''; ?>" />
    </div>
</div>
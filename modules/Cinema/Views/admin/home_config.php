<div class="form-row">
    <div class="form-label">
        <label><?=lang('Cinema.Option');?></label>
    </div>
    <div class="form-field">
        <select name="config[option]">
            <option value="today"><?=lang('Cinema.TodayInCinema'); ?></option>
            <option value="premieres"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='premieres'):?> selected="selected"<?php endif; ?>><?=lang('Cinema.Premieres'); ?></option>
            <option value="announcements"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='announcements'):?> selected="selected"<?php endif; ?>><?=lang('Cinema.Announcements'); ?></option>
        </select>
    </div>
</div>
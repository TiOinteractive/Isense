<div class="module-config-box <?=$module; ?>-<?=$id; ?>" data-module="<?=$module; ?>" data-id="<?=$id; ?>">
    <div class="header">
        <h3><?=lang('Newsletter.emailbuilder.modules.Banner'); ?></h3>
        <span class="close"><svg viewBox="0 0 24 24"><path d="M12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22Z"/><path d="M16.707,7.293a1,1,0,0,0-1.414,0L12,10.586,8.707,7.293A1,1,0,1,0,7.293,8.707L10.586,12,7.293,15.293a1,1,0,1,0,1.414,1.414L12,13.414l3.293,3.293a1,1,0,0,0,1.414-1.414L13.414,12l3.293-3.293A1,1,0,0,0,16.707,7.293Z"/></svg></span>
    </div>
    <div class="field-box">
        <label><?=lang('Newsletter.emailbuilder.Photo'); ?></label>
        <div class="photo">
            <img src="<?=!empty($data) && !empty($data['photo_src']) ? $data['photo_src'] : ''; ?>" />
            <div class="options">
                <span class="change<?=empty($data) || empty($data['photo_src']) ? ' hidden' : ''; ?>" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" data-multi="false" data-key="photo" data-type="image" data-field-name="photo" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><svg viewBox="0 0 24 24"><path d="M13.9392 4.99988L19.0002 10.0609L9.06201 19.999C8.78513 20.2758 8.44079 20.4757 8.06303 20.5787L2.94734 21.9739C2.38747 22.1266 1.87374 21.6128 2.02643 21.053L3.42162 15.9373C3.52465 15.5595 3.72447 15.2152 4.00135 14.9383L13.9392 4.99988ZM21.0303 2.96997C22.4278 4.36743 22.4278 6.63317 21.0303 8.03063L20.0602 8.99988L14.9992 3.93988L15.9697 2.96997C17.3671 1.57251 19.6329 1.57251 21.0303 2.96997Z"/></svg></span>
                <span class="remove<?=empty($data) || empty($data['photo_src']) ? ' hidden' : ''; ?>"><svg viewBox="0 0 24 24"><path d="M12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22Z"/><path d="M16.707,7.293a1,1,0,0,0-1.414,0L12,10.586,8.707,7.293A1,1,0,1,0,7.293,8.707L10.586,12,7.293,15.293a1,1,0,1,0,1.414,1.414L12,13.414l3.293,3.293a1,1,0,0,0,1.414-1.414L13.414,12l3.293-3.293A1,1,0,0,0,16.707,7.293Z"/></svg></span>
                <span class="add<?=!empty($data) && !empty($data['photo_src']) ? ' hidden' : ''; ?>" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" data-multi="false" data-key="photo" data-type="image" data-field-name="photo" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><svg viewBox="0 0 24 24"><path d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM12 7C11.6203 7 11.3065 7.28215 11.2568 7.64823L11.25 7.75V11.25H7.75C7.33579 11.25 7 11.5858 7 12C7 12.3797 7.28215 12.6935 7.64823 12.7432L7.75 12.75H11.25V16.25C11.25 16.6642 11.5858 17 12 17C12.3797 17 12.6935 16.7178 12.7432 16.3518L12.75 16.25V12.75H16.25C16.6642 12.75 17 12.4142 17 12C17 11.6203 16.7178 11.3065 16.3518 11.2568L16.25 11.25H12.75V7.75C12.75 7.33579 12.4142 7 12 7Z"/></svg></span>
            </div>
            <input class="module-config" type="hidden" name="photo_src" value="<?=!empty($data) && !empty($data['photo_src']) ? $data['photo_src'] : ''; ?>" />
        </div>
    </div>
    <div class="field-box">
        <label><?=lang('Newsletter.emailbuilder.PhotoCaption'); ?></label>
        <input class="module-config" type="text" name="photo_alt" value="<?=!empty($data) && !empty($data['photo_alt']) ? $data['photo_alt'] : ''; ?>" />
    </div>
    <div class="field-box">
        <label><?=lang('Newsletter.emailbuilder.UrlAddress'); ?></label>
        <input class="module-config" type="text" name="url_href" value="<?=!empty($data) && !empty($data['url_href']) ? $data['url_href'] : ''; ?>" />
    </div>
    <div class="field-box">
        <label><?=lang('Newsletter.emailbuilder.UrlTitle'); ?></label>
        <input class="module-config" type="text" name="url_title" value="<?=!empty($data) && !empty($data['url_title']) ? $data['url_title'] : ''; ?>" />
    </div>
</div>
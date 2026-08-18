<div class="form-row">
    <div class="form-label">
        <label><?=lang('Catalog.ShowCats');?></label>
    </div>
    <div class="form-field">
        <div class="form-cols">
            <?php if(!empty($lists)): ?>
                <?php foreach($lists as $list): ?>
                    <div class="form-col col-2">
                        <input type="checkbox" name="config[lists][]" value="<?=$list['id_content']; ?>" id="config-lists-<?=$list['id_content']; ?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['lists']) && in_array($list['id_content'], $page_content['config']['lists'])):?> checked="checked"<?php endif; ?> />
                        <label for="config-lists-<?=$list['id_content']; ?>"><?=(!empty($list['tree_name']) ? $list['tree_name'] : '') . $list['name'] . ''; ?></label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
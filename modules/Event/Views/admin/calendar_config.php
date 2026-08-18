<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php if(!empty($lists)): ?>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Event.Lists');?></label>
        </div>
        <div class="form-field">
            <div class="form-cols">
                <?php foreach($lists as $list): ?>
                    <div class="form-col col-10">
                        <input type="checkbox" name="config[lists][]" value="<?=$list['id_content']; ?>" id="config-lists-<?=$list['id_content']; ?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['lists']) && in_array($list['id_content'], $page_content['config']['lists'])):?> checked="checked"<?php endif; ?> />
                        <label for="config-lists-<?=$list['id_content']; ?>"><?=(!empty($list['tree_name']) ? $list['tree_name'] : '') . $list['name'] . ' [' . (!empty($list['content_name']) ? $list['content_name'] . ' - ' : '') . '' . lang('Admin.page_content.Tab') . ' ' . ($list['order'] + 1) . ']'; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

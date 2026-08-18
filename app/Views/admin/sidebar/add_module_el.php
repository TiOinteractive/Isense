<div class="module order-item" data-no="<?=isset($no) ? $no : 0; ?>">
    <input class="order-field" type="hidden" name="content[<?=isset($no) ? $no : 0; ?>][order]" value="<?=!empty($content['order']) ? $content['order'] : ''; ?>" />
    <input type="hidden" name="content[<?=isset($no) ? $no : 0; ?>][id]" value="<?=!empty($content['id']) ? $content['id'] : ''; ?>" />
    <input type="hidden" name="content[<?=isset($no) ? $no : 0; ?>][id_module_element]" value="<?=!empty($content['id_module_element']) ? $content['id_module_element'] : ''; ?>" />
    <input class="publish" type="checkbox" name="content[<?=isset($no) ? $no : 0; ?>][publish]" value="1"<?=!empty($content) && !empty($content['publish']) && $content['publish'] ? ' checked="checked"' : ''; ?> />
    <?php if(!empty($module_elements)): ?>
        <select name="content[<?=isset($no) ? $no : 0; ?>][id_element]">
            <option value=""></option>
            <?php foreach($module_elements as $element): ?>
                <option value="<?=$element['id']; ?>"<?=!empty($content) && !empty($content['id_module_element']) && $content['id_module_element']==$element['id'] ? ' selected="selected"' : '';?>><?=$element['module_name']; ?>: <?=$element['name']; ?></option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
    <div class="delete">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/sidebar/delete-module-el<?=!empty($content) ? '/' . $content['id'] : ''; ?>" class="delete-module-element" title="<?=lang('Admin.sidebar.Remove'); ?>" data-title="<?=lang('Admin.sidebar.DeleteModule'); ?>" data-message="<?=lang('Admin.sidebar.DeleteModuleConfirm'); ?>" data-btn-ok="<?=lang('Admin.sidebar.Remove'); ?>" data-btn-cancel="<?=lang('Admin.sidebar.Cancel'); ?>" data-btn-close="<?=lang('Admin.sidebar.Close'); ?>"><i class="fa-regular fa-trash-can"></i></a>
    </div>
</div>
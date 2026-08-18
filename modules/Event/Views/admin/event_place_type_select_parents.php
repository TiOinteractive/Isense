<option value="<?=$type['id']; ?>"<?php if(!empty($id_parent) && $id_parent == $type['id']):?> selected="selected"<?php endif; ?>><?=!empty($type['level']) ? (str_repeat('&nbsp;&nbsp;&nbsp;', $type['level']) . ($item_no < $count ? '┝' : '┕') . '&nbsp;') : ''; ?><?=$type['name']; ?></option>
<?php if(!empty($type['list'])): ?>
    <?php foreach($type['list'] as $j=>$t): ?>
        <?= view('\Modules\Event\Views\admin/event_place_type_select_parents', array('type'=>$t, 'id_parent'=>!empty($id_parent) ? $id_parent : 0, 'count'=>count($type['list']), 'item_no'=>$j+1)); ?>
    <?php endforeach; ?>
<?php endif; ?>

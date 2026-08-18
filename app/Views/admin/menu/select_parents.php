<option value="<?=$item['id']; ?>"<?php if(!empty($id_parent) && $id_parent == $item['id']):?> selected="selected"<?php endif; ?>><?=!empty($item['level']) ? (str_repeat('&nbsp;&nbsp;&nbsp;', $item['level']) . ($item_no < $count ? '┝' : '┕') . '&nbsp;') : ''; ?><?=$item['name']; ?></option>
<?php if(!empty($item['list'])): ?>
    <?php foreach($item['list'] as $j=>$p): ?>
        <?= view('admin/menu/select_parents', array('item'=>$p, 'id_parent'=>!empty($id_parent) ? $id_parent : 0, 'count'=>count($item['list']), 'item_no'=>$j+1)); ?>
    <?php endforeach; ?>
<?php endif; ?>

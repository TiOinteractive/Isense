<option value="<?=$page['id']; ?>"<?php if(!empty($re_id) && $re_id == $page['id']):?> selected="selected"<?php endif; ?>><?=!empty($page['level']) ? (str_repeat('&nbsp;&nbsp;&nbsp;', $page['level']) . ($item_no < $count ? '┝' : '┕') . '&nbsp;') : ''; ?><?=$page['name']; ?></option>
<?php if(!empty($page['list'])): ?>
    <?php foreach($page['list'] as $j=>$p): ?>
        <?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($re_id) ? $re_id : 0, 'count'=>count($page['list']), 'item_no'=>$j+1)); ?>
    <?php endforeach; ?>
<?php endif; ?>

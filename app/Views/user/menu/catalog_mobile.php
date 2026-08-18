<?php 
if(!empty($menu)): ?>
    <?php foreach($menu as $m): ?>
        <option value="<?=$m['url']; ?>" <?=isset($m['active']) && $m['active'] ? ' selected="selected" ' : ''; ?>><?=$m['name']; ?></option>
    <?php endforeach; ?>
<?php endif; ?>
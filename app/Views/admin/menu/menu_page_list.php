<?php if(!empty($list)): ?>
    <?php foreach ($list as $el): ?>		
        <div class="element checkbox">
            <input type="checkbox" name="available[<?= $m; ?>][]" value="<?= $el['id']; ?>" id="<?= 'element-' . $m . '-' . $el['id']; ?>" />
            <label for="<?= 'element-' . $m . '-' . $el['id']; ?>"><?= $el['name']; ?></label>
            <?php
            if (isset($el['list'])) {
                echo view('admin/menu/menu_page_list', array('list' => $el['list'], 'level' => $el['level'], 'm' => $m));
            }
            ?>
        </div>	
    <?php endforeach; ?>
<?php endif; ?>

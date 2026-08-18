<div class="pagination-order">
    <?php if(!empty($order_list)): ?>
        <div class="order">
            <select class="order-select" name="order">
                <?php foreach($order_list as $order): ?>
                <option value="<?=$order['field']; ?>"<?=!empty($filters['order']) && $filters['order'] == $order['field'] ? ' selected="selected"' : ''; ?>><?=$order['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
<?php if(!empty($on_page_list)): ?>
        <div class="on-page">
            <select class="on-page-select" name="on_page">
                <?php foreach($on_page_list as $no=>$name): ?>
                <option value="<?=$no; ?>"<?=!empty($filters['on_page']) && $filters['on_page'] == $no ? ' selected="selected"' : ''; ?>><?=$name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
    <?php if ($pager): ?>
        <?=$pager->links(); ?>
    <?php endif; ?>  
</div>
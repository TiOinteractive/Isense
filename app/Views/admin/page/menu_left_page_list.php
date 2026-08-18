<div class="head"><a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page" title="<?=lang('Admin.page.PagesList');?>"><?=lang('Admin.page.PagesList');?></a></div>
<div class="list">
    <?php if(!empty($left_pages)): ?>
        <?php foreach($left_pages as $k=>$left_page): ?>
            <?= view('admin/page/menu_left_list_item', array('left_page'=>$left_page, 'count'=>count($left_pages), 'item_no'=>$k+1)); ?>
        <?php endforeach;  ?>
    <?php endif; ?>
</div>
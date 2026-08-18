<div class="order-group">
<div class="list-row list-row-<?=$cat['id']; ?> level-<?=$cat['level']; ?><?= $item_no<$count ? ' full' : ''; ?>"> 
   <div class="list-col">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/foto/edit-category/<?=$cat['id_page_cont']; ?>/<?=$cat['id']; ?>" title="<?=$cat['name']; ?>" class="name"><?=$cat['name']; ?></a>
		<input type="hidden" class="order-field" name="cat_order[<?=$cat['re_id']; ?>][]" value="<?=$cat['id'];?>" />
    </div>
	<div class="list-col center w100">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/foto/edit-category/<?=$cat['id_page_cont']; ?>/<?=$cat['id']; ?>" title="<?=lang('Admin.page.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
    </div>
	 <div class="list-col center w100">
        <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/foto/publish-category/<?=$cat['id']; ?>" title="<?=lang('Admin.page.Publish');?>"><?php if(!empty($cat['publish']) && $cat['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
    </div>
	<div class="list-col center w100">
        <a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/foto/delete-category/<?=$cat['id']; ?>" data-title="<?=lang('Foto.DeleteCategory');?>" data-message="<?=lang('Foto.ConfirmDeleteCategory') . ': <b>' . $cat['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Foto.Remove');?>" data-btn-cancel="<?=lang('Admin.page.Cancel');?>" title="<?=lang('Foto.DeleteCategory');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a>
    </div>
</div>	
<?php if(!empty($cat['list'])): ?>
<div class="order-group-inside">
    <?php foreach($cat['list'] as $j=>$p): ?>
        <?= view('Modules\Foto\Views\admin\category_list_item', array('cat' => $p, 'count'=>count($cat['list']), 'item_no'=>$j+1)); ?>
    <?php endforeach; ?>
	</div>
<?php endif; ?>
</div>

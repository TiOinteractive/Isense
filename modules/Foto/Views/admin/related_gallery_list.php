<div class="list">
    <div class="list-row list-head">
        <div class="list-col center w40">&nbsp;</div>
		<div class="list-col w50"> <?=lang('Foto.Id');?></div>
        <div class="list-col w50">&nbsp;</div>
        <div class="list-col">
            <?=lang('Foto.Name');?>
        </div>
		<div class="list-col w100">
			<?=lang('Foto.CreatedAt');?>
		</div>
    </div>
    <?php if(!empty($products)): ?>
        <?php foreach($products as $k=>$product): ?>
            <?= view('Modules\Foto\Views\admin\related_gallery_list_item', array('product' => $product)); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="list-row no-list-result"><?=lang('Foto.NoListResult'); ?></div>
    <?php endif; ?>
</div>
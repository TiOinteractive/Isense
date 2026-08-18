<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?=lang('Foto.CategoriesList'); ?></h3>
</div>
<p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/add-category/<?=$id_content; ?>" title="<?=lang('Foto.AddCategory');?>"><i class="fa-solid fa-plus"></i> <?=lang('Foto.AddCategory');?></a></p>
	<form method="post" id="order-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/SaveOrderCategory/<?=$id_content; ?>">	
		<div class="list order-sortable">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Admin.page.Name');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.page.Edit');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.page.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.page.Delete');?>
                </div>
            </div>
            <?php if(!empty($lists)): ?>
			   <div class="order-list">
                <?php foreach($lists as $k=>$cat): ?>
                   <?= view('Modules\Foto\Views\admin\category_list_item', array('cat'=>$cat, 'count'=>count($lists), 'item_no'=>$k+1)); ?>
                <?php endforeach;  ?>
			 </div>	
            <?php endif; ?>
        </div>
    </form>
</div>
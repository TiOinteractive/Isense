
<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Flavors.CategoriesList'); ?>
	   </div>
	<p> <a class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/add-category" title="<?=lang('Flavors.AddCategory'); ?>"><i class="fa-solid fa-plus"></i> <?=lang('Flavors.AddCategory'); ?></a></p>
	<form method="post" id="order-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/SaveOrderCategory">	
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
            <?php if(!empty($categories)): ?>
			   <div class="order-list">
                <?php foreach($categories as $k=>$cat): ?>
                   <?= view('Modules\Flavors\Views\admin\category_list_item', array('cat'=>$cat, 'count'=>count($categories), 'item_no'=>$k+1)); ?>
                <?php endforeach;  ?>
			 </div>	
            <?php endif; ?>
        </div>
    </form>
</div>
</div>
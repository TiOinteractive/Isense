<form class="form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/Flavors/save-additional-categories">
<div class="form-row" style="margin:0px;">
<div class="form-field" style="width:100%;">
    <?php if(!empty($categories)): ?>
        <div class="list cat-list">
            <?php foreach($categories as $k=>$c): ?>
                <?= view('Modules\Flavors\Views\admin\checkbox_parents', array('category'=>$c, 'count'=>count($categories),'item_no'=>$k+1,'additional'=>!empty($post['categories']) ? $post['categories'] : array())); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>					
</div>
</form>
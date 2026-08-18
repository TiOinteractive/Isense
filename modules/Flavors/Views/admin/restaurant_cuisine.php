<form class="form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/Flavors/save-cuisine">
<div class="form-row" style="margin:0px;">
<div class="form-field" style="width:100%;">
    <?php if(!empty($categories)): ?>
        <div class="list cat-list">
            <?php foreach($categories as $k=>$c): ?>
			 <div class="list-row list-row-<?=$c['id']; ?>">
	<div class="list-col">
	 <label for="categories-<?=$c['id']; ?>"> <?=$c['name']; ?></label>
    </div>
	<div class="list-col w100">
		       <input type="checkbox" name="categories[<?=$c['id']; ?>]" value="<?=$c['id']; ?>" id="categories-<?=$c['id']; ?>"<?php if(!empty($post['cuisine']) && in_array($c['id'], $post['cuisine'])): ?> checked="checked"<?php endif; ?> />
	</div>
</div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>					
</div>
</form>
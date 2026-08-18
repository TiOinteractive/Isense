<div class="list-row list-row-<?=$category['id']; ?> level-<?=$category['level']; ?><?= $item_no<$count ? ' full' : ''; ?> <?php if($item_no%2==0):?> no-bg <?php endif; ?>">
	<div class="list-col">
      <?php if(!empty($category['list'])): ?><a class="show-btn" href="javascript:ShowHideSub(<?=$category['id']; ?>);"><i class="fa-solid fa-chevron-right"></i></a> <?php endif; ?>  
	 <label for="categories-<?=$category['id']; ?>"> <?=$category['name']; ?></label>
    </div>
	<div class="list-col w100">
		       <input type="checkbox" name="categories[<?=$category['id']; ?>]" value="<?=$category['id']; ?>" id="categories-<?=$category['id']; ?>"<?php if(!empty($additional) && in_array($category['id'], $additional)): ?> checked="checked"<?php endif; ?> />
	</div>
</div>
<?php if(!empty($category['list'])): ?>
   <div class="sub-cat parent-<?=$category['id'];?> hidden">
    <?php foreach($category['list'] as $j=>$c): ?>
        <?= view('Modules\Category\Views\admin\checkbox_parents', array('category' => $c, 'count'=>count($category['list']), 'item_no'=>$j+1,'additional'=>!empty($post['categories']) ? $post['categories'] : array())); ?>
    <?php endforeach; ?>
	</div>
<?php endif; ?>
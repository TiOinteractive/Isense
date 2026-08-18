<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Flavors.TagsList'); ?>
	   </div>
	   
	    <form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/tags" method="get">
			<input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
			<input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
			<div class="filter search-name">
				<input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" placeholder="<?=lang('Flavors.FindTag');?>" />
				<button type="submit"><?=lang('Flavors.Search'); ?></button>
			</div>
		</form>
	   
	   
	    <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
		
		
		
		<div class="list">
            <div class="list-row list-head">
					<div class="list-col   <?php if(!empty($filters['order']) && $filters['order']=="value,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="value,desc"):?>desc<?php endif; ?>" data-order="value">
						<?= lang('Flavors.TagValue'); ?>
					</div>
					<div class="list-col center w120   <?php if(!empty($filters['order']) && $filters['order']=="date,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="date,desc"):?>desc<?php endif; ?>" data-order="date">
						<?= lang('Flavors.RestaurantAdded'); ?>
					</div>
					<div class="list-col center w90">
						<?= lang('Flavors.Edit'); ?>
					</div>
					<div class="list-col center w90">
						<?= lang('Flavors.Delete'); ?>
					</div>
			</div>
			<?php if(!empty($tags_list)):?>
			  <?php foreach($tags_list as $list):?>
			    <div class="list-row comment_<?=$list['id'];?>">
				      <div class="list-col comment_show">
					     <div class="all"><?=$list['value'];?></div>
					  </div>
				      <div class="list-col w120 center">
					     <?=date("d.m.Y H:i",strtotime($list['created_at']));?>
					  </div>
					  <div class="list-col center w90">
						<a class="edit-comment-btn" data-title="<?= lang('Flavors.EditTag'); ?>" data-btn-ok="<?= lang('Flavors.Save'); ?>" data-btn-cancel="<?= lang('Flavors.Cancel'); ?>" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edittag/<?= $list['id']; ?>" title="<?= lang('Flavors.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
					  </div>
					<div class="list-col center w90">
						    <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))): ?><a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/deletetag/<?= $list['id']; ?>" data-title="<?= lang('Flavors.DeleteTag'); ?>" data-message="<?= lang('Flavors.TagDeleteConfirm') . ': <b>' .$list['value'].'</b>?'; ?>" data-btn-ok="<?= lang('Flavors.Remove'); ?>" data-btn-cancel="<?= lang('Flavors.Cancel'); ?>" title="<?= lang('Flavors.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
					  </div>
			    </div>
			  <?php endforeach;?>
			<?php else:?>
			<div class="list-row no-list-result"><?= lang('Flavors.TagsNoListResult'); ?></div>
			<?php endif;?>
		</div>
		<?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	</div>
</div>	
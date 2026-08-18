<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Flavors.RestaurantsList'); ?>
	   </div>
	   <p> <a class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/add-restaurant" title="<?=lang('Flavors.AddRestaurant'); ?>"><i class="fa-solid fa-plus"></i> <?=lang('Flavors.AddRestaurant'); ?></a></p>
	   <?= view('Modules\Flavors\Views\admin\restaurants_filters', array()); ?>
	   <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
		<form method="post" id="order-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/SaveOrderRestaurant">	   
		<div class="list order-sortable">
            <div class="list-row list-head">
			<div class="list-col w100"></div>
                <div class="list-col   <?php if(!empty($filters['order']) && $filters['order']=="name,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="name,desc"):?>desc<?php endif; ?>" data-order="name">
                    <?= lang('Flavors.RestaurantName'); ?>
                </div>
                <div class="list-col w200 hide-500">
                    <?= lang('Flavors.RestaurantAddress'); ?>
                </div>
				<div class="list-col w100 hide-500 <?php if(!empty($filters['order']) && $filters['order']=="date,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="date,desc"):?>desc<?php endif; ?>" data-order="date">
                    <?= lang('Flavors.RestaurantAdded'); ?>
                </div>
				<div class="list-col w100 center hide-500 <?php if(!empty($filters['order']) && $filters['order']=="views,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="views,desc"):?>desc<?php endif; ?>" data-order="views">
                    <?= lang('Flavors.Views'); ?>
                </div>
                <div class="list-col center w100 hide-500   <?php if(!empty($filters['order']) && $filters['order']=="publish,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="publish,desc"):?>desc<?php endif; ?>" data-order="publish">
                    <?= lang('Flavors.Publish'); ?>
                </div>
				<div class="list-col center w100 hide-500   <?php if(!empty($filters['order']) && $filters['order']=="awarded,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="awarded,desc"):?>desc<?php endif; ?>" data-order="awarded">
                    <?= lang('Flavors.RestaurantAwarded'); ?>
                </div>
				<div class="list-col center w100 hide-500   <?php if(!empty($filters['order']) && $filters['order']=="recommended,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="recommended,desc"):?>desc<?php endif; ?>" data-order="recommended">
                    <?= lang('Flavors.RestaurantRecommended'); ?>
                </div>
				<div class="list-col center w100 hide-500   <?php if(!empty($filters['order']) && $filters['order']=="archives,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="archives,desc"):?>desc<?php endif; ?>" data-order="archives">
                    <?= lang('Flavors.RestaurantArchive'); ?>
                </div>
				<div class="list-col center w80 hide-1200">
                    <?= lang('Flavors.Edit'); ?>
                </div>
                <div class="list-col center w100">
            <?= lang('Flavors.Delete'); ?>
                </div>
            </div>
			 <?php if (!empty($list)): ?>
			 <div <?php if(empty($filters['order'])): ?>class="order-list"<?php endif;?>>
			   <?php foreach ($list as $lokal):?>
			     <div class="list-row">
				   <div class="list-col w100">
				    <?php if(!empty($lokal['photo']['path'])):?><a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-restaurant/<?=$lokal['id'];?>" title="<?=lang('Flavors.RestaurantEdit'); ?>"><img src="/image/c/90/90/<?=$lokal['photo']['path'];?>" /></a> <?php endif;?>
					<input type="hidden" class="order-field" name="restaurant_order[]" value="<?=$lokal['id'];?>" />
				   </div>
				   <div class="list-col">
				     <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-restaurant/<?=$lokal['id'];?>" title="<?=lang('Flavors.RestaurantEdit'); ?>"><?=esc($lokal['name']);?></a>
				   </div>
				    <div class="list-col w200 hide-500">
					 <?=$lokal['address'];?><br /><?=$lokal['city'];?>
					</div>
					<div class="list-col w100 hide-500">
					  <?=date("d.m.Y",strtotime($lokal['created_at']));?>
					</div>
					<div class="list-col w100 center hide-500">
					 <?=$lokal['views'];?>
					</div>
					<div class="list-col center w100 hide-500">
                        <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/publishrestaurant/<?= $lokal['id']; ?>" title="<?= lang('Flavors.Publish'); ?>"><?php if (!empty($lokal['publish']) && $lokal['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                     </div>
					<div class="list-col center w100 hide-500">
                        <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/awardrestaurant/<?= $lokal['id']; ?>" title="<?= lang('Flavors.RestaurantAwarded'); ?>"><?php if (!empty($lokal['awarded']) && $lokal['awarded']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                     </div>
					<div class="list-col center w100 hide-500">
                        <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/recommendrestaurant/<?= $lokal['id']; ?>" title="<?= lang('Flavors.RestaurantRecommended'); ?>"><?php if (!empty($lokal['recommended']) && $lokal['recommended']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                     </div>
					<div class="list-col center w100 hide-500">
                        <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/archiverestaurant/<?= $lokal['id']; ?>" title="<?= lang('Flavors.RestaurantArchive'); ?>"><?php if (!empty($lokal['archives']) && $lokal['archives']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                     </div>
					<div class="list-col center w80">
					  <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-restaurant/<?=$lokal['id'];?>" title="<?=lang('Flavors.RestaurantEdit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
					</div>
					<div class="list-col center w100">
					   <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))): ?><a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/deleterestaurant/<?= $lokal['id']; ?>" data-title="<?= lang('Flavors.DeleteRestaurant'); ?>" data-message="<?= lang('Flavors.RestaurantDeleteConfirm') . ': <b>' . esc($lokal['name']) . '</b>'; ?>" data-btn-ok="<?= lang('Flavors.Remove'); ?>" data-btn-cancel="<?= lang('Flavors.Cancel'); ?>" title="<?= lang('Flavors.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
					</div>
			     </div>
			   <?php endforeach;?>
			   </div>
		  <?php else: ?>	   
                 <div class="list-row no-list-result"><?= lang('Flavors.RestaurantNoListResult'); ?></div>
            <?php endif; ?>	
		</div>	
		</form>		
	   <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	</div>
</div>	
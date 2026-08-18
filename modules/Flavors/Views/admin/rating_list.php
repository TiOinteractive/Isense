<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Flavors.RatingList'); ?>
	   </div>
	   
	    <form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/grades" method="get">
			<input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
			<input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
			<div class="filter search-name">
				<input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" placeholder="<?=lang('Flavors.FindRating');?>" />
				<button type="submit"><?=lang('Flavors.Search'); ?></button>
			</div>
		</form>
	   
	   
	    <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
		
		
		
		<div class="list">
            <div class="list-row list-head">
				   <div class="list-col w200">
					<?= lang('Flavors.UserName'); ?>
					</div>
					<div class="list-col   <?php if(!empty($filters['order']) && $filters['order']=="name,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="name,desc"):?>desc<?php endif; ?>" data-order="name">
						<?= lang('Flavors.RestaurantName'); ?>
					</div>
					<div class="list-col center w120   <?php if(!empty($filters['order']) && $filters['order']=="rating,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="rating,desc"):?>desc<?php endif; ?>" data-order="rating">
						<?= lang('Flavors.RestaurantAvgRating'); ?>
					</div>
					<div class="list-col center w120">
						<?= lang('Flavors.RatingType_1'); ?>
					</div>
					<div class="list-col center w120">
						<?= lang('Flavors.RatingType_2'); ?>
					</div>
					<div class="list-col center w120">
						<?= lang('Flavors.RatingType_3'); ?>
					</div>
					<div class="list-col center w120">
						<?= lang('Flavors.RatingType_4'); ?>
					</div>
					<div class="list-col center w120   <?php if(!empty($filters['order']) && $filters['order']=="date,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="date,desc"):?>desc<?php endif; ?>" data-order="date">
						<?= lang('Flavors.RestaurantAdded'); ?>
					</div>
					<div class="list-col center w90">
						<?= lang('Flavors.RatingLink'); ?>
					</div>
			</div>
			<?php if(!empty($rating_list)):?>
			  <?php foreach($rating_list as $list):?>
			    <div class="list-row">
				      <div class="list-col w200">
					     <?=$list['user'];?>
					  </div>
					  <div class="list-col">
					     <?=$list['name'];?>
					  </div>
				      <div class="list-col w120 center">
					     <?=round($list['avg_rating'],1);?>
					  </div>
					  <div class="list-col center w120">
						<?php if(!empty($list['type_1']['rating'])) { echo round($list['type_1']['rating'],1); } ?>
					  </div>
					   <div class="list-col center w120">
					    <?php if(!empty($list['type_2']['rating'])) { echo round($list['type_2']['rating'],1); } ?>
					   </div>
					    <div class="list-col center w120">
						 <?php if(!empty($list['type_3']['rating'])) { echo round($list['type_3']['rating'],1); } ?>
						</div>
						 <div class="list-col center w120">
						   <?php if(!empty($list['type_4']['rating'])) { echo round($list['type_4']['rating'],1); } ?>
						 </div>
						  <div class="list-col center w120">
						   <?=date("d.m.Y H:i",strtotime($list['created_at']));?>
						  </div>
						   <div class="list-col center w90">
						     <a href="/<?=$list['link'];?>" target="_blank"><i class="fa-regular fa-eye fa-xl"></i></a>
						   </div>
			    </div>
			  <?php endforeach;?>
			<?php else:?>
			<div class="list-row no-list-result"><?= lang('Flavors.RatingNoListResult'); ?></div>
			<?php endif;?>
		</div>
		<?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	</div>
</div>	
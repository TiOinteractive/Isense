<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Flavors.CuisineList'); ?>
	   </div>
	   <p> <a class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/add-cuisine" title="<?=lang('Flavors.AddCuisine'); ?>"><i class="fa-solid fa-plus"></i> <?=lang('Flavors.AddCuisine'); ?></a></p>
	   
	    <form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/cuisine" method="get">
			<input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
			<input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
			<div class="filter search-name">
				<input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" placeholder="<?=lang('Flavors.FindCuisine');?>" />
				<button type="submit"><?=lang('Flavors.Search'); ?></button>
			</div>
		</form>
	   
	   
	    <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
		<form method="post" id="order-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/SaveOrderCuisine">	   
		<div class="list order-sortable">
            <div class="list-row list-head">
			   <div class="list-col center w100">
                   &nbsp;
                </div>
                <div class="list-col   <?php if(!empty($filters['order']) && $filters['order']=="name,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="name,desc"):?>desc<?php endif; ?>" data-order="name">
                    <?= lang('Flavors.CuisineName'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Flavors.Edit'); ?>
                </div>
				<div class="list-col center w100 hide-500   <?php if(!empty($filters['order']) && $filters['order']=="menu,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="menu,desc"):?>desc<?php endif; ?>" data-order="menu">
                    <?= lang('Flavors.ShowOnMenu'); ?>
                </div>
                <div class="list-col center w100 hide-500   <?php if(!empty($filters['order']) && $filters['order']=="publish,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="publish,desc"):?>desc<?php endif; ?>" data-order="publish">
                    <?= lang('Flavors.Publish'); ?>
                </div>
                <div class="list-col center w100">
            <?= lang('Flavors.Delete'); ?>
                </div>
            </div>
			 <?php if (!empty($cuisine_list)): ?>
			  <div <?php if(empty($filters['order']) or $filters['order']=="order;asc"): ?>class="order-list"<?php endif;?>>
                <?php foreach ($cuisine_list as $parameter): ?>
                    <div class="list-row list-row-<?= $parameter['id']; ?>">
                        
						<div class="list-col w100">
                           <?php if(!empty($parameter['ico_svg'])):?><a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-cuisine/<?= $parameter['id']; ?>" title="<?= $parameter['name']; ?>" style="display:block;width:40px;"><?=$parameter['ico_svg'];?></a><?php endif; ?>
						   <input type="hidden" class="order-field" name="cuisine_order[]" value="<?=$parameter['id'];?>" />
                        </div>
						<div class="list-col">
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-cuisine/<?= $parameter['id']; ?>" title="<?= $parameter['name']; ?>"><?= $parameter['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-cuisine/<?= $parameter['id']; ?>" title="<?= lang('Parameters.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
						<div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/menucuisine/<?= $parameter['id']; ?>" title="<?= lang('Flavors.ShowOnMenu'); ?>"><?php if (!empty($parameter['menu']) && $parameter['menu']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/publishcuisine/<?= $parameter['id']; ?>" title="<?= lang('Parameters.Publish'); ?>"><?php if (!empty($parameter['publish']) && $parameter['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                            <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))): ?><a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/deletecusine/<?= $parameter['id']; ?>" data-title="<?= lang('Flavors.DeleteCuisine'); ?>" data-message="<?= lang('Flavors.CuisineDeleteConfirm') . ': <b>' . $parameter['name'] . '</b>'; ?>" data-btn-ok="<?= lang('Flavors.Remove'); ?>" data-btn-cancel="<?= lang('Flavors.Cancel'); ?>" title="<?= lang('Flavors.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
				</div>
            <?php else: ?>	   
                 <div class="list-row no-list-result"><?= lang('Flavors.ParametersNoListResult'); ?></div>
            <?php endif; ?>	
		</div>	
	   	</form>		
	   
	    <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	   
	</div>
</div>	
<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Flavors.ParametersList'); ?>
	   </div>
	   <p> <a class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/add-parameter" title="<?=lang('Flavors.AddParameter'); ?>"><i class="fa-solid fa-plus"></i> <?=lang('Flavors.AddParameter'); ?></a></p>
	   
	    <form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/parameters" method="get">
			<input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
			<input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
			<div class="filter search-name">
				<input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" placeholder="<?=lang('Flavors.FindParameter');?>" />
				<button type="submit"><?=lang('Flavors.Search'); ?></button>
			</div>
		</form>
	   
	   
	    <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	   
	   <div class="list">
            <div class="list-row list-head">
                <div class="list-col   <?php if(!empty($filters['order']) && $filters['order']=="name,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="name,desc"):?>desc<?php endif; ?>" data-order="name">
                    <?= lang('Flavors.ParameterName'); ?>
                </div>
                <div class="list-col w300 hide-500   <?php if(!empty($filters['order']) && $filters['order']=="filtername,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="filtername,desc"):?>desc<?php endif; ?>" data-order="filtername">
                    <?= lang('Flavors.ParameterFilterName'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Flavors.ParameterValues'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Flavors.Edit'); ?>
                </div>
                <div class="list-col center w100 hide-500   <?php if(!empty($filters['order']) && $filters['order']=="publish,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="publish,desc"):?>desc<?php endif; ?>" data-order="publish">
                    <?= lang('Flavors.Publish'); ?>
                </div>
                <div class="list-col center w100">
            <?= lang('Flavors.Delete'); ?>
                </div>
            </div>
			 <?php if (!empty($parameters)): ?>
                <?php foreach ($parameters as $parameter): ?>
                    <div class="list-row list-row-<?= $parameter['id']; ?>">
                        <div class="list-col">
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-parameter/<?= $parameter['id']; ?>" title="<?= $parameter['name']; ?>"><?= $parameter['name']; ?></a>
                        </div>
                        <div class="list-col w300 hide-500">
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-parameter/<?= $parameter['id']; ?>" title="<?= $parameter['filter_name']; ?>"><?= $parameter['filter_name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <?= $parameter['values_count']; ?>  
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/edit-parameter/<?= $parameter['id']; ?>" title="<?= lang('Flavors.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/publishparameter/<?= $parameter['id']; ?>" title="<?= lang('Flavors.Publish'); ?>"><?php if (!empty($parameter['publish']) && $parameter['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                            <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))): ?><a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/deleteparameter/<?= $parameter['id']; ?>" data-title="<?= lang('Flavors.DeleteParameter'); ?>" data-message="<?= lang('Flavors.ParameterDeleteConfirm') . ': <b>' . $parameter['name'] . '</b>'; ?>" data-btn-ok="<?= lang('Flavors.Remove'); ?>" data-btn-cancel="<?= lang('Flavors.Cancel'); ?>" title="<?= lang('Flavors.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>	   
                 <div class="list-row no-list-result"><?= lang('Flavors.ParametersNoListResult'); ?></div>
            <?php endif; ?>	
		</div>	
	   
	   
	    <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	   
	</div>
</div>	
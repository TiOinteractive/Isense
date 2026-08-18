<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Tags.Tags'); ?>
	   </div>
	   <form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/tags" method="get">
			<div class="filter">
				<label><?=lang('Tags.TagName'); ?></label>
				<input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
			</div>
			<input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
			<input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
			<div class="filter">
				<button type="submit"><?=lang('News.Search'); ?></button>
			</div>
		</form>
	   	   <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	   <div class="list">
	     <div class="list-row list-head">
		  <div class="list-col">
					<?=lang('Tags.TagName'); ?>
                </div>
				 <div class="list-col w300">
					<?=lang('Tags.PageInfo'); ?>
                </div>
				<div class="list-col center w80 hide-1200">
                    <?= lang('Flavors.Edit'); ?>
                </div>
                <div class="list-col center w100">
					<?= lang('Flavors.Delete'); ?>
                </div>
	     </div>
	   <?php if(!empty($TagsList)):?>
	    <?php foreach($TagsList as $tag):?>
		       <div class="list-row row-tag<?=$tag['id'];?>">
			        <div class="list-col tag">
			         <?=$tag['tag'];?>
			        </div>
                    <div class="list-col w300">
                       <?=$tag['title'];?>
                    </div>  
					<div class="list-col center w80 hide-1200">
						 <a class="edit-tag-btn" data-title="<?= lang('Flavors.EditTag'); ?>" data-btn-ok="<?= lang('Flavors.Save'); ?>" data-btn-cancel="<?= lang('Flavors.Cancel'); ?>" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/tags/edittag/<?= $tag['id']; ?>" title="<?= lang('Flavors.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
					</div>
					<div class="list-col center w100">
						  <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))): ?><a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/tags/deletetag/<?= $tag['id']; ?>" data-title="<?= lang('Tags.DeleteTag'); ?>" data-message="<?= lang('Tags.DeleteConfirm') . ': <b>' . $tag['tag'] . '</b>'; ?>" data-btn-ok="<?= lang('Tags.Remove'); ?>" data-btn-cancel="<?= lang('Tags.Cancel'); ?>" title="<?= lang('Tags.Remove'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
					</div>					
			   </div>
		<?php endforeach;?>
	   <?php endif;?>
	   	   </div>
	      <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	</div>
</div>	
<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Flavors.CommentsList'); ?>
	   </div>
	    <form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/comments" method="get">
			<input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
			<input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
			<div class="filter search-name">
				<input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" placeholder="<?=lang('Flavors.FindComment');?>" />
				<button type="submit"><?=lang('Flavors.Search'); ?></button>
			</div>
		</form>
	    <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
		<div class="list">
            <div class="list-row list-head">
				   <div class="list-col w200 <?php if(!empty($filters['order']) && $filters['order']=="nick,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="nick,desc"):?>desc<?php endif; ?>" data-order="nick">
					<?= lang('Flavors.Nick'); ?>
					</div>
					<div class="list-col w200 <?php if(!empty($filters['order']) && $filters['order']=="name,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="name,desc"):?>desc<?php endif; ?>" data-order="name">
						<?= lang('Flavors.RestaurantName'); ?>
					</div>
					<div class="list-col">
						<?= lang('Flavors.Comment'); ?>
					</div>
					<div class="list-col center w120 <?php if(!empty($filters['order']) && $filters['order']=="date,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="date,desc"):?>desc<?php endif; ?>" data-order="date">
						<?= lang('Flavors.RestaurantAdded'); ?>
					</div>
					<div class="list-col center w140">
						<?= lang('Flavors.IPAddress'); ?>
					</div>
					<div class="list-col center w90">
						<?= lang('Flavors.RatingLink'); ?>
					</div>
					<div class="list-col center w100 <?php if(!empty($filters['order']) && $filters['order']=="publish,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="publish,desc"):?>desc<?php endif; ?>" data-order="publish">
						<?= lang('Flavors.Publish'); ?>
					</div>
					<div class="list-col center w90">
						<?= lang('Flavors.Edit'); ?>
					</div>
					<div class="list-col center w90">
						<?= lang('Flavors.Delete'); ?>
					</div>
			</div>
			<?php if(!empty($comments_list)):?>
			  <?php foreach($comments_list as $comment):?>
			    <div class="list-row comment_<?=$comment['id'];?>">
				           <div class="list-col w200"><?=$comment['nick'];?></div>
						   <div class="list-col w200"><?=$comment['name'];?></div>
						   <div class="list-col comment_show" style="cursor:pointer">
									<div class="truncate"><?=character_limiter($comment['comment'],60);?></div>
									<div class="all hide"><?=$comment['comment'];?></div>
						   </div>
						   <div class="list-col w120 center"><?=date("d.m.Y H:i:s",strtotime($comment['created_at']));?></div>
						   <div class="list-col w140 center"><?=$comment['ip'];?></div>
						   <div class="list-col center w90">
						     <a href="/<?=$comment['link'];?>" target="_blank"><i class="fa-regular fa-eye fa-xl"></i></a>
						   </div>
						   <div class="list-col w100 center">
						     <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/publishcomment/<?= $comment['id']; ?>" title="<?= lang('Flavors.Publish'); ?>"><?php if (!empty($comment['publish']) && $comment['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
						   </div>
						   <div class="list-col w90 center">
						       <a class="edit-comment-btn" data-title="<?= lang('Flavors.EditComment'); ?>" data-btn-ok="<?= lang('Flavors.Save'); ?>" data-btn-cancel="<?= lang('Flavors.Cancel'); ?>" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/editcomment/<?= $comment['id']; ?>" title="<?= lang('Flavors.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
						   </div>
						   <div class="list-col w90 center">
						           <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))): ?><a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/deletecomment/<?= $comment['id']; ?>" data-title="<?= lang('Flavors.DeleteComment'); ?>" data-message="<?= lang('Flavors.CommentDeleteConfirm') . ': <b>' .$comment['nick'].' ('. $comment['created_at'] . ')</b>'; ?>" data-btn-ok="<?= lang('Flavors.Remove'); ?>" data-btn-cancel="<?= lang('Flavors.Cancel'); ?>" title="<?= lang('Flavors.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
						   </div>
			    </div>
			  <?php endforeach;?>
			<?php else:?>
			<div class="list-row no-list-result"><?= lang('Flavors.CommentsNoListResult'); ?></div>
			<?php endif;?>
		</div>
		<?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
	</div>
</div>	
<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	   <div class="head">
			<?=lang('Users.UsersList'); ?>
	   </div>
	   
	  <form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/users" method="get">
			<input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
			<input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
			<div class="filter search-name">
				<input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" placeholder="<?=lang('Users.UserFindFilter');?>" />
				<button type="submit"><?=lang('Users.Search'); ?></button>
			</div> 
		</form>   
		<?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>	
		<div class="list">
            <div class="list-row list-head">
				   <div class="list-col w200 <?php if(!empty($filters['order']) && $filters['order']=="mail,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="mail,desc"):?>desc<?php endif; ?>" data-order="mail">
					<?= lang('Users.Mail'); ?>
					</div>
					<div class="list-col  <?php if(!empty($filters['order']) && $filters['order']=="name,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="name,desc"):?>desc<?php endif; ?>" data-order="name">
						<?= lang('Users.NameSurname'); ?>
					</div>
					<div class="list-col w200  <?php if(!empty($filters['order']) && $filters['order']=="nick,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="nick,desc"):?>desc<?php endif; ?>" data-order="nick">
						<?= lang('Users.Nick'); ?>
					</div>
					<div class="list-col w120  <?php if(!empty($filters['order']) && $filters['order']=="date,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="date,desc"):?>desc<?php endif; ?>" data-order="date">
						<?= lang('Users.CreatedAt'); ?>
					</div>
					<div class="list-col center w120   <?php if(!empty($filters['order']) && $filters['order']=="city,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="city,desc"):?>desc<?php endif; ?>" data-order="city">
						<?= lang('Users.City'); ?>
					</div>
					<div class="list-col center w120   <?php if(!empty($filters['order']) && $filters['order']=="phone,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="phone,desc"):?>desc<?php endif; ?>" data-order="phone">
						<?= lang('Users.Phone'); ?>
					</div>
					<div class="list-col center w120   <?php if(!empty($filters['order']) && $filters['order']=="newsletter,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="newsletter,desc"):?>desc<?php endif; ?>" data-order="newsletter">
						<?= lang('Users.Newsletter'); ?>
					</div>
					<div class="list-col center w120   <?php if(!empty($filters['order']) && $filters['order']=="active,asc"): ?>asc<?php elseif(!empty($filters['order']) && $filters['order']=="active,desc"):?>desc<?php endif; ?>" data-order="active">
						<?= lang('Users.Active'); ?>
					</div>
					<div class="list-col center w100">
						<?= lang('Users.Delete'); ?>
					</div>
			</div>
			<?php if(!empty($users_list)):?>
			  <?php foreach($users_list as $list):?>
			    <div class="list-row">
				   <div class="list-col w200"><?=$list['mail'];?></div>
				   <div class="list-col"><?=$list['name'];?> <?=$list['surname'];?></div>
				   <div class="list-col w200"><?=$list['nick'];?></div>
				   <div class="list-col w120"><?=$list['created_at'];?></div>
				   <div class="list-col w120 center"><?=$list['city'];?></div>
				   <div class="list-col w120 center"><?=$list['phone'];?></div>
				   <div class="list-col w120 center"> <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/users/newsletteruser/<?= $list['id']; ?>" title="<?= lang('Users.NewsletterUser'); ?>"><?php if (!empty($list['newsletter']) && $list['newsletter']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a></div>
				   <div class="list-col w120 center"> <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/users/activeuser/<?= $list['id']; ?>" title="<?= lang('Users.ActiveUser'); ?>"><?php if (!empty($list['active']) && $list['active']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a></div>
				     <div class="list-col center w100">
                            <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))): ?><a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/users/deleteuser/<?= $list['id']; ?>" data-title="<?= lang('Users.DeleteUser'); ?>" data-message="<?= lang('Users.DeleteConfirm') . ': <b>' . $list['mail'] . '</b>'; ?>" data-btn-ok="<?= lang('Users.Delete'); ?>" data-btn-cancel="<?= lang('Users.Cancel'); ?>" title="<?= lang('Users.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                     </div>
			    </div>
			  <?php endforeach;?>
			<?php else:?>
			<div class="list-row no-list-result"><?= lang('Users.NoListResult'); ?></div>
			<?php endif;?>
		</div>
		<?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => '')); ?>
</div>
</div>		
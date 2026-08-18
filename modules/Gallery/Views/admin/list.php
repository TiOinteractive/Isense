<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?=lang('Gallery.GalleryList'); ?></h3>
</div>
<p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/gallery/add/<?=$id_content; ?>" title="<?=lang('Gallery.AddGallery');?>"><i class="fa-solid fa-plus"></i> <?=lang('Gallery.AddGallery');?></a></p>
<?= view('Modules\Gallery\Views\admin\list_filters', array()); ?>

<?= view('admin/order_and_pagination', array('pager'=>$pager,'on_page_list' => $on_page_list)); ?> 
<div class="list">
    <div class="list-row list-head">
       <?php if(empty($filters['order_array']) or !empty($filters['order_array']['order'])):?>   
		<div class="list-col center w50<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['order'])): ?> <?=$filters['order_array']['order']; ?><?php endif; ?>" data-order="order">
             <?=lang('Gallery.Lp');?>
        </div>
		<?php endif;?>		
        <div class="list-col<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['name'])): ?> <?=$filters['order_array']['name']; ?><?php endif; ?>" data-order="name">
            <?=lang('Gallery.Name');?>
        </div>
        <div class="list-col center w100 hide-1200<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['home'])): ?> <?=$filters['order_array']['home']; ?><?php endif; ?>" data-order="home">
            <?=lang('Gallery.Home');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Gallery.Edit');?>
        </div>
        <div class="list-col center w100 hide-500<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['publish'])): ?> <?=$filters['order_array']['publish']; ?><?php endif; ?>" data-order="publish">
            <?=lang('Gallery.Publish');?>
        </div>
        <div class="list-col center w100">
            <?=lang('Gallery.Delete');?>
        </div>
    </div>
    <?php if(!empty($gallery_list)): ?>
        <div<?php if(!empty($filters['order']) && in_array($filters['order'], array('order,asc', 'order,desc'))): ?> class="list-order-box"<?php endif; ?>  data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/gallery/order">
            <?php foreach($gallery_list as $k=>$gallery): ?>
                <div class="list-row list-row-<?=$gallery['id']; ?>">
                    <?php if(empty($filters['order_array']) or !empty($filters['order_array']['order'])):?>     
					   <div class="list-col center w50 order">
                            <?=$gallery['order']; ?>
                        </div>
					<?php endif;?>	
                    <div class="list-col">
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/gallery/edit/<?=$id_content; ?>/<?=$gallery['id']; ?>" title="<?=$gallery['name']; ?>"><strong><?=$gallery['name']; ?></strong></a>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <a class="list-home-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/gallery/home/<?=$gallery['id']; ?>" title="<?=lang('Gallery.Home');?>"><?php if(!empty($gallery['home']) && $gallery['home']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/gallery/edit/<?=$id_content; ?>/<?=$gallery['id']; ?>" title="<?=lang('Gallery.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                    </div>
                    <div class="list-col center w100 hide-500">
                        <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/gallery/publish/<?=$gallery['id']; ?>" title="<?=lang('Gallery.Publish');?>"><?php if(!empty($gallery['publish']) && $gallery['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                    </div>
                    <div class="list-col center w100">
                      <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?>  <a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/gallery/delete/<?=$gallery['id']; ?>" data-title="<?=lang('Gallery.DeleteGallery');?>" data-message="<?=lang('Gallery.DeleteConfirm') . ': <b>' . $gallery['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Gallery.Remove');?>" data-btn-cancel="<?=lang('Gallery.Cancel');?>" title="<?=lang('Gallery.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a> <?php } ?>
                    </div>
                </div>
            <?php endforeach;  ?>
        </div>
    <?php else: ?>
    <div class="list-row no-list-result"><?=lang('Gallery.NoListResult'); ?></div>
    <?php endif; ?> 
    <?= view('admin/order_and_pagination', array('pager'=>$pager,'on_page_list' => $on_page_list)); ?>
</div>
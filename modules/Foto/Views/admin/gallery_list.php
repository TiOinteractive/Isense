<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?=lang('Foto.GalleryList'); ?></h3>
</div>
<p><a class="btn" href="/tiocms/foto/gallery-add/<?=$id_content; ?>/" title="<?=lang('Foto.GalleryAddBtn');?>"><i class="fa-solid fa-plus"></i> <?=lang('Foto.GalleryAddBtn');?></a></p>
<?= view('Modules\Foto\Views\admin\gallery_filters', array()); ?>
<?= view('admin/order_and_pagination', array('pager'=>$pager,'on_page_list' => $on_page_list)); ?> 
<?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
<div class="list">
    <div class="list-row list-head">
      <div class="list-col center w120">
        &nbsp;
      </div>
      <div class="list-col<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['name'])): ?> <?=$filters['order_array']['name']; ?><?php endif; ?>" data-order="name">
        <?=lang('Foto.GalleryName');?>
      </div>
	  <div class="list-col w120 center<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['user'])): ?> <?=$filters['order_array']['user']; ?><?php endif; ?>" data-order="user">
        <?=lang('Foto.GalleryUser');?>
    </div>
	<div class="list-col w100 center<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['date'])): ?> <?=$filters['order_array']['date']; ?><?php endif; ?>" data-order="date">
        <?=lang('Foto.GalleryCreatedDate');?>
    </div>
	<div class="list-col center w100 hide-1200<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['views'])): ?> <?=$filters['order_array']['views']; ?><?php endif; ?>" data-order="views">
        <?=lang('Foto.Views');?>
    </div>
	<div class="list-col center w70 hide-1200<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['photos'])): ?> <?=$filters['order_array']['photos']; ?><?php endif; ?>" data-order="photos">
        <?=lang('Foto.NumberPhotos');?>
    </div>
	<div class="list-col center w70 center hide-1200<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['home'])): ?> <?=$filters['order_array']['home']; ?><?php endif; ?>" data-order="home">
            <?=lang('News.Home');?>
        </div>
		<div class="list-col center w100 center hide-1200<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['investments'])): ?> <?=$filters['order_array']['investments']; ?><?php endif; ?>" data-order="investments">
            <?=lang('Foto.Investments');?>
        </div>
        <div class="list-col center w90 hide-500<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['publish'])): ?> <?=$filters['order_array']['publish']; ?><?php endif; ?>" data-order="publish">
            <?=lang('News.Publish');?>
        </div>
		<div class="list-col center w70 hide-1200">
            <?=lang('News.Edit');?>
        </div>
        <div class="list-col center w100">
            <?=lang('News.Delete');?>
        </div>
	</div>	
		    <?php if(!empty($gallery_list)): ?>
			    <?php foreach($gallery_list as $k=>$gal): ?>
				    <div class="list-row list-row-<?=$gal['id']; ?>">
					   <div class="list-col center w120"><?php if(!empty($gal['photo']['path'])):?><a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/gallery-edit/<?=$id_content; ?>/<?=$gal['id']; ?>" title="<?=lang('Foto.GalleryEdit');?>"><img src="/image/c/90/90/<?=$gal['photo']['path'];?>" alt="<?=$gal['name'];?>"></a> <?php endif; ?></div>
					   <div class="list-col"><a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/gallery-edit/<?=$id_content; ?>/<?=$gal['id']; ?>" title="<?=lang('Foto.GalleryEdit');?>"><?=str_replace(['"',"'"], "&quot;", $gal['name']);?></a></div>
					   <div class="list-col w120 center"><?=$gal['user_name'];?></div>
					   <div class="list-col w100 center"><?=date("d.m.Y H:i:s",strtotime($gal['created_at']));?></div>
					   <div class="list-col w100 center"><?=$gal['views'];?></div>
					   <div class="list-col w70 center"><?=$gal['number_of_photo'];?></div>
					    <div class="list-col center w70 hide-1200">
						 <a class="list-home-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/galleryhome/<?=$gal['id']; ?>" title="<?=lang('News.Home');?>"><?php if(!empty($gal['home']) && $gal['home']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
						</div>
						<div class="list-col center w100 hide-1200">
						 <a class="list-home-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/galleryinvest/<?=$gal['id']; ?>" title="<?=lang('Foto.Investments');?>"><?php if(!empty($gal['investments']) && $gal['investments']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
						</div>
					    <div class="list-col center w70 hide-1200">
						 <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/gallerypublish/<?=$gal['id']; ?>" title="<?=lang('News.Publish');?>"><?php if(!empty($gal['publish']) && $gal['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
						</div>
						<div class="list-col center w90 hide-500">
						  <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/gallery-edit/<?=$id_content; ?>/<?=$gal['id']; ?>" title="<?=lang('Foto.GalleryEdit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
						</div>
						 <div class="list-col center w100">
						  <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?>  <a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/gallerydelete/<?=$gal['id']; ?>" data-title="<?=lang('Foto.DeleteGallery');?>" data-message="<?=lang('Foto.GalleryDeleteConfirm') . ': <b>' . str_replace(['"',"'"], "&quot;", $gal['name']) . '</b>'; ?>" data-btn-ok="<?=lang('Foto.Remove');?>" data-btn-cancel="<?=lang('Foto.Cancel');?>" title="<?=lang('Foto.DeleteGallery');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a> <?php }?>
						 </div>
					</div>
				<?php endforeach; ?>
			  <?php else: ?>
				<div class="list-row no-list-result"><?=lang('Foto.NoGalleryResult'); ?></div>
			  <?php endif; ?> 
</div>	
<?= view('admin/order_and_pagination', array('pager'=>$pager,'on_page_list' => $on_page_list)); ?> 
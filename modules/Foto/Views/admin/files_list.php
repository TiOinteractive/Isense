<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?=lang('Foto.FilesList'); ?></h3>
</div>
<p><a class="btn" href="/tiocms/foto/photo-add/<?=$id_content; ?>/" title="<?=lang('Foto.GalleryAddBtn');?>"><i class="fa-solid fa-plus"></i> <?=lang('Foto.PhotoAddBtn');?></a></p>
<?= view('Modules\Foto\Views\admin\files_filters', array()); ?>
<?= view('admin/order_and_pagination', array('pager'=>$pager,'on_page_list' => $on_page_list)); ?> 
<div class="list">
    <div class="list-row list-head">
      <div class="list-col center w120">
        &nbsp;
      </div>
      <div class="list-col<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['name'])): ?> <?=$filters['order_array']['name']; ?><?php endif; ?>" data-order="name">
        <?=lang('Foto.Name');?>
      </div>
	    <div class="list-col w200">
        <?=lang('Foto.GalleryCategory');?>
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
	<div class="list-col center w70 center hide-1200<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['home'])): ?> <?=$filters['order_array']['home']; ?><?php endif; ?>" data-order="home">
            <?=lang('News.Home');?>
        </div>
        <div class="list-col center w90 hide-500<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['publish'])): ?> <?=$filters['order_array']['publish']; ?><?php endif; ?>" data-order="publish">
            <?=lang('News.Publish');?>
        </div>
        <div class="list-col center w100">
            <?=lang('News.Delete');?>
        </div>
	</div>	
	<?php if(!empty($files_list)): ?>
	    <?php foreach($files_list as $k=>$file): ?>
				    <div class="list-row list-row-<?=$file['id']; ?>">
					   <div class="list-col center w120"><?php if(!empty($file['path'])):?><a href="/image/original/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank"><img src="/image/c/90/90/<?=$file['path'];?>" alt="<?=$file['name'];?>"></a> <?php endif; ?></div>
					   <div class="list-col"><?=$file['name'];?></div>
					   <div class="list-col w200">
							   <select name="id_category" class="file-category-id" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/filecat/<?=$file['id']; ?>">
								<option value="0">(<?=lang('Admin.page.ThereIsNoParent'); ?>)</option>
								<?php if(!empty($categorylists)): ?>
									<?php foreach($categorylists as $k=>$p): ?>
										<?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($file['id_category']) ? $file['id_category'] : 0, 'count'=>count($categorylists), 'item_no'=>$k+1)); ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
					   </div>
					   <div class="list-col w120 center"><?=$file['user_name'];?></div>
					   <div class="list-col w100 center"><?=date("d.m.Y H:i:s",strtotime($file['created_at']));?></div>
					   <div class="list-col w100 center"><?=$file['views'];?></div>
					   <div class="list-col center w70 hide-1200">
						 <a class="list-home-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/filehome/<?=$file['id']; ?>" title="<?=lang('News.Home');?>"><?php if(!empty($file['home']) && $file['home']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
						</div>
					     <div class="list-col center w90 hide-1200">
							<a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/filepublish/<?=$file['id']; ?>" title="<?=lang('News.Publish');?>"><?php if(!empty($file['publish']) && $file['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
						</div>
						 <div class="list-col center w100">
						  <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?>  <a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/filedelete/<?=$file['id']; ?>" data-title="<?=lang('Foto.RemovePhoto');?>" data-message="<?=lang('Foto.RemovePhotoConfirm') . ': <b>' . str_replace(['"',"'"], "&quot;", $file['name']) . '</b>'; ?>" data-btn-ok="<?=lang('Foto.Remove');?>" data-btn-cancel="<?=lang('Foto.Cancel');?>" title="<?=lang('Foto.RemovePhoto');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a> <?php }?>
						 </div>
						
					</div>
        <?php endforeach;?>					
	 <?php else: ?>
				<div class="list-row no-list-result"><?=lang('Foto.NoFiles'); ?></div>
	<?php endif; ?> 	
</div>
<?= view('admin/order_and_pagination', array('pager'=>$pager,'on_page_list' => $on_page_list)); ?> 	
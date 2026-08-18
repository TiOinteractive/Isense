<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?=lang('Catalog.CatalogList'); ?></h3>
</div>
<p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/catalog/add/<?=$id_content; ?>" title="<?=lang('Catalog.AddCatalog');?>"><i class="fa-solid fa-plus"></i> <?=lang('Catalog.AddCatalog');?></a></p>
<?= view('Modules\Catalog\Views\admin\list_filters', array()); ?>

<?= view('admin/order_and_pagination', array('pager'=>$pager,'on_page_list' => $on_page_list)); ?> 
<div class="list">
    <div class="list-row list-head">
        <?php if(empty($filters['order_array']) or !empty($filters['order_array']['order'])):?>     
			<div class="list-col center w50<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['order'])): ?> <?=$filters['order_array']['order']; ?><?php endif; ?>" data-order="order">
                <?=lang('Catalog.Lp');?>
            </div>
        <?php endif;?>	
        <div class="list-col<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['name'])): ?> <?=$filters['order_array']['name']; ?><?php endif; ?>" data-order="name">
            <?=lang('Catalog.Name');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Catalog.CreatedDate');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Catalog.Views');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Catalog.Edit');?>
        </div>
        <div class="list-col center w100 hide-500<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['publish'])): ?> <?=$filters['order_array']['publish']; ?><?php endif; ?>" data-order="publish">
            <?=lang('Catalog.Publish');?>
        </div>
        <div class="list-col center w100">
            <?=lang('Catalog.Delete');?>
        </div>
    </div>
    <?php if(!empty($catalog_list)): ?>
        <div<?php if(!empty($filters['order']) && in_array($filters['order'], array('order,asc', 'order,desc'))): ?> class="list-order-box"<?php endif; ?> data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/catalog/order">
            <?php foreach($catalog_list as $k=>$catalog): ?>
                <div class="list-row list-row-<?=$catalog['id']; ?>">
                  <?php if(empty($filters['order_array']) or !empty($filters['order_array']['order'])):?>  
                        <div class="list-col center w50 order">
                           <?=$catalog['order']; ?>
                        </div>
                    <?php endif;?>
                    <div class="list-col">
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/catalog/edit/<?=$id_content; ?>/<?=$catalog['id']; ?>" title="<?=esc($catalog['name']); ?>"><strong><?=$catalog['name']; ?></strong></a>
                    </div>	
                    <div class="list-col center w100 hide-1200">
                        <?=!empty($catalog['created_at']) ? date('d.m.Y H:i', strtotime($catalog['created_at'])) : ''; ?>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <?=!empty($catalog['views']) ? $catalog['views'] : '0'; ?>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/catalog/edit/<?=$id_content; ?>/<?=$catalog['id']; ?>" title="<?=lang('Catalog.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                    </div>
                    <div class="list-col center w100 hide-500">
                        <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/catalog/publish/<?=$catalog['id']; ?>" title="<?=lang('Catalog.Publish');?>"><?php if(!empty($catalog['publish']) && $catalog['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                    </div>
                    <div class="list-col center w100">
                      <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?>  <a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/catalog/delete/<?=$catalog['id']; ?>" data-title="<?=lang('Catalog.DeleteCatalog');?>" data-message="<?=lang('Catalog.DeleteConfirm') . ': <b>' . esc($catalog['name']) . '</b>'; ?>" data-btn-ok="<?=lang('Catalog.Remove');?>" data-btn-cancel="<?=lang('Catalog.Cancel');?>" title="<?=lang('Catalog.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a> <?php }?>
                    </div>
                </div>
            <?php endforeach;  ?>
        </div>
    <?php else: ?>
    <div class="list-row no-list-result"><?=lang('Catalog.NoListResult'); ?></div>
    <?php endif; ?> 
    <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
</div>
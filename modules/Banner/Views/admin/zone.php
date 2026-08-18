<div class="main-cont">
    <?php
    if (isset($breadcrumbs)) {
        echo $breadcrumbs;
    }
    ?>
    <div class="c">
        <div class="head">
            <?=$zone['name']; ?>
            <span><?= lang('Banner.Zone'); ?></span>
        </div>
        <p><a class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/add-zone/<?= $zone['id']; ?>" title="<?= lang('Banner.AddBaner'); ?>"><?= lang('Banner.AddBaner'); ?></a></p>
<?= view('Modules\Banner\Views\admin\list_filters', array()); ?>  
                <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => $order_list)); ?>    
        <div class="list">
            <div class="list-row list-head">
                    <?php if (!empty($filters) && in_array($filters['order'], array('order;asc', 'order;desc'))): ?>
                    <div class="list-col center w40">
                    <?= lang('Banner.Lp'); ?>
                    </div>
                    <?php endif; ?>
                <div class="list-col">
                    <?= lang('Banner.Name'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.DateFrom'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.DateTo'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.ClicksViews'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.See'); ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?= lang('Banner.Edit'); ?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?= lang('Banner.Publish'); ?>
                </div>
                <div class="list-col center w100">
            <?= lang('Banner.Delete'); ?>
                </div>
            </div>	
                <?php if (!empty($zone['list'])): ?>
                <div<?php if (!empty($filters) && in_array($filters['order'], array('order;asc', 'order;desc'))): ?> class="list-order-box"<?php endif; ?> data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/order">
                        <?php foreach ($zone['list'] as $k => $ban): ?>
                        <div class="list-row list-row-<?= $ban['id']; ?>">
                                <?php if (!empty($filters) && in_array($filters['order'], array('order;asc', 'order;desc'))): ?>
                                <div class="list-col center w40 order">
                                <?= $ban['order']; ?>
                                </div>
        <?php endif; ?>
                            <div class="list-col">
                                <a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/edit/<?= $ban['id']; ?>" title="<?= $ban['name']; ?>"><strong><?= $ban['name']; ?></strong></a>
                            </div>
                            <div class="list-col center w100 hide-1200">
                                <?php if(isset($ban['date_start'])) {echo $ban['date_start'];} else {echo '-';} ?>
                            </div>
                            <div class="list-col center w100 hide-1200">
                                <?php if(isset($ban['date_end'])) {echo $ban['date_end'];} else {echo '-';} ?>
                            </div>
                            <div class="list-col center w100 hide-1200">
                                <?=$ban['clicks'] . '/' . $ban['views']; ?>
                            </div>
                            <div class="list-col center w100 hide-1200">
                                <?php if($ban['path']) { ?> <a href="/image/<?= $ban['path']; ?>" target="_blank" title="<?= lang('Banner.See'); ?>"><img src="/image/c/50/50/<?= $ban['path']; ?>" /></a> <?php }?>
                            </div>
                            <div class="list-col center w100 hide-1200">
                                <a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/edit/<?= $ban['id']; ?>" title="<?= lang('Banner.Edit'); ?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                            </div>
                            <div class="list-col center w100 hide-500">
                                <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/publish/<?= $ban['id']; ?>" title="<?= lang('Banner.Publish'); ?>"><?php if (!empty($ban['publish']) && $ban['publish']): ?><i class="fa-solid fa-square-check fa-2x"></i><?php else: ?><i class="fa-regular fa-square fa-2x"></i><?php endif; ?></a>
                            </div>
                            <div class="list-col center w100">
                                <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?>   <a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/delete/<?= $ban['id']; ?>" data-title="<?= lang('Banner.Delete'); ?>" data-message="<?= lang('Banner.DeleteConfirm') . ': <b>' . $ban['name'] . '</b>'; ?>" data-btn-ok="<?= lang('Banner.Remove'); ?>" data-btn-cancel="<?= lang('Banner.Cancel'); ?>" title="<?= lang('Banner.Delete'); ?>"><i class="fa-solid fa-trash-can fa-2x"></i></a> <?php } ?>
                            </div>
                        </div>
                <?php endforeach; ?>		
                </div>
            <?php else: ?>
                <div class="list-row no-list-result"><?= lang('Banner.NoListResult'); ?></div>
        <?php endif; ?> 
        </div>	
<?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => $order_list)); ?> 	  
    </div>
</div>	
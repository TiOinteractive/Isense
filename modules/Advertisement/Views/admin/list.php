<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=lang('Advertisement.Advertisement');?>
        </div>
        <p>
            <a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/advertisement/add" title=""><?=lang('Advertisement.AddAdvertisement');?></a>
        </p>
        <?= view('Modules\Advertisement\Views\admin\list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col center w50">
                    <?=lang('Advertisement.ID');?>
                </div>
                <div class="list-col">
                    <?=lang('Advertisement.Name');?>
                </div>
                
                
                <div class="list-col center w100 hide-1200">
                    <?= lang('Advertisement.Edit'); ?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?= lang('Advertisement.Publish'); ?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Advertisement.Delete');?>
                </div>
            </div>
            <?php if(!empty($advertisements)): ?>
                <?php foreach($advertisements as $a): ?>
                    <div class="list-row list-row-<?=$a['id']; ?>">
                        <div class="list-col center w50">
                            #<?=$a['id']; ?>
                        </div>
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/advertisement/edit/<?=$a['id']; ?>" title="<?=esc($a['name']); ?>"><?=$a['name']; ?></a>
                        </div>
                        
                        
                        <div class="list-col center w100 hide-1200">
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/advertisement/edit/<?= $a['id']; ?>" title="<?= lang('Advertisement.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/advertisement/publish/<?= $a['id']; ?>" title="<?= lang('Advertisement.Publish'); ?>"><?php if (!empty($a['publish']) && $a['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                            <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/advertisement/delete/<?=$a['id']; ?>" data-title="<?=lang('Advertisement.Delete');?>" data-message="<?=lang('Advertisement.DeleteConfirm') . ': <b>' . esc($a['name']) . '</b>'; ?>" data-btn-ok="<?=lang('Advertisement.Remove');?>" data-btn-cancel="<?=lang('Advertisement.Cancel');?>" title="<?=lang('Advertisement.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>

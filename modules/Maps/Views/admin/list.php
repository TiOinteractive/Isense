<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=lang('Maps.MapsList');?>
            <a class="configuration" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/configuration" title="<?=lang('Maps.Configuration'); ?>"><i class="fa-solid fa-gear"></i></a>
        </div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/add" title=""><?=lang('Maps.AddMap');?></a></p>
        <?= view('Modules\Maps\Views\admin\list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Maps.Name');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Maps.Edit');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Maps.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Maps.Delete');?>
                </div>
            </div>
            <?php if(!empty($maps)): ?>
                <?php foreach($maps as $map): ?>
                    <div class="list-row list-row-<?=$map['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/edit/<?=$map['id']; ?>" title="<?=$map['name']; ?>"><?=$map['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/edit/<?=$map['id']; ?>" title="<?=lang('Maps.Edit');?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/publish/<?=$map['id']; ?>" title="<?=lang('Maps.Publish');?>"><?php if(!empty($map['publish']) && $map['publish']): ?><i class="fa-solid fa-square-check fa-2x"></i><?php else: ?><i class="fa-regular fa-square fa-2x"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/delete/<?=$map['id']; ?>" data-title="<?=lang('Maps.DeleteMap');?>" data-message="<?=lang('Maps.DeleteConfirm') . ': <b>' . $map['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Maps.Remove');?>" data-btn-cancel="<?=lang('Maps.Cancel');?>" title="<?=lang('Maps.Delete');?>"><i class="fa-solid fa-trash-can fa-2x"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>

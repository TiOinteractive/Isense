<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Admin.menu.MenuList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/menu/add" title=""><?=lang('Admin.menu.AddMenu'); ?></a></p>
        <?= view('admin/menu/list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Admin.menu.Name');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.menu.Publish');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Admin.menu.Edit');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.menu.Delete');?>
                </div>
            </div>
            <?php if(!empty($menus)): ?>
                <?php foreach($menus as $k=>$menu): ?>
                    <div class="list-row list-row-<?=$menu['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/menu/edit/<?=$menu['id']; ?>" title="<?=$menu['name']; ?>"><?=$menu['name']; ?></a>
                        </div>
                        <div class="list-col center w100">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/menu/publish/<?=$menu['id']; ?>" title="<?=lang('Admin.menu.Publish');?>"><?php if(!empty($menu['publish']) && $menu['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>&nbsp;
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/menu/edit/<?=$menu['id']; ?>" title="<?=lang('Admin.menu.Edit');?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </div>
                        <div class="list-col center w100">
                          <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?>   <a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/menu/delete/<?=$menu['id']; ?>" data-title="<?=lang('Admin.menu.DeleteMenu');?>" data-message="<?=lang('Admin.menu.DeleteConfirm') . ': <b>' . $menu['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Admin.menu.Remove');?>" data-btn-cancel="<?=lang('Admin.menu.Cancel');?>" title="<?=lang('Admin.menu.Delete');?>"><i class="fa-solid fa-trash-can fa-2x"></i></a> <?php } ?>
                        </div>
                    </div>
                <?php endforeach;  ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>

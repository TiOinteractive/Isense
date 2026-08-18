<div class="list-row list-row-<?=$page['id']; ?> level-<?=$page['level']; ?><?= $item_no<$count ? ' full' : ''; ?>">
    <div class="list-col">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$page['id']; ?>" title="<?=$page['name']; ?>" class="name"><?=$page['name']; ?></a>
    </div>
    <div class="list-col center w100">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$page['id']; ?>" title="<?=lang('Admin.page.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
    </div>
    <div class="list-col center w100">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/configuration/<?=$page['id']; ?>" title="<?=lang('Admin.page.Configuration');?>"><i class="fa-solid fa-gear fa-xl"></i></a>
    </div>
    <div class="list-col center w100">
        <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/publish/<?=$page['id']; ?>" title="<?=lang('Admin.page.Publish');?>"><?php if(!empty($page['publish']) && $page['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
    </div>
    <div class="list-col center w100">
        <a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/delete/<?=$page['id']; ?>" data-title="<?=lang('Admin.page.DeletePage');?>" data-message="<?=lang('Admin.page.DeleteConfirm') . ': <b>' . $page['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Admin.page.Remove');?>" data-btn-cancel="<?=lang('Admin.page.Cancel');?>" title="<?=lang('Admin.page.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a>
    </div>
</div>
<?php if(!empty($page['list'])): ?>
    <?php foreach($page['list'] as $j=>$p): ?>
        <?= view('admin/page/list_item', array('page' => $p, 'count'=>count($page['list']), 'item_no'=>$j+1)); ?>
    <?php endforeach; ?>
<?php endif; ?>

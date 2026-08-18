<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Redirects.RedirectsList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/redirects/add" title=""><?=lang('Redirects.AddRedirect');?></a></p>
        <?= view('Modules\Redirects\Views\admin\list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Redirects.From');?>
                </div>
                <div class="list-col">
                    <?=lang('Redirects.To');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Redirects.Type');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Redirects.Edit');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Redirects.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Redirects.Delete');?>
                </div>
            </div>
            <?php if(!empty($redirects)): ?>
                <?php foreach($redirects as $redirect): ?>
                    <div class="list-row list-row-<?=$redirect['id']; ?>">
                        <div class="list-col">
                            <?=$redirect['from']; ?>
                        </div>
                        <div class="list-col">
                            <?=$redirect['to']; ?>
                        </div>
                        <div class="list-col center w100">
                            <?=$redirect['type']; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/redirects/edit/<?=$redirect['id']; ?>" title="<?=lang('Redirects.Edit');?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/redirects/publish/<?=$redirect['id']; ?>" title="<?=lang('Redirects.Publish');?>"><?php if(!empty($redirect['publish']) && $redirect['publish']): ?><i class="fa-solid fa-square-check fa-2x"></i><?php else: ?><i class="fa-regular fa-square fa-2x"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                            <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/redirects/delete/<?=$redirect['id']; ?>" data-title="<?=lang('Redirects.DeleteRedirect');?>" data-message="<?=lang('Redirects.DeleteConfirm') . ': <b>' . $redirect['from'] . ' - ' . $redirect['to'] . '</b>'; ?>" data-btn-ok="<?=lang('Redirects.Remove');?>" data-btn-cancel="<?=lang('Redirects.Cancel');?>" title="<?=lang('Redirects.Delete');?>"><i class="fa-solid fa-trash-can fa-2x"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
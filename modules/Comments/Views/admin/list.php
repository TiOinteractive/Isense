<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
                <?=lang('Comments.Comments');?>
        </div>
        <?= view('Modules\Comments\Views\admin\list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col center w50">
                    <?=lang('Comments.ID');?>
                </div>
                <div class="list-col center w50">
                    <?=lang('Comments.ParentID');?>
                </div>
                <div class="list-col center w200">
                    <?=lang('Comments.User');?>
                </div>
                <div class="list-col">
                    <?=lang('Comments.Content');?>
                </div>
                <div class="list-col center w200">
                    <?=lang('Comments.CreatedAt');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Comments.Status');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Comments.Go');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Comments.Preview');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Comments.BlockUser');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Comments.Delete');?>
                </div>
            </div>
            <?php if(!empty($comments)): ?>
                <?php foreach($comments as $c): ?>
                    <div class="list-row list-row-<?=$c['status']; ?> list-row-<?=$c['id']; ?> user-<?=$c['id_user']; ?>">
                        <div class="list-col center w50">
                            #<?=$c['id']; ?>
                        </div>
                        <div class="list-col center w50">
                            <?php if($c['id_parent']): ?>#<?=$c['id_parent']; ?><?php endif; ?>
                        </div>
                        <div class="list-col center w200">
                            <?php if($c['name'] || $c['surname']): ?><?=$c['name']; ?> <?=$c['surname']; ?><br /><?php endif; ?><?=$c['nick']; ?><br /><?=$c['mail']; ?>
                        </div>
                        <div class="list-col">
                            <span class="content"><?php if($c['status'] == 'removed'): ?><s><?php endif; ?><?=$c['content']; ?><?php if($c['status'] == 'removed'): ?></s><?php endif; ?></span>
                        </div>
                        <div class="list-col center w200">
                            <?=date('d.m.Y H:i', strtotime($c['created_at'])); ?>
                        </div>
                        <div class="list-col center w100">
                            <?=lang('Comments.status.' . $c['status']);?>
                        </div>
                        <div class="list-col center w100">
                            <a href="/<?=$c['link']; ?>"  title="<?=lang('Comments.Preview');?>" target="_blank"><i class="fa-solid fa-link fa-x1"></i></a>
                        </div>
                        <div class="list-col center w100">
                            <a class="list-preview-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/comments/preview/<?=$c['id_link']; ?>/<?=$c['id']; ?>" data-btn-close="<?=lang('Comments.Close');?>" data-title="<?=lang('Comments.Preview');?>" title="<?=lang('Comments.Preview');?>"><i class="fa-solid fa-magnifying-glass fa-x1"></i></a>
                        </div>
                        <div class="list-col center w100">
                            <?php if($c['user_comments'] && isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-block-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/comments/block-user/<?=$c['id_user']; ?>" data-title="<?=lang('Comments.BlockUser');?>" data-message="<?=lang('Comments.BlockUserConfirm') . ': <b>' . $c['mail'] . '</b>'; ?>" data-btn-ok="<?=lang('Comments.Block');?>" data-btn-cancel="<?=lang('Comments.Cancel');?>" title="<?=lang('Comments.BlockUser');?>"><i class="fa-solid fa-ban fa-xl"></i></a><?php endif; ?>
                        </div>
                        <div class="list-col center w100">
                            <?php if($c['status'] != 'removed' && isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/comments/delete/<?=$c['id']; ?>" data-title="<?=lang('Comments.Delete');?>" data-message="<?=lang('Comments.DeleteConfirm') . ': <b>' . $c['mail'] . '</b>'; ?>" data-btn-ok="<?=lang('Comments.Remove');?>" data-btn-cancel="<?=lang('Comments.Cancel');?>" title="<?=lang('Comments.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>

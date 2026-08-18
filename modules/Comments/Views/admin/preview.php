<?php foreach($comments as $comment): ?>
    <div class="comment">
        <div class="comment-cont user-<?=$comment['id_user']; ?><?php if($id == $comment['id']): ?> active<?php endif; ?>">
            <div class="comment-top"><span class="nick"><?=$comment['nick']; ?> | <?=$comment['mail']; ?><?php if($comment['name'] || $comment['surname']): ?>(<?=$comment['name']; ?> <?=$comment['surname']; ?>)<?php endif; ?></span><span class="date"><?=date('d.m.Y H:i', strtotime($comment['created_at'])); ?></span></div>
            <div class="comment-content"><span class="content"><?php if($comment['status'] == 'removed'): ?><s><?php endif; ?><?=$comment['content']; ?><?php if($comment['status'] == 'removed'): ?></s><?php endif; ?></span></div>
            <div class="comment-bottom">
                <?php if($comment['user_comments'] && isset($_SESSION['role']) && !in_array($_SESSION['role'], array('editor', 'contributor'))): ?>
                    <a class="preview-block-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/comments/block-user/<?=$comment['id_user']; ?>" data-title="<?=lang('Comments.BlockUser');?>" data-message="<?=lang('Comments.BlockUserConfirm') . ': <b>' . $comment['mail'] . '</b>'; ?>" data-btn-ok="<?=lang('Comments.Block');?>" data-btn-cancel="<?=lang('Comments.Cancel');?>" title="<?=lang('Comments.BlockUser');?>"><i class="fa-solid fa-ban fa-xl"></i></a>
                <?php endif; ?>
                <?php if($comment['status'] != 'removed' && isset($_SESSION['role']) && !in_array($_SESSION['role'], array('editor', 'contributor'))): ?>
                    <a class="preview-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/comments/delete/<?=$comment['id']; ?>" data-title="<?=lang('Comments.Delete');?>" data-message="<?=lang('Comments.DeleteConfirm') . ': <b>' . $comment['mail'] . '</b>'; ?>" data-btn-ok="<?=lang('Comments.Remove');?>" data-btn-cancel="<?=lang('Comments.Cancel');?>" title="<?=lang('Comments.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <?php if(!empty($comment['comments'])): ?>
            <?=view('\Modules\Comments\Views\admin/preview', ['comments' => $comment['comments']]); ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
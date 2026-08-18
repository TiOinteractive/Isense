<?php if(!empty($comments)): ?>
    <?php 
        if(empty($level)) {
            $level = 0;
        }
    ?>
    <?php foreach($comments as $comment): ?>
        <div class="comment lvl-<?=$level; ?>">
            <div class="comment-cont">
                <div class="comment-top">
                    <div class="avatar">
                        <svg viewBox="0 0 32 32"><path d="M22.56,16.53a9.95,9.95,0,0,1-13.12,0A15,15,0,0,0,1,30a1,1,0,0,0,1,1H30a1,1,0,0,0,1-1A15,15,0,0,0,22.56,16.53Z"/><circle cx="16" cy="9" r="8"/></svg>
                    </div>
                    <div class="info">
                        <span class="nick"><?=$comment['nick']; ?></span>
                        <span class="date"><?=date('d.m.Y', strtotime($comment['created_at'])); ?>, <?=lang('Comments.form.HourShort'); ?> <?=date('H:i', strtotime($comment['created_at'])); ?></span>
                    </div>
                </div>
                <div class="comment-content"><?php if($comment['status'] == 'removed'): ?><span class="comment-info"><?=lang('Comments.form.CommentRemoved'); ?></span><?php else: ?><?=$comment['content']; ?><?php endif; ?></div>
                <div class="comment-bottom">
                    <a class="reply" href="<?=$locale ? '/' . $locale : ''; ?>/comments-action/form/<?=$comment['id']; ?>" title="<?=lang('Comments.form.Reply'); ?>" rel="nofollow"><svg viewBox="0 0 35 35"><path d="M19.564,29.894a3.21,3.21,0,0,1-1.355-.3A3.16,3.16,0,0,1,16.366,26.7l-.024-3.187c-4.27-.575-7.937.03-10.651,1.755A3.186,3.186,0,0,1,.883,21.883c1.626-7.269,6.162-11.609,12.133-11.609a14.144,14.144,0,0,1,3.35.4V8.3a3.189,3.189,0,0,1,5.242-2.441l10.934,9.2a3.187,3.187,0,0,1,0,4.879l-10.934,9.2A3.167,3.167,0,0,1,19.564,29.894ZM13.325,20.8a26.184,26.184,0,0,1,4.459.4,1.25,1.25,0,0,1,1.037,1.207l.045,4.288a.67.67,0,0,0,.4.624.663.663,0,0,0,.734-.1l10.935-9.2a.689.689,0,0,0,0-1.054h0L20,7.771a.69.69,0,0,0-1.132.528V12.37a1.249,1.249,0,0,1-1.694,1.168,11.64,11.64,0,0,0-4.156-.764c-6.8,0-9.044,6.752-9.694,9.656a.664.664,0,0,0,.28.707.65.65,0,0,0,.747.023A16.427,16.427,0,0,1,13.325,20.8Z"/></svg> <?=lang('Comments.form.Reply'); ?></a>
                </div>
            </div>
            <?php if(!empty($comment['comments'])): ?>
                <?php if($level == 0): ?><div class="comment-childs-box"><div class="comment-childs-cont"><?php endif; ?>
                <?=view('\Modules\Comments\Views\user/comments_list', ['comments' => $comment['comments'], 'level' => $level + 1]); ?>
                <?php if($level == 0): ?>
                    </div></div>
            <div class="childs-comments-btn-box"><span class="childs-comments-btn" data-collapse="<?=lang('Comments.form.Collapse'); ?>" data-expand="<?=lang('Comments.form.Expand'); ?>"><svg viewBox="0 0 48 48"><path d="M33.17 17.17l-9.17 9.17-9.17-9.17-2.83 2.83 12 12 12-12z"/><path d="M0 0h48v48h-48z" fill="none"/></svg> <span><?=lang('Comments.form.Expand'); ?></span></span></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if((empty($action) || $action == 'list') && $level == 0 && $count > count($comments)): ?>
    <div class="more-comments-box">
        <a class="more-comments" href="/comments-action/more" title="<?=lang('Comments.form.ShowMoreComments'); ?>"><?=lang('Comments.form.ShowMoreComments'); ?></a>
    </div>
    <?php endif; ?>
<?php endif; ?>
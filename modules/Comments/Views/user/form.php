<div class="comments-box">
    <?php if(!empty($data)): ?>
        <p class="header"><?=lang('Comments.form.Comments'); ?></p>
    <?php endif; ?>
    <?php if(!empty($session_user)): ?>
        <form class="comments-form" action="<?=$locale ? '/' . $locale : ''; ?>/comments-action" method="post">
            <input type="hidden" name="parent" value="<?=!empty($parent) ? $parent : ''; ?>">
            <input type="text" name="field_h" value="">
            <div class="field content">
                <div class="input">
                    <label><?= lang('Comments.form.Content'); ?>*</label>
                    <textarea name="content"></textarea>
                    <p class="form-info"><?=lang('Comments.form.YouAddCommentAs'); ?> <strong><?=$session_user['nick']; ?></strong></p>
                </div>
            </div>
            <div class="field btn-box">
                <input class="btn" type="submit" name="addcomment" value="<?= lang('Comments.form.AddComment'); ?>" />
            </div>
            <p class="form-info"><?=lang('Comments.form.FormInfo'); ?></p>
        </form>
    <?php else: ?>
        <div class="comments-login-box">
            <p class="not-logged"><a class="log-in-ajax" href="/<?=$global_links['login']; ?>" title="<?=lang('Comments.form.LogIn'); ?>"><?=lang('Comments.form.LogIn'); ?></a> <?=lang('Comments.form.LogInInfo'); ?></p>
        </div>
    <?php endif; ?>
    <?php if(empty($action)): ?>
        <div class="comments-list">
            <?php if(!empty($comments)): ?>
                <?=view('\Modules\Comments\Views\user/comments_list'); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
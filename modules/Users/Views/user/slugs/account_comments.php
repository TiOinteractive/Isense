
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="user-account">
    <div class="container">
        <div class="account-row">
            <div class="col col-sidebar">
                <?=view('Modules\Users\Views/user/slugs/account_menu'); ?>
            </div>
            <div class="col col-content">
                <div class="comments">
                    <h1><?=lang('Users.account.YourComments'); ?></h1>
                    <div class="comments-list">
                        <?php if(!empty($comments)): ?>
                            <?php foreach($comments as $comment): ?>
                                <div class="comment">
                                    
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-comments"><?=lang('Users.account.NoComments'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
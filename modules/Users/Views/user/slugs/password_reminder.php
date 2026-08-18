
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="user-login-form">
    <div class="container">
        <div class="login-register-box">
            <div class="login-quest-box">
                <div class="login-box">
                    <div class="login-form">
                        <h2><?= lang('Users.account.ForgotPassword'); ?></h2>
                        <?php if(!empty($data['flashdata']) && !empty($data['flashdata']['result'])): ?>
                            <div class="field"><div class="result alert alert-<?=$data['flashdata']['result']['status']; ?>"><?=$data['flashdata']['result']['message']; ?></div></div>
                        <?php endif; ?>
                        <form method="post">
                            <input type="text" name="field_h" value="">
                            <div class="field<?php if(isset($data['errors']) && isset($data['errors']['email'])):?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="text" name="email" value="<?php if(!empty($data['post']['email'])):?><?=$data['post']['email'];?><?php endif; ?>" placeholder="" />
                                    <span class="placeholder"><?=lang('Users.account.Email'); ?>*</span>
                                </div>
                                <?php if(isset($data['errors']) && isset($data['errors']['email'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['email'];?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field btn-box">
                                <input class="btn" type="submit" name="remind" value="<?= lang('Users.account.RemindPassword'); ?>" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
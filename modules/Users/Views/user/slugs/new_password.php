
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="user-login-form">
    <div class="container">
        <div class="login-register-box">
            <div class="login-quest-box">
                <div class="login-box">
                    <div class="login-form">
                        <h2><?= lang('Users.account.ForgotPassword'); ?></h2>
                        <?php if(isset($data['errors']) && isset($data['errors']['result'])):?>	
                            <div class="result alert alert-error"><?=$data['errors']['result'];?></div>
                        <?php endif; ?>
                        <?php if(!empty($data['flashdata']) && !empty($data['flashdata']['result'])): ?>
                            <div class="field"><div class="result alert alert-<?=$data['flashdata']['result']['status']; ?>"><?=$data['flashdata']['result']['message']; ?></div></div>
                        <?php endif; ?>
                        <?php if(!empty($data['check_token'])): ?>
                            <form method="post">
                                <input type="text" name="field_h" value="">
                                <div class="field password<?php if(isset($data['errors']) && isset($data['errors']['password'])): ?> error<?php endif; ?>">
                                    <div class="input">
                                        <input type="password" name="password" value="" placeholder="" />
                                        <span class="placeholder"><?=lang('Users.account.Password'); ?>*</span>
                                        <button type="button" class="show-password" data-hide="<?=lang('Users.account.Hide'); ?>" data-show="<?=lang('Users.account.Show'); ?>"><?=lang('Users.account.Show'); ?></button>
                                    </div>
                                    <?php if(isset($data['errors']) && isset($data['errors']['password'])):?>	
                                        <div class="alert alert-error"><?=$data['errors']['password'];?></div>
                                    <?php endif; ?>
                                    <p class="passinfo"><?=lang('Users.account.PasswordInfo'); ?></p>
                                </div>
                                <div class="field password<?php if(isset($data['errors']) && isset($data['errors']['password2'])): ?> error<?php endif; ?>">
                                    <div class="input">
                                        <input type="password" name="password2" value="" placeholder="" />
                                        <span class="placeholder"><?=lang('Users.account.RepeatPassword'); ?>*</span>
                                        <button type="button" class="show-password" data-hide="<?=lang('Users.account.Hide'); ?>" data-show="<?=lang('Users.account.Show'); ?>"><?=lang('Users.account.Show'); ?></button>
                                    </div>
                                    <?php if(isset($data['errors']) && isset($data['errors']['password2'])):?>	
                                        <div class="alert alert-error"><?=$data['errors']['password2'];?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="field btn-box">
                                    <input class="btn" type="submit" name="remind" value="<?= lang('Users.account.Save'); ?>" />
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
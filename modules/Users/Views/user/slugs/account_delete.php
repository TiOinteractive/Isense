
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="user-account">
    <div class="container">
        <div class="account-row">
            <div class="col col-sidebar">
                <?=view('Modules\Users\Views/user/slugs/account_menu'); ?>
            </div>
            <div class="col col-content">
                <div class="login-register-box">
                    <div class="registration-form">
                        <h1><?=lang('Users.account.DeleteAccount'); ?></h1>
                        <form method="post">
                            <?php if(isset($data['errors']) && isset($data['errors']['result'])):?>	
                                <div class="result alert alert-error"><?=$data['errors']['result'];?></div>
                            <?php endif; ?>
                            <?php if(!empty($data['flashdata']) && !empty($data['flashdata']['result'])): ?>
                                <div class="field"><div class="result alert alert-<?=$data['flashdata']['result']['status']; ?>"><?=$data['flashdata']['result']['message']; ?></div></div>
                            <?php endif; ?>
                            <div class="field password<?php if(isset($data['errors']) && isset($data['errors']['password'])): ?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="password" name="password" value="" placeholder="" />
                                    <span class="placeholder"><?=lang('Users.account.CurrentPassword'); ?>*</span>
                                    <button type="button" class="show-password" data-hide="<?=lang('Users.account.Hide'); ?>" data-show="<?=lang('Users.account.Show'); ?>"><?=lang('Users.account.Show'); ?></button>
                                </div>
                                <?php if(isset($data['errors']) && isset($data['errors']['password'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['password'];?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field agreement<?php if(isset($data['errors']) && isset($data['errors']['accept'])):?> error<?php endif; ?>">
                                <input type="checkbox" name="accept" value="1" id="agreement-accept" />
                                <label for="agreement-accept"><?=lang('Users.account.DeleteAcceptAgreement'); ?></label>
                                <?php if(isset($data['errors']) && isset($data['errors']['accept'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['accept'];?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="field btn-box">
                                <input class="btn" type="submit" name="account_delete" value="<?=lang('Users.account.Delete'); ?>" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="user-account">
    <div class="container">
        <div class="title resinet-title">
            <h1><?=lang('Users.account.UserAccount'); ?></h1>
        </div>
        <div class="account-row">
            <div class="col col-sidebar">
                <?=view('Modules\Users\Views/user/slugs/account_menu'); ?>
            </div>
            <div class="col col-content">
                <div class="login-register-box">
                    <div class="registration-form">
                        <h2><?=lang('Users.account.YourData'); ?></h2>
                        <form method="post">
                            <?php if(isset($data['errors']) && isset($data['errors']['result'])):?>	
                                <div class="result alert alert-error"><?=$data['errors']['result'];?></div>
                            <?php endif; ?>
                            <?php if(!empty($data['flashdata']) && !empty($data['flashdata']['result'])): ?>
                                <div class="field"><div class="result alert alert-<?=$data['flashdata']['result']['status']; ?>"><?=$data['flashdata']['result']['message']; ?></div></div>
                            <?php endif; ?>
                            <div class="field<?php if(isset($data['errors']) && isset($data['errors']['name'])):?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="text" name="name" value="<?php if(!empty($data['user']['name'])):?><?=$data['user']['name'];?><?php endif; ?>" placeholder="" />
                                    <span class="placeholder"><?=lang('Users.account.Name'); ?></span>
                                </div>
                                <?php if(isset($data['errors']) && isset($data['errors']['name'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['name'];?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field<?php if(isset($data['errors']) && isset($data['errors']['surname'])):?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="text" name="surname" value="<?php if(!empty($data['user']['surname'])): ?><?=$data['user']['surname'];?><?php endif; ?>" placeholder="" />
                                    <span class="placeholder"><?=lang('Users.account.Surname'); ?></span>
                                </div>
                                <?php if(isset($data['errors']) && isset($data['errors']['surname'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['surname'];?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field<?php if(isset($data['errors']) && isset($data['errors']['nick'])):?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="text" name="nick" value="<?php if(!empty($data['user']['nick'])): ?><?=$data['user']['nick'];?><?php endif; ?>" placeholder="" />
                                    <span class="placeholder"><?=lang('Users.account.Nick'); ?></span>
                                </div>
                                <?php if(isset($data['errors']) && isset($data['errors']['nick'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['nick'];?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field">
                                <div class="input">
                                    <input type="text" name="mail" value="<?php if(!empty($data['user']['mail'])):?><?=$data['user']['mail'];?><?php endif; ?>" readonly="readonly" />
                                    <span class="placeholder"><?=lang('Users.account.Email'); ?></span>
                                </div>
                            </div>
                            <div class="field agreement<?php if(isset($data['errors']) && isset($data['errors']['newsletter'])):?> error<?php endif; ?>">
                                <input type="checkbox" name="newsletter" value="1" id="agreement-newsletter"<?php if(!empty($data['user']['newsletter'])): ?> checked="checked"<?php endif; ?> />
                                <label for="agreement-newsletter"><?=lang('Users.account.AgreementNewsletter'); ?></label>
                                <?php if(isset($data['errors']) && isset($data['errors']['newsletter'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['newsletter'];?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field btn-box">
                                <input class="btn" type="submit" name="account" value="<?=lang('Users.account.Save'); ?>" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
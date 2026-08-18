<div class="user-login-form">
    <div class="container">
        <div class="login-register-box">
            <div class="login-quest-box">
                <div class="login-box">
                    <div class="login-form">
                        <?php if(isset($data['errors']) && isset($data['errors']['result'])):?>	
                            <div class="result alert alert-error"><?=$data['errors']['result'];?></div>
                        <?php endif; ?>
                        <?php if(!empty($data['flashdata']) && !empty($data['flashdata']['result'])): ?>
                            <div class="field"><div class="result alert alert-<?=$data['flashdata']['result']['status']; ?>"><?=$data['flashdata']['result']['message']; ?></div></div>
                        <?php endif; ?>
                        <h2><?=lang('Users.account.SignIn'); ?></h2>
                        <form method="post" action="/<?=$global_links['login'];?>">
                            <input type="hidden" name="return" value="<?=!empty($data['return']) ? $data['return'] : ''; ?>" />
                            <input type="text" name="field_h" value="">
                            <div class="field<?php if(isset($data['errors']) and isset($data['errors']['email'])):?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="text" name="email" value="<?php if(!empty($_POST['email'])): ?><?=$_POST['email'];?><?php endif; ?>" placeholder="" />
                                    <span class="placeholder"><?=lang('Users.account.Email'); ?></span>
                                </div>
                                <?php if(isset($data['errors']) && isset($data['errors']['email'])):?>	
                                    <div class="alert alert-error"><?=$data['errors']['email'];?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field password<?php if(isset($data['errors']) && isset($data['errors']['password'])):?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="password" name="password" value="" placeholder="" />
                                    <span class="placeholder"><?=lang('Users.account.Password'); ?></span>
                                    <button type="button" class="show-password" data-hide="<?=lang('Users.account.Hide'); ?>" data-show="<?=lang('Users.account.Show'); ?>"><?=lang('Users.account.Show'); ?></button>
                                </div>
                                <?php if(isset($data['errors']) and isset($data['errors']['password'])):?>	
                                     <div class="alert alert-error"><?=$data['errors']['password'];?></div>
                                 <?php endif; ?>
                                <a class="password-remind" href="/<?=$global_links['remind_password'];?>" title="<?=lang('Users.account.RemindPassword'); ?>" target="_blank"><?=lang('Users.account.RemindPassword'); ?></a>
                            </div>
                            <div class="field btn-box">
                                <input class="btn trans400" type="submit" name="signin" value="<?=lang('Users.account.LogIn'); ?>" />
                            </div>
                        </form>
                        <div class="social-btn-box">
                            <button class="fb-login-btn trans400"><svg viewBox="0 0 512 512"><path d="M288,192v-38.1c0-17.2,3.8-25.9,30.5-25.9H352V64h-55.9c-68.5,0-91.1,31.4-91.1,85.3V192h-45v64h45v192h83V256h56.4l7.6-64  H288z"/></svg><?=lang('Users.account.FBLogIn'); ?></button>
                        </div>
<?php /*
                        
<div id="g_id_onload"
     data-client_id="910512538764-8s74bk9nkd93h46q5vaiosttn1ubh0dp.apps.googleusercontent.com"
     data-context="signin"
     data-ux_mode="popup"
     data-callback="goggleLogin",
     data-auto_prompt="false">
</div>

<div class="g_id_signin"
     data-type="standard"
     data-shape="rectangular"
     data-theme="outline"
     data-text="signin_with"
     data-size="large"
     data-logo_alignment="left">
</div>
               <script src="https://accounts.google.com/gsi/client" async></script>         
*/ ?>
                        <div class="create-account-btn-box">
                            <a href="/<?= $global_links['registration']; ?>" title="<?= lang('Users.account.Register'); ?>" class="btn btn-create-account" target="_blank"><?= lang('Users.account.NoAccountCreateIt'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
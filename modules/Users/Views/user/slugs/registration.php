
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="user-login-form">
    <div class="container">
        <div class="login-register-box">
            <div class="register-box">
                <div class="register-cont">
                    <h2><?= lang('Users.account.CreateAnAccount'); ?></h2>
                    <?php if (!empty($data['flashdata']) && !empty($data['flashdata']['result'])): ?>
                        <div class="field"><div class="result alert alert-<?= $data['flashdata']['result']['status']; ?>"><?= $data['flashdata']['result']['message']; ?></div></div>
                    <?php endif; ?>
                    <div class="registration-form">
                        <form method="post">
                            <input type="text" name="field_h" value="">
                            <div class="field<?php if (isset($data['errors']) && isset($data['errors']['name'])): ?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="text" name="name" value="<?php if (!empty($data['post']['name'])): ?><?= $data['post']['name']; ?><?php endif; ?>" placeholder="" />
                                    <span class="placeholder"><?= lang('Users.account.Name'); ?></span>
                                </div>
                                <?php if (isset($data['errors']) && isset($data['errors']['name'])): ?>	
                                    <div class="alert alert-error"><?= $data['errors']['name']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field<?php if (isset($data['errors']) && isset($data['errors']['surname'])): ?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="text" name="surname" value="<?php if (!empty($data['post']['surname'])): ?><?= $data['post']['surname']; ?><?php endif; ?>" placeholder="" />
                                    <span class="placeholder"><?= lang('Users.account.Surname'); ?></span>
                                </div>
                                <?php if (isset($data['errors']) && isset($data['errors']['surname'])): ?>	
                                    <div class="alert alert-error"><?= $data['errors']['surname']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field<?php if (isset($data['errors']) && isset($data['errors']['email'])): ?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="text" name="email" value="<?php if (!empty($data['post']['email'])): ?><?= $data['post']['email']; ?><?php endif; ?>" placeholder="" />
                                    <span class="placeholder"><?= lang('Users.account.Email'); ?>*</span>
                                </div>
                                <?php if (isset($data['errors']) && isset($data['errors']['email'])): ?>	
                                    <div class="alert alert-error"><?= $data['errors']['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field password<?php if (isset($data['errors']) && isset($data['errors']['password'])): ?> error<?php endif; ?>">
                                <div class="input">
                                    <input type="password" name="password" value="" placeholder="" />
                                    <span class="placeholder"><?= lang('Users.account.Password'); ?>*</span>
                                    <button type="button" class="show-password" data-hide="<?= lang('Users.account.Hide'); ?>" data-show="<?= lang('Users.account.Show'); ?>"><?= lang('Users.account.Show'); ?></button>
                                </div>
                                <?php if (isset($data['errors']) && isset($data['errors']['password'])): ?>	
                                    <div class="alert alert-error"><?= $data['errors']['password']; ?></div>
                                <?php endif; ?>
                                <p class="passinfo"><?= lang('Users.account.PasswordInfo'); ?></p>
                            </div>
                            <div class="field agreement<?php if (isset($data['errors']) && isset($data['errors']['rules'])): ?> error<?php endif; ?>">
                                <input type="checkbox" name="rules" value="1" id="agreement-rules" />
                                <label for="agreement-rules">*<?= str_replace(array('{URL1}', '{NAME1}', '{URL2}', '{NAME2}'), array(!empty($global_links['shop_rules']) ? '/' . $global_links['shop_rules']['link'] : '/', !empty($global_links['shop_rules']) ? $global_links['shop_rules']['name'] : '', !empty($global_links['privacy_policy']) ? '/' . $global_links['privacy_policy']['link'] : '/', !empty($global_links['privacy_policy']) ? $global_links['privacy_policy']['name'] : ''), lang('Users.account.AgreementRules')); ?></label>
                                <?php if (isset($data['errors']) && isset($data['errors']['rules'])): ?>	
                                    <div class="alert alert-error"><?= $data['errors']['rules']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field agreement<?php if (isset($data['errors']) && isset($data['errors']['newsletter'])): ?> error<?php endif; ?>">
                                <input type="checkbox" name="newsletter" value="1" id="agreement-newsletter" />
                                <label for="agreement-newsletter"><?= lang('Users.account.AgreementNewsletter'); ?></label>
                                <?php if (isset($data['errors']) && isset($data['errors']['newsletter'])): ?>	
                                    <div class="alert alert-error"><?= $data['errors']['newsletter']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="field btn-box">
                                <input class="btn" type="submit" name="register" value="<?= lang('Users.account.Register'); ?>" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
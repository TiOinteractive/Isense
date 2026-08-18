<div class="newsletter-box">
    <form class="newsletter-form" action="<?=$locale ? '/' . $locale : ''; ?>/newsletter-action/add-email" method="post">
        <input type="text" name="field_h" value="" />
        <div class="field">
            <div class="input">
                <input type="text" name="email" value="<?php if (!empty($data['post']['email'])): ?><?= $data['post']['email']; ?><?php endif; ?>" placeholder="">
                <span class="placeholder"><?= lang('Users.account.Email'); ?>*</span>
            </div>
            <?php if (isset($data['errors']) && isset($data['errors']['email'])): ?>	
                <div class="alert alert-error"><?= $data['errors']['email']; ?></div>
            <?php endif; ?>
        </div>
        <div class="field agreement">
            <input type="checkbox" name="newsletter" value="1" id="agreement-newsletter-popup">
            <label for="agreement-newsletter-popup"><?= lang('Users.account.AgreementNewsletter'); ?></label>
        </div>
        <div class="field btn-box">
            <input class="btn trans400" type="submit" name="register" value="<?= lang('Users.account.Subscribe'); ?>" />
        </div>
    </form>
</div>
<?=view('emails/header'); ?>

<h4><?=lang('Newsletter.form.Hello'); ?>,</h4>
<?php if(!empty($count)): ?>
    <p><?=lang('Newsletter.form.ConfirmText'); ?>:</p>
    <a href="<?=$url; ?>" title="<?=lang('Newsletter.form.Confirm'); ?>"><?=lang('Newsletter.form.Confirm'); ?></a>
    <p><?=lang('Newsletter.form.ConfirmLink'); ?>:</p>
    <p><?=$url; ?></p>
<?php else: ?>
    <p><?=lang('Newsletter.form.ExistsText'); ?>:</p>
<?php endif; ?>
<hr />
<p><?=lang('Newsletter.form.DoNotReply'); ?></p>
<?=view('emails/footer'); ?>

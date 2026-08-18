<?=view('emails/header'); ?>
<h4><?=lang('Users.form.Hello'); ?>,</h4>
<p><?=lang('Users.form.ConfirmText'); ?>:</p>
<a href="<?=$url; ?>" title="<?=lang('Users.form.Confirm'); ?>"><?=lang('Users.form.Confirm'); ?></a>
<p><?=lang('Users.form.ConfirmLink'); ?>:</p>
<p><?=$url; ?></p>
<hr />
<p><?=lang('Users.form.DoNotReply'); ?></p>
<?=view('emails/footer'); ?>
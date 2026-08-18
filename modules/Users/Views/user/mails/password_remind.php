<?=view('emails/header'); ?>
<h4><?=lang('Users.form.Hello'); ?>,</h4>
<p><?=lang('Users.form.PasswordRemindText'); ?>:</p>
<a href="<?=$url; ?>" title="<?=lang('Users.form.ChangePassword'); ?>"><?=lang('Users.form.ChangePassword'); ?></a>
<p><?=lang('Users.form.PasswordRemindLink'); ?>:</p>
<p><?=$url; ?></p>
<hr />
<p><?=lang('Users.form.DoNotReply'); ?></p>
<?=view('emails/footer'); ?>
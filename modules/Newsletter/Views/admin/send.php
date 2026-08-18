<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=$newsletter['name']; ?>
            <span><?=lang('Newsletter.NewsletterSend');?></span>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form newsletter-send-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/<?php echo $action; ?><?=!empty($newsletter['id']) ? '/' . $newsletter['id'] : '' ; ?>" method="post" data-check-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/check-sending<?=!empty($newsletter['id']) ? '/' . $newsletter['id'] : '' ; ?>" data-status-url="<?=$locale ? '/' . $locale : ''; ?>/nosession/newsletter/check-sending<?=!empty($newsletter['hash']) ? '/' . $newsletter['hash'] : '' ; ?>" data-title="<?=lang('Newsletter.NewsletterDispatchSummary'); ?>" data-btn-ok="<?=lang('Newsletter.Send'); ?>" data-btn-cancel="<?=lang('Newsletter.Cancel'); ?>" data-btn-close="<?=lang('Newsletter.Close'); ?>">
            <div class="form-row nag">
                <h3><?=lang('Newsletter.NewsletterSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.Subject');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="subject" value="<?= !empty($newsletter['subject']) ? esc($newsletter['subject']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.FromName');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="from_name" value="<?= !empty($settings['form_sender']) ? esc($settings['form_sender']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.FromEmail');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="from_email" value="<?= !empty($settings['form_sender_email']) ? esc($settings['form_sender_email']) : esc($email_config->fromEmail); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.ReplyTo');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="reply_to" value="no-reply@<?=str_replace('www.', '', $_SERVER['HTTP_HOST']); ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.TestEmailAddress');?></label>
                </div>
                <div class="form-field">
                    <input class="short" type="text" name="test" value="<?= !empty($email['reply_to']) ? esc($email['reply_to']) : ''; ?>" >
                    <a class="btn small send-test" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/send-test<?=!empty($newsletter['id']) ? '/' . $newsletter['id'] : '' ; ?>"><?=lang('Newsletter.SendTest');?></a>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.Groups');?></label>
                </div>
                <div class="form-field">
                    <?php if(!empty($groups)): ?>
                        <?php foreach($groups as $group): ?>
                            <div>
                                <input type="checkbox" name="groups[<?=$group['id'];?>]" value="<?=$group['id'];?>" id="group-<?=$group['id'];?>" />
                                <label for="group-<?=$group['id'];?>"><?=$group['name'];?> (<b><?=$group['count'];?></b>)</label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.UtmSource');?></label>
                </div>
                <div class="form-field">
                    <input class="short" type="text" name="utm_source" value="<?= !empty($email['utm_source']) ? esc($email['utm_source']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.UtmMedium');?></label>
                </div>
                <div class="form-field">
                    <input class="short" type="text" name="utm_medium" value="<?= !empty($email['utm_medium']) ? esc($email['utm_medium']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.UtmCampaign');?></label>
                </div>
                <div class="form-field">
                    <input class="short" type="text" name="utm_campaign" value="<?= !empty($email['utm_campaign']) ? esc($email['utm_campaign']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.SendingDate');?></label>
                </div>
                <div class="form-field">
                    <input class="short datepicker-date time" type="text" name="sending_date" value="<?=date('d.m.Y H:i', strtotime('+5min')); ?>" >
                    <a class="btn small set-sending" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/set-sending<?=!empty($newsletter['id']) ? '/' . $newsletter['id'] : '' ; ?>"><?=lang('Newsletter.SetSending');?></a>
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class="send"><?=lang('Newsletter.SendNow');?></button>
            </div>     
        </form>
    </div>
</div>
<?php if(!empty($templates)): ?>
    <?php foreach($templates as $template): ?>
        <div class="template-item" data-template-id="<?=$template['id']; ?>"  data-btn-ok="<?=lang('Newsletter.emailbuilder.Load'); ?>" data-btn-cancel="<?=lang('Newsletter.emailbuilder.Cancel'); ?>" data-btn-close="<?=lang('Newsletter.emailbuilder.Close'); ?>" data-title="<?=lang('Newsletter.emailbuilder.NewsletterTemplateLoad'); ?>" data-message="<?=lang('Newsletter.emailbuilder.NewsletterTemplateLoadMessage'); ?>">
            <span class="name"><?=$template['name']; ?></span>
            <a class="delete-template-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/<?php echo $action; ?><?=!empty($newsletter['id']) ? '/' . $newsletter['id'] : '' ; ?>" title="<?=lang('Newsletter.Remove'); ?>" data-btn-ok="<?=lang('Newsletter.Remove'); ?>" data-btn-cancel="<?=lang('Newsletter.emailbuilder.Cancel'); ?>" data-btn-close="<?=lang('Newsletter.emailbuilder.Close'); ?>" data-title="<?=lang('Newsletter.emailbuilder.NewsletterTemplateDeleteForm'); ?>" data-message="<?=lang('Newsletter.emailbuilder.NewsletterTemplateDeleteFormMessage'); ?>: <b><?=esc($template['name']); ?></b>"><i class="fa-solid fa-xmark"></i></a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
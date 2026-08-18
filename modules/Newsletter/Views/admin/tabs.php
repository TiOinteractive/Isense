<div class="tabs page-content-tabs">
    <div class="tabs-head">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter" title="<?=lang('Newsletter.NewsletterList'); ?>" class="tab<?=empty($action) || $action=='' ? ' active' : ''; ?>"><span class="name"><?=lang('Newsletter.NewsletterList'); ?></span></a>
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/groups" title="<?=lang('Newsletter.GroupList'); ?>" class="tab<?=!empty($action) && $action=='groups' ? ' active' : ''; ?>"><span class="name"><?=lang('Newsletter.GroupList'); ?></span></a>
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mails" title="<?=lang('Newsletter.EmailList'); ?>" class="tab<?=!empty($action) && $action=='mails' ? ' active' : ''; ?>"><span class="name"><?=lang('Newsletter.EmailList'); ?></span></a>
    </div>
</div>
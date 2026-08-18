<?php if(!empty($event) && !empty($event['id']) && $action != 'copy'): ?>
    <div class="tabs page-content-tabs">
        <div class="tabs-head">
            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event<?=!empty($event) && !empty($event['id']) ? '/edit' : '/add' ; ?><?=!empty($event) && !empty($event['id']) ? '/' . $event['id'] : '' ; ?>" title="<?=!empty($event) && !empty($event['id']) ? lang('Event.EventEdit') : lang('Event.NewEventAdd'); ?>" class="tab<?= in_array($action, array('add', 'edit', 'save')) ? ' active' : ''; ?>"><span class="name"><?=!empty($event) && !empty($event['id']) ? lang('Event.EventEdit') : lang('Event.NewEventAdd'); ?></span></a>
            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/repertoire/<?=$event['id']; ?>" title="<?=lang('Event.AddEventRepertoire');?>" class="tab<?= $action=='repertoire' ? ' active' : ''; ?>"><span class="name"><?=lang('Event.AddEventRepertoire');?></span></a>
        </div>
    </div>
<?php endif; ?>
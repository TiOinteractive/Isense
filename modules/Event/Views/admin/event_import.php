<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head">
            <?= lang('Event.CalendarImport'); ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form event-import" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/<?php echo $action; ?>" method="post" data-title="<?=lang('Cinema.RepertoireSaving'); ?>" data-message="<?=lang('Cinema.RepertoireSavingInfo'); ?>" data-btn-close="<?=lang('Cinema.Close'); ?>" data-btn-cancel="<?=lang('Cinema.Cancel'); ?>">
            <div class="form-row nag">
                <h3><?= lang('Event.ImportData'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.ImportSource'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="source" value="<?=$source; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.ImportType'); ?></label>
                </div>
                <div class="form-field">
                    <select name="option" class="cinema-import-template">
                        <option value="types"<?php if(!empty($post) && !empty($post['option']) && $post['option'] == 'types'): ?> selected="selected"<?php endif; ?>><?=lang('Event.import.EventTypes'); ?></option>
                        <option value="places"<?php if(!empty($post) && !empty($post['option']) && $post['option'] == 'places'): ?> selected="selected"<?php endif; ?>><?=lang('Event.import.EventPlaces'); ?></option>
                        <option value="events"<?php if(!empty($post) && !empty($post['option']) && $post['option'] == 'events'): ?> selected="selected"<?php endif; ?>><?=lang('Event.import.Events'); ?></option>
                    </select>
                </div>
            </div>
            <div class="import-content">
                <?php 
                if(!empty($post) && !empty($post['option'])) {
                    switch($post['option']) {
                        case 'places': echo view('Modules\Event\Views\admin\event_import_places', array());
                            break;
                        case 'types': echo view('Modules\Event\Views\admin\event_import_types', array());
                            break;
                    }
                } 
                ?>
            </div>
            <?php if(!empty($stats)): ?>
                <div class="stats">
                    <div class="form-row">
                        <div class="form-label">
                            <?=lang('Event.Status'); ?>:
                        </div>
                        <div class="form-field">
                            <b><?=$stats['result'] ? lang('Event.OK') : lang('Event.Error'); ?></b>
                        </div>
                    </div>
                    <?php if($stats['result']): ?>
                        <div class="form-row">
                            <div class="form-label">
                                <?=lang('Event.Created'); ?>:
                            </div>
                            <div class="form-field">
                                <b><?=count($stats['created']); ?></b>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <?=lang('Event.Updated'); ?>:
                            </div>
                            <div class="form-field">
                                <b><?=count($stats['updated']); ?></b>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <?=lang('Event.Removed'); ?> (<?=lang('Event.Unpublished'); ?>):
                            </div>
                            <div class="form-field">
                                <b><?=count($stats['removed']); ?></b>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <?=lang('Event.Duplicated'); ?>:
                            </div>
                            <div class="form-field">
                                <b><?=count($stats['duplicated']); ?></b>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Event.Check') . ' / ' . lang('Event.Assign'); ?></button>
            </div>
        </form>
    </div>
</div>
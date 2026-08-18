<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
        <?= lang('Event.NewMassEventsAdd'); ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form cinema-movie-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/<?php echo $action; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Event.EventSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Template'); ?></label>
                </div>
                <div class="form-field">
                    <select name="template">
                        <?php if(!empty($templates)): ?>
                            <?php foreach($templates as $template): ?>
                                <option value="<?= $template['file']; ?>"<?= !empty($movie) && $movie['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?= $template['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row nag">
                <h3><?= lang('Event.EventMedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.PrimaryPhoto'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($events)): ?>
                            <?php foreach($events as $k => $event): ?>
                                <?= view('Modules\Event\Views\admin\upload_events', array('types' => $types, 'event' => $event, 'field' => 'movies', 'file' => $event, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="events" data-option="events" data-module="event" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Event.Save'); ?></button>
            </div>
        </form>
    </div>
</div>
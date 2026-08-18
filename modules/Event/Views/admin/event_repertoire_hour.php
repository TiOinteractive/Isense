<div class="event-hour">
    <input class="datepicker-time" type="text" name="hour[]" value="<?=!empty($hour) ? $hour : ''; ?>" autocomplete="off" >
    <?php if(!empty($remove)): ?>
        <div class="delete">
            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/delete-event-hour" class="delete-event-hour" title="<?=lang('Event.Remove'); ?>" ><i class="fa-regular fa-trash-can"></i></a>
        </div>
    <?php endif; ?>
</div>
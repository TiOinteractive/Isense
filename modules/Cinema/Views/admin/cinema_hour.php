<div class="time-box" data-h="<?=$h; ?>">
    <b class="hnr"><?=lang('Cinema.Hour'); ?> #<?=$h + 1; ?>:</b>
    <div class="hour">
        <?=lang('Cinema.Hours'); ?>:
        <table>
            <tr>
                <?php for($i=8;$i<24;$i++): ?>
                    <td>
                        <input id="hour-h-<?=$h; ?>-<?=$i; ?>" type="radio" value="<?=$i; ?>" name="hour[<?=$h; ?>][h]" <?php if(!empty($hour) && !empty($hour['h']) && $hour['h']==$i): ?>checked="checked"<?php endif; ?> />
                        <label for="hour-h-<?=$h; ?>-<?=$i; ?>"><?=$i; ?></label>
                    </td>
                <?php endfor; ?>
            </tr>
        </table>
    </div>
    <div class="minute">
        <?=lang('Cinema.Minutes'); ?>:
        <table>
            <tr>
                <?php for($i=0;$i<60;$i+=5): ?>
                    <td>
                        <input id="hour-m-<?=$h; ?>-<?=$i; ?>" type="radio" value="<?=$i; ?>" name="hour[<?=$h; ?>][m]" <?php if(!empty($hour) && !empty($hour['m']) && $hour['m']==$i): ?>checked="checked"<?php endif; ?> />
                        <label for="hour-m-<?=$h; ?>-<?=$i; ?>"><?=$i; ?></label>
                    </td>
                <?php endfor; ?>
            </tr>
        </table>
    </div>
    <div class="option">
        <input id="hour-<?=$h; ?>-special" type="checkbox" name="hour[<?=$h; ?>][special]" <?php if(!empty($hour) && !empty($hour['special']) && $hour['special']): ?>checked="checked"<?php endif; ?>  value="1" >
        <label for="hour-<?=$h; ?>-special"><?= lang('Cinema.SpecialShow'); ?></label>
    </div>
    <div class="option">
        <input id="hour-<?=$h; ?>-surprise" type="checkbox" name="hour[<?=$h; ?>][surprise]" <?php if(!empty($hour) && !empty($hour['surprise']) && $hour['surprise']): ?>checked="checked"<?php endif; ?>  value="1" >
        <label for="hour-<?=$h; ?>-surprise"><?= lang('Cinema.SessionWithSurprises'); ?></label>
    </div>
    <?php if(!empty($remove)): ?>
        <div class="delete">
            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/cinema/delete-cinema-hour" class="delete-cinema-hour" title="<?=lang('Cinema.Remove'); ?>" ><i class="fa-regular fa-trash-can"></i></a>
        </div>
    <?php endif; ?>
</div>
<?php if(!empty($statistics)):?>
    <?php if(!empty($statistics['added'])): ?>
        <div class="alert-box success">
            <p><?=lang('Event.EventAddedList'); ?></p>
            <ul>
                <?php foreach($statistics['added'] as $s): ?>
                    <li>
                        <?=lang('Event.EventDate'); ?>: <strong><?=date('d.m.Y', strtotime($s['date_start'])); ?><?=!empty($s['date_end']) ? ' - ' . date('d.m.Y', strtotime($s['date_end'])) : ''; ?><?=!empty($s['hour']) ? ' ' . date('H:i', strtotime($s['hour'])) : ''; ?></strong>
                        <?php if(!empty($s['id_place'])): ?><?=lang('Event.EventPlace'); ?>: <strong><?=!empty($places[$s['id_place']]) ? $places[$s['id_place']]['name'] : 'ID: ' . $s['id_place']; ?></strong><?php elseif(!empty($s['custom_place'])): ?><?=lang('Event.EventPlace'); ?>: <strong><?=$s['custom_place']; ?></strong><?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="close"><i class="fas fa-times"></i></button>
        </div>
    <?php endif; ?>

    <?php if(!empty($statistics['exists'])): ?>
        <div class="alert-box info">
            <p><?=lang('Event.EventExistsList'); ?></p>
            <ul>
                <?php foreach($statistics['exists'] as $s): ?>
                    <li>
                        <?=lang('Event.EventDate'); ?>: <strong><?=date('d.m.Y', strtotime($s['date_start'])); ?><?=!empty($s['date_end']) ? ' - ' . date('d.m.Y', strtotime($s['date_end'])) : ''; ?><?=!empty($s['hour']) ? ' ' . date('H:i', strtotime($s['hour'])) : ''; ?></strong>
                        <?php if(!empty($s['id_place'])): ?><?=lang('Event.EventPlace'); ?>: <strong><?=!empty($places[$s['id_place']]) ? $places[$s['id_place']]['name'] : 'ID: ' . $s['id_place']; ?></strong><?php elseif(!empty($s['custom_place'])): ?><?=lang('Event.EventPlace'); ?>: <strong><?=$s['custom_place']; ?></strong><?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="close"><i class="fas fa-times"></i></button>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php if(!empty($statistics)):?>
    <?php if(!empty($statistics['added'])): ?>
        <div class="alert-box success2">
            <p><?=lang('Cinema.CinemaAddedList'); ?></p>
            <ul>
                <?php foreach($statistics['added'] as $s): ?>
                    <li>
                        <?php if(!empty($s['id_movie'])): ?><?=lang('Cinema.Movie'); ?>: <strong><?=!empty($movies[$s['id_movie']]) ? $movies[$s['id_movie']]['title'] : (!empty($movie) ? $movie['title'] : 'ID: ' . $s['id_movie']); ?></strong><?php endif; ?>
                        <?php if(!empty($s['id_type'])): ?><?=lang('Cinema.Type'); ?>: <strong><?=!empty($types[$s['id_type']]) ? $types[$s['id_type']]['name'] : 'ID: ' . $s['id_type']; ?></strong><?php endif; ?>
                        <?php if(!empty($s['date'])): ?><?=lang('Cinema.CinemaDate'); ?>: <strong><?=strlen($s['date']) > 10 ? date('d.m.Y H:i', strtotime($s['date'])) : date('d.m.Y', strtotime($s['date'])); ?></strong><?php endif; ?>
                        <?php if(!empty($s['id_place'])): ?><?=lang('Cinema.CinemaPlace'); ?>: <strong><?=!empty($places[$s['id_place']]) ? $places[$s['id_place']]['name'] : 'ID: ' . $s['id_place']; ?></strong><?php endif; ?>
                        <?php if(!empty($s['special']) || !empty($s['surprise']) || !empty($s['premiere']) || !empty($s['pre-premiere'])): $is=false; ?>
                            <?=lang('Cinema.Options'); ?>:
                            <strong><?php if(!empty($s['special'])): ?><?=($is ? ', ' : '') . lang('Cinema.SpecialShow'); ?><?php $is=true; endif; ?><?php if(!empty($s['surprise'])): ?><?=($is ? ', ' : '') . lang('Cinema.SessionWithSurprises'); ?><?php $is=true; endif; ?><?php if(!empty($s['premiere'])): ?><?=($is ? ', ' : '') . lang('Cinema.Premiere'); ?><?php $is=true; endif; ?><?php if(!empty($s['pre-premiere'])): ?><?=($is ? ', ' : '') . lang('Cinema.PrePremiere'); ?><?php $is=true; endif; ?></strong>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="close"><i class="fas fa-times"></i></button>
        </div>
    <?php endif; ?>

    <?php if(!empty($statistics['exists'])): ?>
        <div class="alert-box info">
            <p><?=lang('Cinema.CinemaExistsList'); ?></p>
            <ul>
                <?php foreach($statistics['exists'] as $s): ?>
                    <li>
                        <?php if(!empty($s['id_movie'])): ?><?=lang('Cinema.Movie'); ?>: <strong><?=!empty($movies[$s['id_movie']]) ? $movies[$s['id_movie']]['title'] : (!empty($movie) ? $movie['title'] : 'ID: ' . $s['id_movie']); ?></strong><?php endif; ?>
                        <?php if(!empty($s['id_type'])): ?><?=lang('Cinema.Type'); ?>: <strong><?=!empty($types[$s['id_type']]) ? $types[$s['id_type']]['name'] : 'ID: ' . $s['id_type']; ?></strong><?php endif; ?>
                        <?php if(!empty($s['date'])): ?><?=lang('Cinema.CinemaDate'); ?>: <strong><?=strlen($s['date']) > 10 ? date('d.m.Y H:i', strtotime($s['date'])) : date('d.m.Y', strtotime($s['date'])); ?></strong><?php endif; ?>
                        <?php if(!empty($s['id_place'])): ?><?=lang('Cinema.CinemaPlace'); ?>: <strong><?=!empty($places[$s['id_place']]) ? $places[$s['id_place']]['name'] : 'ID: ' . $s['id_place']; ?></strong><?php endif; ?>
                        <?php if(!empty($s['special']) || !empty($s['surprise']) || !empty($s['premiere']) || !empty($s['pre-premiere'])): $is=false; ?>
                            <?=lang('Cinema.Options'); ?>:
                            <strong><?php if(!empty($s['special'])): ?><?=($is ? ', ' : '') . lang('Cinema.SpecialShow'); ?><?php $is=true; endif; ?><?php if(!empty($s['surprise'])): ?><?=($is ? ', ' : '') . lang('Cinema.SessionWithSurprises'); ?><?php $is=true; endif; ?><?php if(!empty($s['premiere'])): ?><?=($is ? ', ' : '') . lang('Cinema.Premiere'); ?><?php $is=true; endif; ?><?php if(!empty($s['pre-premiere'])): ?><?=($is ? ', ' : '') . lang('Cinema.PrePremiere'); ?><?php $is=true; endif; ?></strong>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="close"><i class="fas fa-times"></i></button>
        </div>
    <?php endif; ?>
<?php endif; ?>
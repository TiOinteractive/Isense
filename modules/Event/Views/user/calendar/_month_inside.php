<div class="event-calendar">
    <?php
        if(!empty($data['date']) && strtotime(date('Y-m', strtotime($data['date']))) >= strtotime(date('Y-m'))) {
            $year = date('Y', strtotime($data['date']));
            $month = date('m', strtotime($data['date']));
            if(strlen($data['date']) == 10) {
                $current = date('Y-m-d', strtotime($data['date']));
            } elseif(date('Y-m', strtotime($data['date'])) == date('Y-m')) {
                $current = date('Y-m-d');
            } else {
                $current = null;
            }
            $prev_month = date('Y-m', strtotime($data['date'] . ' - 1 month'));
            $next_month = date('Y-m', strtotime($data['date'] . ' + 1 month'));
        } else {
            $year = date('Y');
            $month = date('m');
            $current = date('Y-m-d');
            $prev_month = date('Y-m', strtotime('- 1 month'));
            $next_month = date('Y-m', strtotime('+ 1 month'));
        }
        $date_start = date('Y-m-d', strtotime($year . '-' . $month . '-01' . ' - ' . (date('N', strtotime($year . '-' . $month . '-01')) - 1) . ' days'));
        $date_end = date('Y-m-d', strtotime($year . '-' . $month . '-01 + 1 month - 1 day'));
        $today = date('d');
        $tomonth = date('m');
        $toyear = date('Y');
        $day_no = date('N', strtotime($date_end));
        if($day_no != 7) {
            $date_end = date('Y-m-d', strtotime($date_end . ' + ' . (7 - $day_no) . ' days'));
        }
        $date = $date_start;
    ?>
    <div class="header">
        <a class="arrow prev<?=$tomonth == $month && $toyear == $year ? ' disabled' : ''; ?>" href="/<?=$global_links['calendar']; ?>" title="" data-month="<?=$prev_month; ?>">
            <svg viewBox="0 0 96 96"><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg>
        </a>
        <div class="date">
            <?php if(!empty($global_links['calendar'])): ?><a class="month" href="/<?=$global_links['calendar']; ?>/g/d/01.<?=$month; ?>.<?=$year; ?>-<?=date('t', strtotime($year . '-' . $month . '-01')); ?>.<?=$month; ?>.<?=$year; ?>" title="<?=lang('Event.months_names_no.' . intval($month)); ?> <?=$year; ?>"><?php endif; ?>
                <span class="month"><?=lang('Event.months_names_no.' . intval($month)); ?></span> <span class="year"><?=$year; ?></span>
            <?php if(!empty($global_links['calendar'])): ?></a><?php endif; ?>
        </div>
        <a class="arrow next<?=$tomonth == $month && $toyear + 1 == $year ? ' disabled' : ''; ?>" href="/<?=$global_links['calendar']; ?>" title="" data-month="<?=$next_month; ?>">
            <svg viewBox="0 0 96 96"><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg>
        </a>
    </div>
    <div class="content">
        <div class="day-names">
            <?php for($i=1;$i<=7;$i++): ?>
                <div class="day"><?=lang('Event.days_short_names_no.' . $i); ?></div>
            <?php endfor; ?>
        </div>
        <div class="days">
            <?php while($date <= $date_end): ?>
                <div class="day<?=$date == $current ? ' current' : ''; ?><?=$month==substr($date,5,2) ? '' : ' disabled'; ?><?=substr($date,8,2) < $today && $month==substr($date,5,2) ? ' disabled' : ''; ?>"<?php if(substr($date,8,2) >= $today && $month==substr($date,5,2) ): ?> data-count="<?=!empty($data['list']) && !empty($data['list'][$date]) ? lang('Event.EventsNumber') . ': ' . $data['list'][$date] : lang('Event.NoEvents'); ?>"<?php endif; ?>>
                    <?php if(!empty($global_links['calendar']) && ((substr($date,8,2) >= $today && $month==substr($date,5,2)) || $tomonth < substr($date,5,2) || $toyear < substr($date,0,4))): ?><a href="/<?=$global_links['calendar']; ?>/g/d/<?=date('d.m.Y', strtotime($date)); ?>" title="<?=date('d.m.Y', strtotime($date)); ?>" data-date="<?=date('d.m.Y', strtotime($date)); ?>"><?php else: ?><span><?php endif; ?>
                        <?=substr($date,8,2); ?>
                    <?php if(!empty($global_links['calendar']) && ((substr($date,8,2) >= $today && $month==substr($date,5,2)) || $tomonth < substr($date,5,2) || $toyear < substr($date,0,4))): ?></a><?php else: ?></span><?php endif; ?>
                </div>
            <?php $date = date('Y-m-d', strtotime($date . ' + 1 day')); endwhile; ?>
        </div>
    </div>
</div>
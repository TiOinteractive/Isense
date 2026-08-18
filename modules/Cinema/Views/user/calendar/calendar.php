<?php
/* 
Kalendarium kin
 */
?>
<?php if(!empty($data) && !empty($data['dates'])): ?>
    <section class="section-<?=$id_cont; ?> cinema-calendar">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title resinet-title">
                   <h1 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h1>
                    <?php if(!empty($subtitle)): ?>
                        <h3 class="h-2"><?=$subtitle; ?></h3>
                    <?php endif; ?>
                    <?php if($url):?><a class="more" href="<?=$url; ?>" title="<?=esc(lang('User.entertainment.CheckFullRepertoire')); ?>"><?=lang('User.entertainment.CheckFullRepertoire'); ?></a><?php endif; ?>
               </div>	
            <?php endif; ?>     
           

<?php if(!empty($data['dates'])): ?>
  <div class="search-engine-2">
      <div class="events-form">
	      <div class="search-carousel">
                <div class="cinema-carousel-wrap">
				    <?php foreach($data['dates'] as $k=>$date): ?>
				
						<?php
						
						    $today_time=strtotime('now');
                            $diff = (strtotime($date['date']) - $today_time) / (24 * 60 * 60);
                            $day_no = date('N', strtotime($date['date']));
                            $day_name = '';
                            switch ($diff) {
                                case 0: $day_name = lang('Event.Today');
                                    break;
                                case 1: $day_name = lang('Event.Tomorrow');
                                    break;
                                case 2: $day_name = lang('Event.AfterTomorrow');
                                    break;
                                default: $day_name = lang('Event.days_names_no.' . $day_no);
                                    break;
                            }
                        ?>
				
						<div class="day day-no-<?=$day_no; ?><?php if($date['active']): ?> active<?php endif; ?>">
                            <div class="header" data-date="<?=date('d.m.Y', strtotime($date['date'])); ?>">
                                <?php if(in_array($day_no, array(5,6,7))): ?><span class="day-weekend"><?=lang('Event.user.Weekend'); ?></span><?php endif; ?>
                                <span class="day-name"><?=$day_name; ?></span>
                            </div>
                            <div class="day-date<?php if(empty($date['count'])): ?> no-movies<?php endif; ?>" data-date="<?=date('d.m.Y', strtotime($date['date'])); ?>">
                                <a<?php if($date['active']): ?> class='active'<?php endif; ?> href="<?=$date['link']; ?>" title="<?=lang('Cinema.days_names_no.' . $date['day_no']); ?> <?=$date['day']; ?>" data-data="<?=$date['date']; ?>"><strong><?=date('d', strtotime($date['date'])); ?></strong>
                                <span><?=lang('Event.user.months_names_no2.' . date('n', strtotime($date['date']))); ?></span></a>
                            </div>
                        </div>
				
				
				   <?php endforeach; ?>
				</div>
		  </div>		
	  </div>
	    </div>
<?php endif; ?>	  
      <div class="calendar-repertoire">
			<?php if(!empty($data['calendar'])): ?>
                    <div class='movies'>
                        <?php if($mobile): ?>
                            <?= view('\Modules\Cinema\Views\user/calendar/_m_calendar_repertoire', array('movies'=>$data['calendar'], 'types'=>$data['types'], 'cinemas'=>$data['cinemas'])); ?>
                        <?php else: ?>
                            <?= view('\Modules\Cinema\Views\user/calendar/_calendar_repertoire', array('movies'=>$data['calendar'], 'types'=>$data['types'], 'cinemas'=>$data['cinemas'])); ?>
                        <?php endif; ?>
                    </div>
            <?php endif; ?>
	  </div>		
    </section>
<?php endif; ?>
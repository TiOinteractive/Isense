<?php
/* 
Event place tpl
*/
?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 11, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<section class="place-single place-single-<?=$data['id'];?>">
     <div class="container">
	
        <div class="entertainment-row">
            <div class="col col-content">   
           		<div class="title resinet-title">
                    <h2 class="h-1"><?=$data['place_type_name']; ?></h2>
                </div>
		   
		   	     <?php if(!empty($data['photos'])): ?>
                    <div class="photos">
                        <?php foreach($data['photos'] as $k=>$photo): ?>
                            <?php if(!$k): ?>
                                <div class="photo main">
                                    <div class="photo-cont">
                                        <picture>
                                            <a href="/image/r/1200/1200/<?=$photo['path'];?>" rel="lightbox" data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?php if(!empty($photo['caption'])):?><?=$photo['caption'];?><?php else: ?><?=$data['name'];?> - galeria zdjęć<?php endif; ?>"><img src="/image/c/900/570/<?=$photo['path']; ?>" alt="<?=esc($photo['caption'] ? $photo['caption'] : $data['name']); ?>" class="trans400" /></a>
                                        </picture>
										<?php if(!empty($data['path'])): ?>
										 <div class="logo"><img src="/image/r/190/190/<?=$data['path']; ?>" alt="<?=esc($data['name']); ?>"  /></div>
										<?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="photo">
                                    <div class="photo-cont">
                                        <picture>
                                            <a href="/image/r/1200/1200/<?=$photo['path'];?>" rel="lightbox" data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?php if(!empty($photo['caption'])):?><?=$photo['caption'];?><?php else: ?><?=$data['name'];?> - galeria zdjęć<?php endif; ?>"><img src="/image/c/300/190/<?=$photo['path']; ?>" alt="<?=esc($photo['caption'] ? $photo['caption'] : $data['name']); ?>" class="trans400" /><span class="show_cnt">+<?=(count($data['photos'])-($k+1)); ?></span></a>
                                        </picture>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
		   
		   
		      <h1><?=$data['name']; ?></h1>
		   	   <?php if(!empty($data['place_type_name'])): ?>
			      <h3 class="place_type"><a href="/<?=$data['place_type_link']; ?>" title="<?=esc($data['place_type_name']); ?>"><?=$data['place_type_name']; ?></a></h3>
			   <?php endif; ?>
		   <div class="place_details">
		   
		   <?php if(!empty($data['address'])): ?>
                                <div class="row_details">
								   <div class="detail">
								    <label><?=lang('User.entertainment.Address'); ?>:</label>
									<div class="place_address"><?=$data['address']; ?></div>
								   </div>
                                </div>
                    <?php endif; ?>
			       <?php if(!empty($data['email'])): ?>
                                 <div class="row_details">
								   <div class="detail">
                                   <label><?=lang('User.entertainment.Email'); ?>:</label>
                                   <a href="mailto:<?=$data['email']; ?>" title="<?=$data['email']; ?>"><?=$data['email']; ?></a>
                                 </div>
                                </div>
                  <?php endif; ?>
				 <?php if(!empty($data['working_hours'])): ?>
                                 <div class="row_details">
								   <div class="detail">
                                   <label><?=lang('Event.WorkingHours'); ?>:</label>
                                   <?=nl2br($data['working_hours']); ?>
                                 </div>
                                </div>
                  <?php endif; ?> 
				  
				  
			 <?php if(!empty($data['phone'])): ?>
                                 <div class="row_details">
								   <div class="detail">
                                   <label><?=lang('User.entertainment.Phone'); ?>:</label>
								   <?php
								   $phones_list=explode(',',$data['phone']);
								   if(!empty($phones_list)):
								     foreach($phones_list as $k=>$phone):
								   ?>
								     <?php if($k>0):?>, <?php endif; ?><a href="tel:<?=str_replace(' ','',$phone);?>"><?=trim($phone);?></a>
								   <?php 
									endforeach;
								    endif;
								   ?>
								   
                                 </div>
                                </div>
                  <?php endif; ?>
			
			<?php if(!empty($data['www'])): ?>
                                 <div class="row_details">
								   <div class="detail">
                                   <label>Strona internetowa:</label>
                                   <a href="<?=!str_contains($data['www'], 'http') ? 'http://' : ''; ?><?=$data['www']; ?>" title="<?=$data['www']; ?>" target="_blank" rel="nofollow"><?=$data['www']; ?></a>
                                 </div>
                                </div>
                  <?php endif; ?>
		   
		   
			  <?php if(!empty($data['events'])): ?>
			    <div class="btn"><a href="javascript:GoToPlaceEvents();">SPRAWDŹ REPERTUAR <i class="fa-solid fa-chevron-down"></i></a></div>
			  <?php endif; ?>
		   </div>
		   
		   
		   
		   
                <?php if(!empty($data['content'])): ?>
                    <div class="content">
					 <div class="title"><h2 class="h-1">Opis</h2></div>
					<?=$data['content']; ?></div>
                <?php endif; ?>
                <?php if(!empty($data['repertoire'])): ?>
                    <div class="repertoire">
                        <div class="title">
                            <h2 class="h-1"><?=lang('User.entertainment.Repertoire'); ?></h2>
                        </div>
                        <?=$data['repertoire']; ?>
                    </div>
                <?php endif; ?>
                <?php if(!empty($data['calendar']) || !empty($data['dates'])): ?>
                    <div class="calendar">
                        <div class="title">
                            <h2 class="h-1"><?=lang('User.entertainment.CurrentRepertoire'); ?></h2>
                        </div>
						
						
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
                                    <?= view('\Modules\Cinema\Views\user/calendar/_calendar_repertoire', array('movies'=>$data['calendar'], 'types'=>$data['types'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(!empty($data['events'])): ?>
                    <div class="events closest-events-list">
                        <div class="title">
                            <h2 class="h-1">Repertuar</h2>
                        </div>
                        <div class="list-2">
                            <?php foreach($data['events'] as $event): ?>
                                <div class="event-item event-item-<?=$event['id']; ?>">
                                    <div class="event-item-cont">
                                        <div class="photo">
                                            <div class="photo-cont">
                                                <?php if($event['photo']): ?>
                                                    <a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>">
                                                        <picture>
                                                            <img src="/image/c/600/400/<?=$event['photo']; ?>" alt="<?=esc($event['name']); ?>" class="trans400" />
                                                        </picture>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="info">
                                            <?php if(!empty($event['type_name'])): ?>
                                                <div class="type">
                                                    <a href="/<?=$event['type_link']; ?>" title="<?=esc($event['type_name']); ?>"><?=$event['type_name']; ?></a>
                                                </div>
                                            <?php endif; ?>
											 <h3 class="name"><a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></h3>
											 <div class="details">
                                                        <div class="date">
                                                            <div class="date-cont">
                                                                <?php if(empty($event['date_end'])): ?>
                                                                        <strong><?=date('d', strtotime($event['date_start'])); ?></strong>
                                                                        <span><?=lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                                                    <?php elseif(time()>=strtotime($event['date_start']) && time()<strtotime($event['date_end'] . ' + 1 day')): ?>
                                                                        <strong><?=date('d'); ?></strong>
                                                                        <span><?=lang('Admin.months_short_names.' . date('F')); ?></span>
                                                                    <?php else: ?>
                                                                        <strong><?=date('d', strtotime($event['date_start'])); ?></strong>
                                                                        <span><?=lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                                                    <?php endif; ?>
															</div>
														</div>
                                                        <div class="hours">
                                                            <p class="label">Godzina</p>
                                                            <p class="value"><span><?=$event['hours'];?></span></p>
                                                        </div>	
														<div class="tickets">
                                                            <?php if (!empty($event['price'])): ?>
                                                                <p class="label"><?=lang('Users.user.Tickets'); ?></p>
                                                                <p class="value"><?=str_replace(',',' /',$event['price']); ?></p>
                                                            <?php endif; ?>
														 </div>	
                                            </div>
											<?php if (!empty($event['for_kids'])): ?>
												<div class="for-kids">
													<svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none" stroke="none"/><circle cx="12" cy="12" r="9"/><line x1="9" x2="9.01" y1="10" y2="10"/><line x1="15" x2="15.01" y1="10" y2="10"/><path d="M9.5 15a3.5 3.5 0 0 0 5 0"/><path d="M12 3a2 2 0 0 0 0 4"/></svg> <span><?=lang('Users.user.ForKids'); ?></span>
												</div>
											<?php endif; ?>
											<?php if(!empty($event['source'])): ?>
												<div class="source"><?=lang('Event.BuyTicket'); ?></div>
											<?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
				<?php if(empty($data['calendar']) and empty($data['events'])): ?>
				<p>Brak wydarzeń i repertuaru dla wybranej daty</p>
				<?php endif;?>
                <?php if(!empty($data['comment'])): ?>
                    <?= view_cell('\Modules\Comments\Libraries\Comments::showCommentsForm', ['id_lang' => $id_lang, 'locale' => $locale, 'id_link' => $data['id_link']]); ?>
                <?php endif; ?>
            </div>
            <div class="col col-sidebar">
                <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => 7, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
        </div>
    </div>
</section>
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
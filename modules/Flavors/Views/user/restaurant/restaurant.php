<?php if(!empty($data['page_content']['restaurant'])):
	$restaurant=$data['page_content']['restaurant'];
	helper('text');		
	
	
?>
<article id="restaurant-single"<?php if(!empty($restaurant['archives'])): ?> class="restaurant-archives"<?php endif; ?>>
    <?php if(!empty($mobile)):?>
             <header>
			     <?php if(!empty($restaurant['categories'])):?>  
					<div class="cat">
						<?php foreach($restaurant['categories'] as $k=>$category):?><?php if($k<3):?><?php if($k>0):?> / <?php endif;?><a href="/<?=$category['link'];?>" class="category" title="<?=$category['name'];?>"><?=$category['name'];?></a><?php endif;?><?php endforeach;?>
				    </div> 
				 <?php endif;?>
			  <h1><?=$restaurant['name'];?> <?=$restaurant['name2'];?></h1>
			</header>
			<div class="rating">
			   <div>
			    <?php if(!empty($restaurant['avg']['rating'])):?> 
				 <h4><?=lang('Flavors.OverallrestaurantRating');?>:</h4>
				 <div id="rate_lokal_<?=$restaurant['id'];?>" class="rate_lokal" data-rate="<?=number_format($restaurant['avg']['rating'], 1, '.', '');?>">
				   <?php if(!empty($restaurant['avg']['rating'])):?><div class="rate"><span><?=number_format($restaurant['avg']['rating'], 1, ',', '');?></span>/5</div><?php endif;?>
				 </div>
						<p onclick="RscrollTo('div.rates')" class="trans400">(Liczba ocen: <b><?=$restaurant['avg']['cnt'];?></b>)</p>
				<?php else:?>
                  <p>Ten lokal nie ma jeszcze ocen.<br />Bądź pierwszym, który go oceni.</p>
                <?php endif;?>				
				 </div>
			   <div class="rate_btn">
			     <a href="/<?=$restaurant['link'];?>" onclick="RateRestaurant(this,<?=$restaurant['id'];?>);return false;">Oceń lokal <svg viewBox="0 0 48 48"><path d="M0 0h48v48H0z" fill="none"/><path d="M24 4C12.95 4 4 12.95 4 24s8.95 20 20 20 20-8.95 20-20S35.05 4 24 4zm10 22h-8v8h-4v-8h-8v-4h8v-8h4v8h8v4z"/></svg></a>
			   </div>
			</div>
    <?php endif;?>
      <div class="column">
	    <div class="main">
				<div id="main_photo">
					<?php if(!empty($restaurant['logo']['path'])): ?>
						<div class="logo"><img src="/image/r/150/100/<?=$restaurant['logo']['path'];?>" alt="<?php if(!empty($restaurant['logo']['caption'])):?><?=$restaurant['logo']['caption'];?><?php else:?><?=$restaurant['name'];?><?php endif;?>" /></div>
					<?php endif;?>
					<figure>
					  <?php if(!empty($restaurant['photo'])):?>
					  
					  
					  <?php 
					  $restaurant['photo_method']='c';
					  $restaurant['photo_big']=$restaurant['photo']['path'];
					  if(!empty($restaurant['photo']['crop_dimension'])) {
							$restaurant['photo']['crop_dimension']=json_decode($restaurant['photo']['crop_dimension']); 
							$restaurant['photo']['path']=$data['crop_dimension']->width.'/'.$restaurant['photo']['crop_dimension']->height.'/'.$restaurant['photo']['crop_dimension']->x.'/'.$restaurant['photo']['crop_dimension']->y.'/'.$restaurant['photo']['path'];
							$restaurant['photo_method']='r';
						}	
					?>
					  
					  
					  
						<a href="/image/r/1200/1200/<?=$restaurant['photo_big'];?>" rel="lightbox" data-thumb="/image/c/120/70/<?=$restaurant['photo']['path'];?>" title="<?php if(!empty($restaurant['photo']['caption'])):?><?=$restaurant['photo']['caption'];?><?php else:?><?=$restaurant['name'];?><?php if(!empty($restaurant['categories'])):?> - <?php foreach($restaurant['categories'] as $k=>$category):?><?php if($k>0):?>,<?php endif;?><?=$category['name'];?><?php endforeach;?><?php endif;?><?php if(!empty($restaurant['address'])):?> - <?=$restaurant['city'];?> <?php endif;?> - galeria lokalu<?php endif;?>"><img src="/image/<?=$restaurant['photo_method'];?>/<?php if(!empty($restaurant['awarded'])):?>900/600<?php else:?>650/430<?php endif;?>/<?=$restaurant['photo']['path'];?>" alt="<?php if(!empty($restaurant['photo']['caption'])):?><?=$restaurant['photo']['caption'];?><?php else:?><?=$restaurant['name'];?><?php if(!empty($restaurant['categories'])):?> - <?php foreach($restaurant['categories'] as $k=>$category):?><?php if($k>0):?>,<?php endif;?><?=$category['name'];?><?php endforeach;?><?php endif;?><?php if(!empty($restaurant['address'])):?> - <?=$restaurant['city'];?> <?php endif;?> - galeria lokalu<?php endif;?>" /></a>
					 <?php else: ?>
                        <img src="/image/c/<?php if(!empty($restaurant['awarded'])):?>900/600<?php else:?>650/430<?php endif;?>/flavors/20231213/brak_zdjecia.jpg" alt="<?=esc($restaurant['name']);?>">
                     <?php endif;?>
						<?php if(!empty($restaurant['awarded'])):?><span class="ico_award">LOKAL WYRÓŻNIONY</span><?php endif;?>					 
				   </figure>
				   <?php if(!empty($restaurant['archives'])): ?>
						<div class="archives-info">
							<p class="p1"><?=lang('Flavors.RestaurantArchivesInfo'); ?></p>
							<?php if(!empty($restaurant['categories']) && !empty($restaurant['categories'][0]) && !empty($restaurant['categories'][0]['name']) && !empty($restaurant['categories'][0]['link'])): ?>
								<div><p class="p2"><?=lang('Flavors.RestaurantArchivesOther'); ?> <a href="/<?=$restaurant['categories'][0]['link']; ?>" title=""><?=$restaurant['categories'][0]['name']; ?></a></p></div>
							<?php endif; ?>
						</div>
				   <?php endif; ?>
		   </div>
			<?php if(!empty($restaurant['photos']) and !empty($restaurant['awarded'])):?>
			<div class="photos">
			    <div class="list">
				    <?php foreach($restaurant['photos'] as $k=>$photo): ?>
					  <figure><a href="/image/r/1200/1200/<?=$photo['path'];?>" rel="lightbox" data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?php if(!empty($photo['caption'])):?><?=$photo['caption'];?><?php else:?><?=$restaurant['name'];?> - galeria lokalu<?php endif;?>"><img src="/image/c/300/190/<?=$photo['path'];?>" alt="<?php if(!empty($photo['caption'])):?><?=$photo['caption'];?><?php else:?><?=$restaurant['name'];?> -  galeria lokalu<?php endif;?>" /><span class="show_cnt">+<?=(count($restaurant['photos'])-($k+1)); ?></span></a>
					  </figure>
					<?php endforeach;?>
				</div>
			</div>
			<?php endif;?>
			<div class="single-menu">
			  <ul>
			    <li><a href="javascript:RscrollTo('section.description')">Opis</a></li>
				<?php if(!empty($restaurant['parameters'])):?><li><a href="javascript:RscrollTo('section.parameters')">Parametry</a></li><?php endif;?>
				<?php if(!empty($restaurant['menu'])):?><li><a href="javascript:RscrollTo('section.menu')">Menu</a></li><?php endif;?>
				<?php if(!empty($restaurant['news_list'])):?><li><a href="javascript:RscrollTo('section.news')">Aktualności</a></li><?php endif;?>
				<li> <?php if(!empty($restaurant['user_rates'])):?> <a href="javascript:RscrollTo('section.user_rates')">Oceny</a><?php endif;?></li>
				<li><?php if(!empty($restaurant['comments'])):?><a href="javascript:RscrollTo('section.comments')">Opinie</a><?php endif;?></li>
			  </ul>
			</div>
			<?php if(!empty($restaurant['description'])):?>
				<section class="description">
					<?=$restaurant['description'];?>
				</section> 
			<?php endif;?>

<?php if(!empty($restaurant['parameters'])):?>		
	<section class="parameters">
        <h4><?=lang('Flavors.RestaurantInformation');?></h4>
		   
		       	<?php if(!empty($mobile)):?>  
	        <div class="parameters">
                 <?php if(!empty($restaurant['cuisine_type'])):?>
                  <div class="param cuisine_type">
				     <figure><svg viewBox="0 0 69.24 58.69"><defs><style>.cls-1ct{stroke:#fff;stroke-width:6px;}.cls-1ct,.cls-2ct{fill:none;stroke-linecap:round;stroke-linejoin:round;}.cls-2ct{stroke:#1d1d1b;stroke-width:2px;}</style></defs><g id="icons"><g><path class="cls-2ct" d="M43.46,24.47c-2.13,2.81-5.35,4.42-8.84,4.42m27.94-7.1c0,2.96-2.41,5.37-5.37,5.37"/><path class="cls-2ct" d="M57.19,10.73c-2.44,0-4.68,.82-6.51,2.16-2.09-6.88-8.49-11.89-16.06-11.89s-13.96,5.01-16.06,11.89c-1.83-1.34-4.07-2.16-6.51-2.16C5.95,10.73,1,15.68,1,21.78s4.95,11.05,11.05,11.05c3.84,0,7.21-1.96,9.2-4.93,3.07,4.05,7.91,6.67,13.37,6.67s10.31-2.62,13.37-6.67c1.98,2.97,5.36,4.93,9.2,4.93,6.1,0,11.05-4.95,11.05-11.05s-4.95-11.05-11.05-11.05Z"/><line class="cls-2ct" x1="48.9" y1="50.59" x2="48.9" y2="32.25"/><line class="cls-2ct" x1="41.79" y1="50.59" x2="41.79" y2="32.25"/><line class="cls-1ct" x1="22.71" y1="50.59" x2="54.82" y2="50.59"/><line class="cls-2ct" x1="20.34" y1="50.59" x2="54.82" y2="50.59"/><path class="cls-1ct" d="M50.09,24.32c-.85,2.01-2.08,3.82-3.6,5.34-1.52,1.52-3.33,2.75-5.34,3.6-2.01,.85-4.22,1.32-6.53,1.32s-4.53-.47-6.53-1.32c-2.01-.85-3.82-2.08-5.34-3.6-1.52-1.52-2.75-3.33-3.6-5.34"/><path class="cls-2ct" d="M50.09,24.32c-.85,2.01-2.08,3.82-3.6,5.34-1.52,1.52-3.33,2.75-5.34,3.6-2.01,.85-4.22,1.32-6.53,1.32s-4.53-.47-6.53-1.32c-2.01-.85-3.82-2.08-5.34-3.6-1.52-1.52-2.75-3.33-3.6-5.34"/><polyline class="cls-2ct" points="14.42 37.39 14.42 57.69 54.82 57.69 54.82 32.65"/></g></g></svg></figure>
					 <div>
					  <label>Rodzaj kuchni:</label>
					 <?php foreach($restaurant['cuisine_type'] as $k=>$cuisine):?><span <?php if($k>3):?>class="hide"<?php endif;?>><a href="/<?=$cuisine['link'];?>" class="cuisine"><?=$cuisine['name'];?></a></span><?php endforeach;?>
					 <?php if(count($restaurant['cuisine_type'])>3):?><span class="show_other_cuisine trans400">Inne +</span><?php endif;?>
					</div>
				  </div>
                 <?php endif;?>  		
	  <?php if(!empty($restaurant['dish_type'])):?>  
	         <div class="param dish_type">
					<figure><svg viewBox="0 0 24 24"><path d="M13,7.06V5H11V7.06A9,9,0,0,0,3.06,15H2v2H22V15H20.94A9,9,0,0,0,13,7.06Z"/></svg></figure>
					<div>
					  <label>Dania:</label>
					  <?php if(in_array(1,$restaurant['dish_type'])):?><span>Mięsne</span><?php endif;?> <?php if(in_array(2,$restaurant['dish_type'])):?><span>Wegańskie</span><?php endif;?> <?php if(in_array(3,$restaurant['dish_type'])):?><span>Wegetariańskie</span><?php endif;?>
					</div>
	         </div>
	  <?php endif;?>
	 <?php if(!empty($restaurant['address'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 82 110"><defs><style>.cls-1adr{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><path class="cls-1adr" d="M70.52,36.12C70.52,15.86,53.02-.31,32.31,1.91,17.11,3.54,4.51,15.53,2.14,30.63c-1.31,8.33,.43,16.16,4.2,22.68h-.02s24.17,41.87,24.17,41.87c2.5,4.34,8.76,4.34,11.27,0l24.17-41.86h-.02c2.93-5.08,4.63-10.94,4.63-17.2Z"/><path class="cls-1adr" d="M52.9,36.12c0,9.27-7.52,16.79-16.79,16.79s-16.79-7.52-16.79-16.79,7.52-16.79,16.79-16.79,16.79,7.52,16.79,16.79Z"/></g></g></svg></figure>
					<div>
					<label>Adres:</label>
					<span><?=$restaurant['address'];?>, <?=$restaurant['city'];?></span>
					</div>
	         </div>
	 <?php endif;?> 
	 
	  <?php if(!empty($restaurant['phone'])):?>
	    <div class="param">
		       <figure><svg viewBox="0 0 94.69 94.68"><defs><style>.cls-1rp{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><g><path class="cls-1rp" d="M45.47,25.37c13.17,0,23.85,10.68,23.85,23.85"/><path class="cls-1rp" d="M45.47,13.54c19.7,0,35.67,15.97,35.67,35.67"/><path class="cls-1rp" d="M45.47,1.71c26.24,0,47.51,21.27,47.51,47.51"/></g><path class="cls-1rp" d="M7.32,14.74h0c-11.56,11.56-4.68,37.19,15.38,57.25,20.05,20.05,45.68,26.94,57.24,15.38,3.5-3.51,5.1-11.46,5.1-11.46,.26-1.31-.5-2.77-1.69-3.25l-20.99-8.39c-1.19-.48-2.96-.08-3.93,.89l-6.08,6.08c-1.16,1.16-2.84,1.45-4.16,.85-.11-.05-.21-.11-.31-.16-.04-.02-.09-.05-.13-.08-4.62-2.52-9.51-6.19-14.12-10.79-4.6-4.6-8.27-9.49-10.79-14.11-.02-.04-.05-.08-.07-.12-.06-.11-.11-.21-.17-.32-.6-1.33-.31-3,.85-4.16l6.09-6.09c.97-.96,1.37-2.73,.89-3.93L22.04,11.34c-.48-1.19-1.94-1.96-3.25-1.7,0,0-7.96,1.59-11.46,5.09Z"/></g></g></svg></figure>
		       <div class="col">
		          <div>
				    <label>Telefon:</label>
					<span><a href="tel:<?=str_replace(' ','',$restaurant['phone']);?>"><?=$restaurant['phone'];?></a></span>
				  </div>
				  <?php if(!empty($restaurant['reservation'])):?>
				  <div>
				    <label>Rezerwacje:</label>
					<span><a href="tel:<?=str_replace(' ','',$restaurant['reservation']);?>"><?=$restaurant['reservation'];?></a></span>
				  </div>
				 <?php endif;?>
		       </div> 
	    </div> 
	 <?php endif;?> 
	 
	 <?php if(!empty($restaurant['working_hours'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 100.13 100.13"><defs><style>.cls-1wh{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><circle class="cls-1wh" cx="50.06" cy="50.06" r="48.36"/><g><path class="cls-1wh" d="M88.84,50.06c0,21.41-17.36,38.78-38.78,38.78S11.29,71.48,11.29,50.06,28.65,11.28,50.06,11.28s38.78,17.36,38.78,38.78Z"/><g><line class="cls-1wh" x1="22.64" y1="77.48" x2="27.69" y2="72.43"/><line class="cls-1wh" x1="50.06" y1="11.28" x2="50.06" y2="18.42"/><line class="cls-1wh" x1="22.65" y1="22.64" x2="27.69" y2="27.69"/><line class="cls-1wh" x1="77.48" y1="77.49" x2="72.44" y2="72.43"/><line class="cls-1wh" x1="88.85" y1="50.07" x2="81.71" y2="50.06"/><line class="cls-1wh" x1="77.49" y1="22.65" x2="72.43" y2="27.69"/><line class="cls-1wh" x1="50.06" y1="88.84" x2="50.06" y2="81.7"/><line class="cls-1wh" x1="11.28" y1="50.06" x2="18.43" y2="50.06"/></g></g><line class="cls-1wh" x1="50.06" y1="26.71" x2="50.06" y2="50.06"/><line class="cls-1wh" x1="50.06" y1="50.06" x2="61.43" y2="61.43"/></g></g></svg></figure>
					<div>
					<label>Godziny otwarcia:</label>
					<span><?=$restaurant['working_hours']; ?></span>
					</div>
	         </div>
	 <?php endif;?> 
	 
	 	 <?php if(!empty($restaurant['www']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 100.14 100.14"><defs><style>.cls-1www{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><path class="cls-1www" d="M65.93,65.93c2.5-5.99,2.5-12.77,0-18.76-1.18-2.82-2.91-5.47-5.2-7.76s-4.94-4.02-7.76-5.2l-2.9,2.9c-3.57,3.57-3.57,9.39,0,12.96,3.57,3.57,3.57,9.39,0,12.96l-2.9,2.9-14.75,14.75c-3.57,3.57-9.39,3.57-12.96,0-3.57-3.57-3.57-9.39,0-12.96l14.75-14.75c-2.5-5.99-2.5-12.77,0-18.76-2.82,1.18-5.47,2.91-7.76,5.2L8.8,57.05c-9.45,9.45-9.45,24.83,0,34.29,9.45,9.45,24.83,9.45,34.29,0l17.65-17.65c2.29-2.29,4.02-4.94,5.2-7.76Z"/><path class="cls-1www" d="M91.34,8.8c-9.45-9.45-24.83-9.45-34.29,0l-17.65,17.65c-2.29,2.29-4.02,4.94-5.2,7.76-2.5,5.99-2.5,12.77,0,18.76,1.18,2.82,2.91,5.47,5.2,7.76,2.29,2.29,4.94,4.02,7.76,5.2l2.9-2.9c3.57-3.57,3.57-9.39,0-12.96-3.57-3.57-3.57-9.39,0-12.96l2.9-2.9,14.75-14.75c3.57-3.57,9.39-3.57,12.96,0,3.57,3.57,3.57,9.39,0,12.96l-14.75,14.75c2.5,5.99,2.5,12.77,0,18.76,2.82-1.18,5.47-2.91,7.76-5.2l17.65-17.65c9.45-9.45,9.45-24.83,0-34.29Z"/></g></g></svg></figure>
					<div>
					<label>Strona internetowa:</label>
					<span><a href="<?=$restaurant['www'];?>" rel="nofollow" target="_blank"><?=str_replace(array('https://','http://'), "", $restaurant['www']); ?></a></span>
					</div>
	         </div>
	 <?php endif;?> 
	  	 <?php if(!empty($restaurant['social_link']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure>
					<svg viewBox="0 0 102.56 121.11"><defs><style>.cls-1sl{fill:none;stroke:#3c3c3b;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}.cls-2sl{fill:#fff;}</style></defs><g id="Graphics"><g><g><g><path class="cls-2sl" d="M.29,34.39s0,.07,0,.1l1.87,25.49c.31,3.52,4.35,6.51,8.18,7.34l2.43,32.84c.15,2.06,1.92,3.74,3.95,3.74h13.53c2.03,0,3.8-1.68,3.95-3.74l2.43-32.83c3.83-.83,7.87-3.8,8.18-7.3l1.87-25.08c.02-.31,.01-.5,0-.61,0-5.68-4.55-10.47-10.15-10.61H10.5c-5.65,.14-10.21,4.93-10.21,10.67Z"/><path class="cls-2sl" d="M33.19,10C33.19,4.64,28.84,.29,23.49,.29S13.78,4.64,13.78,10s4.35,9.71,9.71,9.71,9.71-4.35,9.71-9.71Z"/></g><g><path class="cls-1sl" d="M1.71,34.39l1.87,25.49c.26,3.03,4.37,5.68,7.66,6.16,.15,.02,.29,.04,.43,.05l2.51,33.96c.1,1.34,1.24,2.43,2.54,2.43h13.53c1.3,0,2.44-1.09,2.54-2.43l2.51-33.96c.14-.01,.28-.03,.43-.05,3.29-.49,7.4-3.13,7.66-6.15l1.87-25.06c.01-.21,0-.35,0-.43,0-5-3.93-9.15-8.77-9.27H10.5c-4.86,.12-8.79,4.27-8.79,9.26Z"/><path class="cls-1sl" d="M23.49,18.29c4.57,0,8.29-3.72,8.29-8.29S28.06,1.71,23.49,1.71s-8.29,3.72-8.29,8.29,3.72,8.29,8.29,8.29Z"/><line class="cls-1sl" x1="23.49" y1="63.71" x2="23.49" y2="101.41"/><line class="cls-1sl" x1="35.3" y1="66.1" x2="37.4" y2="37.78"/><line class="cls-1sl" x1="11.67" y1="66.1" x2="9.58" y2="37.78"/></g></g><g><g><path class="cls-2sl" d="M92.06,23.72h-26.04c-5.6,.14-10.15,4.93-10.15,10.61,0,.11-.01,.3,0,.61l1.87,25.08c.31,3.5,4.35,6.47,8.18,7.3l2.43,32.83c.15,2.06,1.92,3.74,3.95,3.74h13.53c2.03,0,3.8-1.68,3.95-3.74l2.43-32.84c3.83-.83,7.87-3.81,8.18-7.34l1.87-25.49s0-.07,0-.1c0-5.75-4.56-10.54-10.21-10.67Z"/><path class="cls-2sl" d="M79.07,19.7c5.35,0,9.71-4.35,9.71-9.71S84.42,.29,79.07,.29s-9.71,4.35-9.71,9.71,4.35,9.71,9.71,9.71Z"/></g><g><path class="cls-1sl" d="M100.85,34.39l-1.87,25.49c-.26,3.03-4.37,5.68-7.66,6.16-.15,.02-.29,.04-.43,.05l-2.51,33.96c-.1,1.34-1.24,2.43-2.54,2.43h-13.53c-1.3,0-2.44-1.09-2.54-2.43l-2.51-33.96c-.14-.01-.28-.03-.43-.05-3.29-.49-7.39-3.13-7.66-6.15l-1.87-25.06c-.01-.21,0-.35,0-.43,0-5,3.93-9.15,8.77-9.27h26c4.86,.12,8.79,4.27,8.79,9.26Z"/><path class="cls-1sl" d="M79.07,18.29c-4.57,0-8.29-3.72-8.29-8.29s3.72-8.29,8.29-8.29,8.29,3.72,8.29,8.29-3.72,8.29-8.29,8.29Z"/><line class="cls-1sl" x1="79.06" y1="63.71" x2="79.06" y2="101.41"/><line class="cls-1sl" x1="67.26" y1="66.1" x2="65.16" y2="37.78"/><line class="cls-1sl" x1="90.89" y1="66.1" x2="92.98" y2="37.78"/></g></g><g><g><g><path class="cls-2sl" d="M44.52,119.4c-2.9,0-5.44-2.39-5.65-5.32l-2.34-31.63c-3.94-1.26-7.9-4.43-8.26-8.48l-1.87-25.1c-.02-.43-.02-.68,0-.82,0-6.55,5.3-12.07,11.81-12.23h26.08c6.59,.16,11.92,5.71,11.92,12.38,0,.08,0,.15,0,.23l-1.87,25.49c-.36,4.09-4.32,7.28-8.27,8.54l-2.34,31.63c-.21,2.93-2.75,5.32-5.65,5.32h-13.53Z"/><path class="cls-2sl" d="M64.27,37.52c5.65,.14,10.21,4.93,10.21,10.67,0,.04,0,.07,0,.1l-1.87,25.49c-.31,3.52-4.35,6.51-8.18,7.34l-2.43,32.84c-.15,2.06-1.92,3.74-3.95,3.74h-13.53c-2.03,0-3.8-1.68-3.95-3.74l-2.43-32.83c-3.83-.83-7.87-3.8-8.18-7.3l-1.87-25.08c-.02-.31-.01-.5,0-.61,0-5.68,4.55-10.47,10.15-10.61h26.04m.04-3.41h-26.16c-7.41,.18-13.44,6.43-13.48,13.95-.01,.27,0,.55,.01,.87v.03s0,.03,0,.03l1.87,25.08c.4,4.5,4.18,7.96,8.35,9.61l2.26,30.52c.28,3.87,3.51,6.9,7.35,6.9h13.53c3.84,0,7.07-3.03,7.35-6.91l2.26-30.52c4.17-1.66,7.96-5.13,8.35-9.61l1.87-25.53c0-.09,.01-.21,.01-.35,0-7.58-6.07-13.9-13.54-14.08h-.04Z"/></g><g><path class="cls-2sl" d="M51.28,35.21c-6.29,0-11.41-5.12-11.41-11.41s5.12-11.41,11.41-11.41,11.41,5.12,11.41,11.41-5.12,11.41-11.41,11.41Z"/><path class="cls-2sl" d="M51.28,14.09c5.35,0,9.71,4.35,9.71,9.71s-4.35,9.71-9.71,9.71-9.71-4.35-9.71-9.71,4.35-9.71,9.71-9.71m0-3.41c-7.23,0-13.12,5.89-13.12,13.12s5.89,13.12,13.12,13.12,13.12-5.89,13.12-13.12-5.88-13.12-13.12-13.12h0Z"/></g></g><g><path class="cls-1sl" d="M51.28,32.09c-4.57,0-8.29-3.72-8.29-8.29s3.72-8.29,8.29-8.29,8.29,3.72,8.29,8.29-3.72,8.29-8.29,8.29Z"/><line class="cls-1sl" x1="51.28" y1="77.51" x2="51.28" y2="115.21"/><line class="cls-1sl" x1="39.47" y1="79.9" x2="37.37" y2="51.58"/><line class="cls-1sl" x1="63.1" y1="79.9" x2="65.19" y2="51.58"/></g></g><path class="cls-1sl" d="M73.07,48.19l-1.87,25.49c-.26,3.03-4.37,5.68-7.66,6.16-.15,.02-.29,.04-.43,.05l-2.51,33.96c-.1,1.34-1.24,2.43-2.54,2.43h-13.53c-1.3,0-2.44-1.09-2.54-2.43l-2.51-33.96c-.14-.01-.28-.03-.43-.05-3.29-.49-7.39-3.13-7.66-6.15l-1.87-25.06c-.01-.21,0-.35,0-.43,0-5,3.93-9.15,8.77-9.27h26c4.86,.12,8.79,4.27,8.79,9.26Z"/></g></g></svg>
					</figure>
					<div>
					<label>Media społecznościowe:</label>
					<span><a href="<?=$restaurant['social_link'];?>" rel="nofollow" target="_blank"><?=str_replace(array('https://','http://'), "", $restaurant['social_link']); ?></a></span>
					</div>
	         </div>
	 <?php endif;?> 
	 	 <?php if(!empty($restaurant['email']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 100.14 70.85"><defs><style>.cls-1em{fill:#fff;}.cls-2em{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><rect class="cls-2em" x="1.71" y="1.71" width="96.72" height="67.44" rx="3.87" ry="3.87"/><g><path class="cls-1em" d="M5.58,69.14c-2.13,0-2.61-1.2-1.07-2.68L47.27,25.5c1.54-1.47,4.05-1.47,5.59,0l42.77,40.97c1.54,1.47,1.05,2.68-1.07,2.68H5.58Z"/><path class="cls-2em" d="M5.58,69.14c-2.13,0-2.61-1.2-1.07-2.68L47.27,25.5c1.54-1.47,4.05-1.47,5.59,0l42.77,40.97c1.54,1.47,1.05,2.68-1.07,2.68H5.58Z"/></g><g><path class="cls-1em" d="M94.56,1.71c2.13,0,2.61,1.2,1.07,2.68L52.86,45.35c-1.54,1.47-4.05,1.47-5.59,0L4.5,4.38c-1.54-1.47-1.05-2.68,1.07-2.68H94.56Z"/><path class="cls-2em" d="M94.56,1.71c2.13,0,2.61,1.2,1.07,2.68L52.86,45.35c-1.54,1.47-4.05,1.47-5.59,0L4.5,4.38c-1.54-1.47-1.05-2.68,1.07-2.68H94.56Z"/></g></g></g></svg></figure>
					<div>
					<label>E-mail:</label>
					<span><a href="mailto:<?=$restaurant['email'];?>" rel="nofollow" target="_blank"><?=$restaurant['email']; ?></a></span>
					</div>
	         </div>
	 <?php endif;?> 
	 <?php if(!empty($restaurant['chef']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 57.48 70.43"><defs><style>.cls-1chef{stroke:#fff;stroke-width:6px;}.cls-1chef,.cls-2chef,.cls-3chef,.cls-4chef{stroke-linecap:round;}.cls-1chef,.cls-2chef,.cls-4chef{fill:none;}.cls-1chef,.cls-3chef,.cls-4chef{stroke-linejoin:round;}.cls-2chef{stroke-miterlimit:10;}.cls-2chef,.cls-3chef,.cls-4chef{stroke:#1d1d1b;stroke-width:2px;}.cls-3chef{fill:#fff;}</style></defs><g id="icons"><g><g><path class="cls-2chef" d="M55.81,69.43l.65-11.03c.22-3.19-1.83-5.89-5.34-6.99l-14.67-4.29"/><path class="cls-2chef" d="M1.67,69.43l-.65-11.03c-.22-3.19,1.83-5.89,5.34-6.99l14.67-4.29"/><line class="cls-2chef" x1="22.11" y1="42.44" x2="21.03" y2="47.12"/><line class="cls-2chef" x1="35.37" y1="42.44" x2="36.45" y2="47.12"/><path class="cls-3chef" d="M17.58,27.93c-1.9,.11-3.42,1.67-3.42,3.6s1.62,3.62,3.62,3.62c.2,0,.39-.03,.58-.06h0c1.64,6.65,5.66,11.35,10.37,11.35s8.73-4.71,10.37-11.35c.19,.03,.38,.06,.58,.06,2,0,3.62-1.62,3.62-3.62s-1.51-3.49-3.42-3.6"/></g><path class="cls-4chef" d="M36.45,47.12c0,2.57-1.66,7.86-7.71,7.86s-7.71-5.29-7.71-7.86"/><line class="cls-1chef" x1="28.74" y1="54.98" x2="15.1" y2="54.98"/><polyline class="cls-4chef" points="28.74 54.98 15.1 54.98 15.1 63.58"/><line class="cls-2chef" x1="15.1" y1="59.23" x2="15.1" y2="69.43"/><line class="cls-4chef" x1="22.48" y1="61.02" x2="22.48" y2="61.02"/><line class="cls-4chef" x1="22.48" y1="67.71" x2="22.48" y2="67.71"/><line class="cls-4chef" x1="34.83" y1="61.02" x2="34.83" y2="61.02"/><line class="cls-4chef" x1="34.83" y1="67.71" x2="34.83" y2="67.71"/><polyline class="cls-4chef" points="17.2 21.17 17.2 27.93 39.9 27.93 39.9 21.17"/><g><path class="cls-4chef" d="M38.25,20.93c4.23,0,7.65-3.43,7.65-7.65s-3.43-7.65-7.65-7.65c-.87,0-1.71,.15-2.49,.42-1.06-2.94-3.88-5.05-7.19-5.05s-6.13,2.11-7.19,5.07c-.79-.28-1.64-.44-2.53-.44-4.23,0-7.65,3.43-7.65,7.65s3.43,7.65,7.65,7.65"/><path class="cls-4chef" d="M38.25,20.93c-2.11,0-4.03-.86-5.41-2.24"/><path class="cls-4chef" d="M18.85,20.93c2.11,0,4.03-.86,5.41-2.24"/><path class="cls-4chef" d="M33.98,3.24c.69,.69,1.25,1.52,1.64,2.43,.39,.92,.6,1.92,.6,2.98s-.21,2.06-.6,2.98"/></g><path class="cls-4chef" d="M36.45,47.12c0,2.57-1.66,7.86-7.71,7.86"/></g></g></svg></figure>
					<div>
					<label>Szef kuchni:</label>
					<span><?=$restaurant['chef']; ?></span>
					</div>
	         </div>
	 <?php endif;?> 
	 	 <?php if(!empty($restaurant['speciality']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 70.2 74"><defs><style>.cls-1sp{stroke:#fff;stroke-width:6px;}.cls-1sp,.cls-2sp,.cls-3sp,.cls-4sp{stroke-linejoin:round;}.cls-1sp,.cls-3sp{fill:#fff;}.cls-1sp,.cls-3sp,.cls-4sp{stroke-linecap:round;}.cls-2sp,.cls-3sp,.cls-4sp{stroke:#1d1d1b;stroke-width:2px;}.cls-2sp,.cls-4sp{fill:none;}.cls-5sp{fill:#1d1d1b;}</style></defs><g id="icons"><g><g><line class="cls-4sp" x1="2" y1="37.19" x2="69.2" y2="37.19"/><line class="cls-4sp" x1="9.32" y1="41.99" x2="61.88" y2="41.99"/><g><path class="cls-2sp" d="M52.8,33.73c0-4.74-1.93-9.04-5.04-12.16"/><path class="cls-4sp" d="M47.76,21.57c-3.12-3.12-7.42-5.04-12.16-5.04"/><path class="cls-2sp" d="M13.32,33.73c0-12.3,9.97-22.28,22.28-22.28s22.28,9.98,22.28,22.28"/></g><path class="cls-2sp" d="M39.09,4.49c0,1.93-1.56,3.49-3.49,3.49s-3.49-1.56-3.49-3.49,1.56-3.49,3.49-3.49,3.49,1.56,3.49,3.49Z"/></g><g><path class="cls-4sp" d="M41.82,52.33s7.78-3.26,11.85-4.98c3.6-1.52,5.95-.46,7.62,1.6,.51,.63,.32,1.57-.38,1.97l-18.53,10.54c-.34,.2-.73,.3-1.13,.3H19.29"/><path class="cls-4sp" d="M10.32,54.14c4.35-5.46,8.18-7.54,12.47-7.54,3.6,0,5.14,.42,8.21,2.48h5.52c1.43,0,4.96,.38,5.51,4.22,.1,.73-.48,1.39-1.22,1.39h-12.54"/><polyline class="cls-1sp" points="3 57.4 7.04 53.36 20.63 66.96 16.59 71"/><polyline class="cls-3sp" points="3 57.4 7.04 53.36 20.63 66.96 16.59 71"/><path class="cls-5sp" d="M6.39,60.63c-.53,.53-.53,1.39,0,1.92,.53,.53,1.39,.53,1.92,0,.53-.53,.53-1.39,0-1.92-.53-.53-1.39-.53-1.92,0Z"/></g></g></g></svg></figure>
					<div>
					<label><?=lang('Flavors.RestaurantSpeciality');?>:</label>
					<span><?=$restaurant['speciality']; ?></span>
					</div>
	         </div>
	 <?php endif;?>
	  	 <?php if(!empty($restaurant['opening_year']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 113.22 99.21"><defs><style>.cls-1op{fill:none;}.cls-1op,.cls-2op{stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}.cls-2op{fill:#fff;}</style></defs><g id="Graphics"><g><rect class="cls-1op" x="1.71" y="12.54" width="109.81" height="84.96" rx="7.74" ry="7.74"/><g><path class="cls-2op" d="M87.93,23.38c-2.83,0-5.12-2.29-5.12-5.12V6.82c0-2.83,2.29-5.12,5.12-5.12s5.12,2.29,5.12,5.12v11.43c0,2.83-2.29,5.12-5.12,5.12Z"/><path class="cls-2op" d="M25.29,23.38c-2.83,0-5.12-2.29-5.12-5.12V6.82c0-2.83,2.29-5.12,5.12-5.12s5.12,2.29,5.12,5.12v11.43c0,2.83-2.29,5.12-5.12,5.12Z"/></g><line class="cls-2op" x1="1.71" y1="34.15" x2="111.51" y2="34.15"/><g><polyline class="cls-2op" points="87.07 59.41 87.07 46.99 99.49 46.99"/><polyline class="cls-2op" points="62.62 59.41 62.62 46.99 75.04 46.99"/><polyline class="cls-2op" points="38.18 59.41 38.18 46.99 50.6 46.99"/><polyline class="cls-2op" points="13.73 59.41 13.73 46.99 26.15 46.99"/></g><g><polyline class="cls-2op" points="87.07 84.66 87.07 72.24 99.49 72.24"/><polyline class="cls-2op" points="62.62 84.66 62.62 72.24 75.04 72.24"/><polyline class="cls-2op" points="38.18 84.66 38.18 72.24 50.6 72.24"/><polyline class="cls-2op" points="13.73 84.66 13.73 72.24 26.15 72.24"/></g></g></g></svg></figure>
					<div>
					<label><?=lang('Flavors.RestaurantOpeningYear');?>:</label>
					<span><?=$restaurant['opening_year']; ?></span>
					</div>
	         </div>
	 <?php endif;?>
	   	 <?php if(!empty($restaurant['table_numbers']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 64.36 71.57"><defs><style>.cls-1tn{fill:none;stroke:#1d1d1b;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><g id="icons"><g><g><path class="cls-1tn" d="M49.84,33.18H14.52c-.62,0-1.12,.5-1.12,1.12v7.66c3.13,0,3.13-3.93,6.26-3.93s3.13,3.93,6.26,3.93,3.13-3.93,6.26-3.93,3.13,3.93,6.26,3.93,3.13-3.93,6.26-3.93,3.13,3.93,6.26,3.93v-7.66c0-.62-.5-1.12-1.12-1.12Z"/><g><path class="cls-1tn" d="M45.1,70.57v-17.66c0-2.81,2.28-5.09,5.08-5.09h13.18"/><line class="cls-1tn" x1="45.1" y1="53.97" x2="63.36" y2="53.97"/><line class="cls-1tn" x1="63.36" y1="25.56" x2="63.36" y2="70.57"/></g><g><path class="cls-1tn" d="M19.26,70.57v-17.66c0-2.81-2.28-5.09-5.08-5.09H1"/><line class="cls-1tn" x1="19.26" y1="53.97" x2="1" y2="53.97"/><line class="cls-1tn" x1="1" y1="25.56" x2="1" y2="70.57"/></g><line class="cls-1tn" x1="24.62" y1="70.57" x2="39.74" y2="70.57"/><line class="cls-1tn" x1="32.18" y1="70.57" x2="32.18" y2="41.93"/></g><path class="cls-1tn" d="M19.45,16.64c.7-1.65,1.71-3.14,2.96-4.39s2.74-2.26,4.39-2.96c1.65-.7,3.47-1.09,5.38-1.09s3.73,.39,5.38,1.09c1.65,.7,3.14,1.71,4.39,2.96,1.25,1.25,2.26,2.74,2.96,4.39H19.45Z"/><line class="cls-1tn" x1="32.18" y1="1" x2="32.18" y2="8.2"/></g></g></svg></figure>
					<div>
					<label><?=lang('Flavors.RestaurantTableNumbers');?>:</label>
					<span><?=$restaurant['table_numbers']; ?></span>
					</div>
	         </div>
	 <?php endif;?>
	 
	 </div>
	 <?php endif;?>
		   
		   
		   
		   
		   <div class="list-parameters">
			  <?php foreach($restaurant['parameters'] as $param):?>
			  <dl>
				<dt><?=$param['name'];?>:</dt>
				<?php if(!empty($param['values'])):?>
				<dd <?php if(count($param['values'])<4):?>class="line"<?php endif;?>>
				   <?php foreach($param['values'] as $val):?>
					 <span><?=$val['value'];?></span>
				   <?php endforeach;?>
				</dd>
				<?php endif;?>
			   </dl>
			  <?php endforeach;?>
		   </div>
	 </section>
	<?php endif;?>		

	<?php if(!empty($restaurant['menu'])):?>			
		<section class="menu">	
			<h4><?=lang('Flavors.RestaurantMenu');?></h4>
		    <div class="menu-list">
			 <?php foreach($restaurant['menu'] as $k=>$menu):?>
			   <figure><a href="/image/r/1200/1200/<?=$menu['path'];?>" rel="menu" data-thumb="/image/c/120/120/<?=$menu['path'];?>" title="<?php if(!empty($menu['caption'])):?><?=$menu['caption'];?><?php else:?><?=lang('Flavors.RestaurantMenu');?> - <?=lang('Flavors.Photo');?> <?=($k+1);?><?php endif;?>"><img src="/image/r/260/360/<?=$menu['path'];?>" alt="<?php if(!empty($menu['caption'])):?><?=$menu['caption'];?><?php else:?><?=lang('Flavors.RestaurantMenu');?> - <?=lang('Flavors.Photo');?> <?=($k+1);?><?php endif;?>" /></a></figure>
			 <?php endforeach;?>
			</div> 
		</section>	
	<?php endif;?>
	
	<?php if(!empty($mobile)):?>
		<?php if(!empty($restaurant['coordinates_array'])):?>
	  <div class="map">
	     <h4>Lokalizacja</h4>
	     <div id="map" data-marker="/<?=$restaurant['link'];?>" data-lat="<?=$restaurant['coordinates_array'][0];?>" data-long="<?=$restaurant['coordinates_array'][1];?>"></div>
	 </div>
	<?php endif;?> 
	
	<?php endif;?>
	
   <?php if(!empty($restaurant['news_list'])):?>
       <section class="news">
	       <h4>Aktualności</h4>
	    <div class="list">
                <?php foreach($restaurant['news_list'] as $news): ?>
                <div class="news-item news-item-<?=$news['id']; ?>">
                    <div class="photo">
                        <?php if($news['photo']): ?>
                            <a href="/<?=$news['link']; ?>" title="<?=esc($news['title']); ?>">
                                <picture>
                                    <source srcset="/image/c/330/220/<?=$news['photo']['path']; ?>" media="(max-width: 800px)">
                                    <img src="/image/c/480/320/<?=$news['photo']['path']; ?>" alt="<?=esc($news['title']); ?>" class="trans400" />
                                </picture>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="info">
                        <h2><a href="/<?=$news['link']; ?>" title="<?=esc($news['title']); ?>"><?=$news['title']; ?></a></h2>
						<?php if(!empty($news['introduction'])):?>
					      <div class="introduction"><?=$news['introduction'];?></div>
						<?php endif;?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
	
       </section>
   <?php endif;?> 
 <?php if(!empty($restaurant['user_rates'])):?> 
<section class="user_rates">
  <h4><?=lang('Flavors.ThisRestaurantRates');?></h4>
  <div class="list">
    <?php 
	foreach($restaurant['user_rates'] as $id_rate=>$rate):?>
	  <div class="rate-item">
		<figure><img src="/assets/gfx/user_graph.jpg" /></figure>
	    <div class="rating">
			    <h5><?php if(!empty($rate['user_info']['nick'])):?><?=$rate['user_info']['nick'];?><?php elseif(!empty($rate['user_info']['name']) and !empty($rate['user_info']['surname'])):?><?=$rate['user_info']['name'];?> <?=$rate['user_info']['surname'];?><?php endif;?></h5> 
				<div id="rate_lokal_<?=$id_rate;?>" class="rating"></div>
				<?php if(!empty($rate['main'])):?><div class="rate"><span><?=number_format($rate['main'], 1, ',', '');?></span>/5</div><?php endif;?>
		</div>
            <div class="rate_box rating trans800">
                <div class="inside">
                 <?php if(!empty($rate[1])):?>  
				   <div class="row"> 
						<div class="label"><?=lang('Flavors.RatingType_1');?>:</div> 
					    <div class="rate_lokal" data-rate="<?=number_format($rate[1], 1, '.', '');?>"></div>
					    <div><span class="label2"><strong><span><?=number_format($rate[1], 1, ',', '');?></span>/5</strong></span></div>
					</div>
				  <?php endif;?>
				  <?php if(!empty($rate[2])):?>  
				   <div class="row"> 
						<div class="label"><?=lang('Flavors.RatingType_2');?>:</div> 
					    <div class="rate_lokal" data-rate="<?=number_format($rate[2], 1, '.', '');?>"></div>
					    <div><span class="label2"><strong><span><?=number_format($rate[2], 1, ',', '');?></span>/5</strong></span></div>
					</div>
				  <?php endif;?>
                  <?php if(!empty($rate[3])):?>  
				   <div class="row"> 
						<div class="label"><?=lang('Flavors.RatingType_3');?>:</div> 
					    <div class="rate_lokal" data-rate="<?=number_format($rate[3], 1, '.', '');?>"></div>
					    <div><span class="label2"><strong><span><?=number_format($rate[3], 1, ',', '');?></span>/5</strong></span></div>
					</div>
				  <?php endif;?>
				  <?php if(!empty($rate[4])):?>  
				   <div class="row"> 
						<div class="label"><?=lang('Flavors.RatingType_4');?>:</div> 
					    <div class="rate_lokal" data-rate="<?=number_format($rate[4], 1, '.', '');?>"></div>
					    <div><span class="label2"><strong><span><?=number_format($rate[4], 1, ',', '');?></span>/5</strong></span></div>
					</div>
				  <?php endif;?>
                </div>
            </div>
	  </div>
	<?php endforeach; ?>
  </div>
</section>
<?php endif;?>

	  <section class="comments">
	     <div class="title"><h4><?=lang('Flavors.RestaurantComments');?> <?=$restaurant['name'];?>:</h4>
	     <div class="rate_btn">
			     <a href="/<?=$restaurant['link'];?>" onclick="RateRestaurant(this,<?=$restaurant['id'];?>);return false;">Dodaj opinię <svg viewBox="0 0 48 48"><path d="M0 0h48v48H0z" fill="none"></path><path d="M24 4C12.95 4 4 12.95 4 24s8.95 20 20 20 20-8.95 20-20S35.05 4 24 4zm10 22h-8v8h-4v-8h-8v-4h8v-8h4v8h8v4z"></path></svg></a>
			   </div>
	     </div>		   
	  <div class="comments_list">
	  <?php if(!empty($restaurant['comments'])):?>
	   <?php 
	   $z=0;
	   foreach($restaurant['comments'] as $comment): ?>
	     <div class="comment<?php if(($z%2)!=0):?> gray<?php endif;?>">
		    <div class="avatar"><figure><img src="/assets/gfx/user_graph.jpg"></figure></div>
			<div class="box">
			 <div class="flex">
				<h5><?=$comment['nick'];?></h5>
				<div class="date"><?=lang('Flavors.RestaurantAdded');?>: <?=$comment['created_at'];?></div>
			 </div>
			 <div class="txt">
			   <?=$comment['comment'];?>
			 </div>
			 <?php if(!empty($restaurant['user_rates'][$comment['id_user']])):?>
			  <div class="rating user_rate">
			    <div class="rate_main"><?=lang('Flavors.RestaurantRate');?>: <span><?=number_format($restaurant['user_rates'][$comment['id_user']]['main'], 1, ',', '');?></span>/5</div><div class="rate_lokal" data-rate="<?=number_format($restaurant['user_rates'][$comment['id_user']]['main'], 1, '.', '');?>"></div>
				<?php if(!empty($restaurant['user_rates'][$comment['id_user']][1])):?>
				<div class="rate_type">
				  <?=lang('Flavors.RatingType_1');?>: <span><?=number_format($restaurant['user_rates'][$comment['id_user']][1], 1, ',', '');?></span>
				</div>
				<?php endif;?>
				<?php if(!empty($restaurant['user_rates'][$comment['id_user']][2])):?>
				<div class="rate_type">
				  <?=lang('Flavors.RatingType_2');?>: <span><?=number_format($restaurant['user_rates'][$comment['id_user']][2], 1, ',', '');?></span>
				</div>
				<?php endif;?>
				<?php if(!empty($restaurant['user_rates'][$comment['id_user']][3])):?>
				<div class="rate_type">
				  <?=lang('Flavors.RatingType_3');?>: <span><?=number_format($restaurant['user_rates'][$comment['id_user']][3], 1, ',', '');?></span>
				</div>
				<?php endif;?>
				<?php if(!empty($restaurant['user_rates'][$comment['id_user']][4])):?>
				<div class="rate_type">
				  <?=lang('Flavors.RatingType_4');?>: <span><?=number_format($restaurant['user_rates'][$comment['id_user']][4], 1, ',', '');?></span>
				</div>
				<?php endif;?>
			  </div>
			 <?php endif;?>
			 <div class="reply_btn">
			   <a href="/<?=$restaurant['link'];?>" onclick="RateRestaurant(this,<?=$restaurant['id'];?>,<?=$comment['id'];?>);return false;"><svg height="16px" style="enable-background:new 0 0 512 512;" viewBox="0 0 512 512"><g><path d="M448,400c0,0-36.8-208-224-208v-80L64,256l160,134.4v-92.3C325.6,298.1,395,307,448,400z"/></g></svg> odpowiedz</a>
			 </div>
			</div>
		 </div>
		 <?php if(!empty($comment['reply'])):?>
		    <?php foreach($comment['reply'] as $reply):?>
					<div class="comment reply<?php if(($z%2)!=0):?> gray<?php endif;?>">
						<div class="avatar"><figure><img src="/assets/gfx/user_graph.jpg"></figure></div>
						<div class="box">
						 <div class="flex">
							<h5><?=$reply['nick'];?></h5>
							<div class="date"><?=lang('Flavors.RestaurantAdded');?>: <?=$reply['created_at'];?></div>
						 </div>
						 <div class="txt">
						   <?=$reply['comment'];?>
						 </div>
						 <?php if(!empty($restaurant['user_rates'][$reply['id_user']])):?>
						  <div class="rating user_rate">
							<div class="rate_main"><?=lang('Flavors.RestaurantRate');?>: <span><?=number_format($restaurant['user_rates'][$reply['id_user']]['main'], 1, ',', '');?></span>/5</div><div class="rate_lokal" data-rate="<?=number_format($restaurant['user_rates'][$reply['id_user']]['main'], 1, '.', '');?>"></div>
							<?php if(!empty($restaurant['user_rates'][$reply['id_user']][1])):?>
							<div class="rate_type">
							  <?=lang('Flavors.RatingType_1');?>: <span><?=number_format($restaurant['user_rates'][$reply['id_user']][1], 1, ',', '');?></span>
							</div>
							<?php endif;?>
							<?php if(!empty($restaurant['user_rates'][$reply['id_user']][2])):?>
							<div class="rate_type">
							  <?=lang('Flavors.RatingType_2');?>: <span><?=number_format($restaurant['user_rates'][$reply['id_user']][2], 1, ',', '');?></span>
							</div>
							<?php endif;?>
							<?php if(!empty($restaurant['user_rates'][$reply['id_user']][3])):?>
							<div class="rate_type">
							  <?=lang('Flavors.RatingType_3');?>: <span><?=number_format($restaurant['user_rates'][$reply['id_user']][3], 1, ',', '');?></span>
							</div>
							<?php endif;?>
							<?php if(!empty($restaurant['user_rates'][$reply['id_user']][4])):?>
							<div class="rate_type">
							  <?=lang('Flavors.RatingType_4');?>: <span><?=number_format($restaurant['user_rates'][$reply['id_user']][4], 1, ',', '');?></span>
							</div>
							<?php endif;?>
						  </div>
						 <?php endif;?>
						 	<div class="reply_btn">
							   <a href="/<?=$restaurant['link'];?>" onclick="RateRestaurant(this,<?=$restaurant['id'];?>,<?=$comment['id'];?>);return false;"><svg height="16px" style="enable-background:new 0 0 512 512;" viewBox="0 0 512 512"><g><path d="M448,400c0,0-36.8-208-224-208v-80L64,256l160,134.4v-92.3C325.6,298.1,395,307,448,400z"/></g></svg> odpowiedz</a>
							 </div>
						</div>
					 </div>
		    <?php endforeach;?>
		 <?php endif;?>
	   <?php  $z=$z+1; endforeach;?>
	   <?php else:?>
	   <div class="empty_comments">
		Ten lokal nie ma jeszcze dodanych opinii 
	   </div>
	   <?php endif;?>  
	</div>
	  
	       <div class="info"><?=lang('Flavors.RestaurantCommentsInfo');?></div>
	  </section>


<section id="tags">
<h4><?=lang('Flavors.RestaurantTags');?></h4>
<ul>
  <li><a href="/rzeszowskie-smaki/tagi?tag=<?=$restaurant['name'];?>"><?=$restaurant['name'];?></a></li>
   <?php if(!empty($restaurant['categories'])):?>  
			<?php foreach($restaurant['categories'] as $k=>$category):?><li><a href="/rzeszowskie-smaki/tagi?tag=<?=$category['name'];?>"><?=$category['name'];?></a></li>	<?php endforeach;?> 
  <?php endif;?>
  <?php if(!empty($restaurant['tags'])):?>
	<?php foreach($restaurant['tags'] as $tag):?>
		<li><a href="/rzeszowskie-smaki/tagi?tag=<?=$tag['value'];?>"><?=$tag['value'];?></a></li>
	<?php endforeach;?>
  <?php endif;?>
</ul>
</section>
		</div>
		<div class="right">
	     <?php if(empty($mobile)):?>  
			<header>
			     <?php if(!empty($restaurant['categories'])):?>  
					<div class="cat">
						<?php foreach($restaurant['categories'] as $k=>$category):?><?php if($k<3):?><?php if($k>0):?> / <?php endif;?><a href="/<?=$category['link'];?>" class="category" title="<?=$category['name'];?>"><?=$category['name'];?></a><?php endif;?><?php endforeach;?>
				    </div> 
				 <?php endif;?>
			  <h1><?=$restaurant['name'];?> <?=$restaurant['name2'];?></h1>
			</header>
			<div class="rating">
			   <div>
			    <?php if(!empty($restaurant['avg']['rating'])):?> 
				 <h4><?=lang('Flavors.OverallrestaurantRating');?>:</h4>
				 <div id="rate_lokal_<?=$restaurant['id'];?>" class="rate_lokal" data-rate="<?=number_format($restaurant['avg']['rating'], 1, '.', '');?>">
				   <?php if(!empty($restaurant['avg']['rating'])):?><div class="rate"><span><?=number_format($restaurant['avg']['rating'], 1, ',', '');?></span>/5</div><?php endif;?>
				 </div>
						<p onclick="RscrollTo('div.rates')" class="trans400">(Liczba ocen: <b><?=$restaurant['avg']['cnt'];?></b>)</p>
				<?php else:?>
                  <p>Ten lokal nie ma jeszcze ocen.<br />Bądź pierwszym, który go oceni.</p>
                <?php endif;?>				
				 </div>
				 
			   
			   <div class="rate_btn">
			     <a href="/<?=$restaurant['link'];?>" onclick="RateRestaurant(this,<?=$restaurant['id'];?>);return false;">Oceń lokal <svg viewBox="0 0 48 48"><path d="M0 0h48v48H0z" fill="none"/><path d="M24 4C12.95 4 4 12.95 4 24s8.95 20 20 20 20-8.95 20-20S35.05 4 24 4zm10 22h-8v8h-4v-8h-8v-4h8v-8h4v8h8v4z"/></svg></a>
			   </div>
			</div>
			<?php endif;?>
			<?php if(empty($mobile)):?>  
	        <div class="parameters">
                 <?php if(!empty($restaurant['cuisine_type'])):?>
                  <div class="param cuisine_type">
				     <figure><svg viewBox="0 0 69.24 58.69"><defs><style>.cls-1ct{stroke:#fff;stroke-width:6px;}.cls-1ct,.cls-2ct{fill:none;stroke-linecap:round;stroke-linejoin:round;}.cls-2ct{stroke:#1d1d1b;stroke-width:2px;}</style></defs><g id="icons"><g><path class="cls-2ct" d="M43.46,24.47c-2.13,2.81-5.35,4.42-8.84,4.42m27.94-7.1c0,2.96-2.41,5.37-5.37,5.37"/><path class="cls-2ct" d="M57.19,10.73c-2.44,0-4.68,.82-6.51,2.16-2.09-6.88-8.49-11.89-16.06-11.89s-13.96,5.01-16.06,11.89c-1.83-1.34-4.07-2.16-6.51-2.16C5.95,10.73,1,15.68,1,21.78s4.95,11.05,11.05,11.05c3.84,0,7.21-1.96,9.2-4.93,3.07,4.05,7.91,6.67,13.37,6.67s10.31-2.62,13.37-6.67c1.98,2.97,5.36,4.93,9.2,4.93,6.1,0,11.05-4.95,11.05-11.05s-4.95-11.05-11.05-11.05Z"/><line class="cls-2ct" x1="48.9" y1="50.59" x2="48.9" y2="32.25"/><line class="cls-2ct" x1="41.79" y1="50.59" x2="41.79" y2="32.25"/><line class="cls-1ct" x1="22.71" y1="50.59" x2="54.82" y2="50.59"/><line class="cls-2ct" x1="20.34" y1="50.59" x2="54.82" y2="50.59"/><path class="cls-1ct" d="M50.09,24.32c-.85,2.01-2.08,3.82-3.6,5.34-1.52,1.52-3.33,2.75-5.34,3.6-2.01,.85-4.22,1.32-6.53,1.32s-4.53-.47-6.53-1.32c-2.01-.85-3.82-2.08-5.34-3.6-1.52-1.52-2.75-3.33-3.6-5.34"/><path class="cls-2ct" d="M50.09,24.32c-.85,2.01-2.08,3.82-3.6,5.34-1.52,1.52-3.33,2.75-5.34,3.6-2.01,.85-4.22,1.32-6.53,1.32s-4.53-.47-6.53-1.32c-2.01-.85-3.82-2.08-5.34-3.6-1.52-1.52-2.75-3.33-3.6-5.34"/><polyline class="cls-2ct" points="14.42 37.39 14.42 57.69 54.82 57.69 54.82 32.65"/></g></g></svg></figure>
					 <div>
					  <label>Rodzaj kuchni:</label>
					 <?php foreach($restaurant['cuisine_type'] as $k=>$cuisine):?><span <?php if($k>3):?>class="hide"<?php endif;?>><a href="/<?=$cuisine['link'];?>" class="cuisine"><?=$cuisine['name'];?></a></span><?php endforeach;?>
					 <?php if(count($restaurant['cuisine_type'])>3):?><span class="show_other_cuisine trans400">Inne +</span><?php endif;?>
					</div>
				  </div>
                 <?php endif;?>  		
	  <?php if(!empty($restaurant['dish_type'])):?>  
	         <div class="param dish_type">
					<figure><svg viewBox="0 0 24 24"><path d="M13,7.06V5H11V7.06A9,9,0,0,0,3.06,15H2v2H22V15H20.94A9,9,0,0,0,13,7.06Z"/></svg></figure>
					<div>
					  <label>Dania:</label>
					  <?php if(in_array(1,$restaurant['dish_type'])):?><span>Mięsne</span><?php endif;?> <?php if(in_array(2,$restaurant['dish_type'])):?><span>Wegańskie</span><?php endif;?> <?php if(in_array(3,$restaurant['dish_type'])):?><span>Wegetariańskie</span><?php endif;?>
					</div>
	         </div>
	  <?php endif;?>
	 <?php if(!empty($restaurant['address'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 82 110"><defs><style>.cls-1adr{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><path class="cls-1adr" d="M70.52,36.12C70.52,15.86,53.02-.31,32.31,1.91,17.11,3.54,4.51,15.53,2.14,30.63c-1.31,8.33,.43,16.16,4.2,22.68h-.02s24.17,41.87,24.17,41.87c2.5,4.34,8.76,4.34,11.27,0l24.17-41.86h-.02c2.93-5.08,4.63-10.94,4.63-17.2Z"/><path class="cls-1adr" d="M52.9,36.12c0,9.27-7.52,16.79-16.79,16.79s-16.79-7.52-16.79-16.79,7.52-16.79,16.79-16.79,16.79,7.52,16.79,16.79Z"/></g></g></svg></figure>
					<div>
					<label>Adres:</label>
					<span><?=$restaurant['address'];?>, <?=$restaurant['city'];?></span>
					</div>
	         </div>
	 <?php endif;?> 
	 
	  <?php if(!empty($restaurant['phone'])):?>
	    <div class="param">
		       <figure><svg viewBox="0 0 94.69 94.68"><defs><style>.cls-1rp{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><g><path class="cls-1rp" d="M45.47,25.37c13.17,0,23.85,10.68,23.85,23.85"/><path class="cls-1rp" d="M45.47,13.54c19.7,0,35.67,15.97,35.67,35.67"/><path class="cls-1rp" d="M45.47,1.71c26.24,0,47.51,21.27,47.51,47.51"/></g><path class="cls-1rp" d="M7.32,14.74h0c-11.56,11.56-4.68,37.19,15.38,57.25,20.05,20.05,45.68,26.94,57.24,15.38,3.5-3.51,5.1-11.46,5.1-11.46,.26-1.31-.5-2.77-1.69-3.25l-20.99-8.39c-1.19-.48-2.96-.08-3.93,.89l-6.08,6.08c-1.16,1.16-2.84,1.45-4.16,.85-.11-.05-.21-.11-.31-.16-.04-.02-.09-.05-.13-.08-4.62-2.52-9.51-6.19-14.12-10.79-4.6-4.6-8.27-9.49-10.79-14.11-.02-.04-.05-.08-.07-.12-.06-.11-.11-.21-.17-.32-.6-1.33-.31-3,.85-4.16l6.09-6.09c.97-.96,1.37-2.73,.89-3.93L22.04,11.34c-.48-1.19-1.94-1.96-3.25-1.7,0,0-7.96,1.59-11.46,5.09Z"/></g></g></svg></figure>
		       <div class="col">
		          <div>
				    <label>Telefon:</label>
					<span><a href="tel:<?=str_replace(' ','',$restaurant['phone']);?>"><?=$restaurant['phone'];?></a></span>
				  </div>
				  <?php if(!empty($restaurant['reservation'])):?>
				  <div>
				    <label>Rezerwacje:</label>
					<span><a href="tel:<?=str_replace(' ','',$restaurant['reservation']);?>"><?=$restaurant['reservation'];?></a></span>
				  </div>
				 <?php endif;?>
		       </div> 
	    </div> 
	 <?php endif;?> 
	 
	 <?php if(!empty($restaurant['working_hours'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 100.13 100.13"><defs><style>.cls-1wh{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><circle class="cls-1wh" cx="50.06" cy="50.06" r="48.36"/><g><path class="cls-1wh" d="M88.84,50.06c0,21.41-17.36,38.78-38.78,38.78S11.29,71.48,11.29,50.06,28.65,11.28,50.06,11.28s38.78,17.36,38.78,38.78Z"/><g><line class="cls-1wh" x1="22.64" y1="77.48" x2="27.69" y2="72.43"/><line class="cls-1wh" x1="50.06" y1="11.28" x2="50.06" y2="18.42"/><line class="cls-1wh" x1="22.65" y1="22.64" x2="27.69" y2="27.69"/><line class="cls-1wh" x1="77.48" y1="77.49" x2="72.44" y2="72.43"/><line class="cls-1wh" x1="88.85" y1="50.07" x2="81.71" y2="50.06"/><line class="cls-1wh" x1="77.49" y1="22.65" x2="72.43" y2="27.69"/><line class="cls-1wh" x1="50.06" y1="88.84" x2="50.06" y2="81.7"/><line class="cls-1wh" x1="11.28" y1="50.06" x2="18.43" y2="50.06"/></g></g><line class="cls-1wh" x1="50.06" y1="26.71" x2="50.06" y2="50.06"/><line class="cls-1wh" x1="50.06" y1="50.06" x2="61.43" y2="61.43"/></g></g></svg></figure>
					<div>
					<label>Godziny otwarcia:</label>
					<span><?=$restaurant['working_hours']; ?></span>
					</div>
	         </div>
	 <?php endif;?> 
	 
	 	 <?php if(!empty($restaurant['www']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 100.14 100.14"><defs><style>.cls-1www{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><path class="cls-1www" d="M65.93,65.93c2.5-5.99,2.5-12.77,0-18.76-1.18-2.82-2.91-5.47-5.2-7.76s-4.94-4.02-7.76-5.2l-2.9,2.9c-3.57,3.57-3.57,9.39,0,12.96,3.57,3.57,3.57,9.39,0,12.96l-2.9,2.9-14.75,14.75c-3.57,3.57-9.39,3.57-12.96,0-3.57-3.57-3.57-9.39,0-12.96l14.75-14.75c-2.5-5.99-2.5-12.77,0-18.76-2.82,1.18-5.47,2.91-7.76,5.2L8.8,57.05c-9.45,9.45-9.45,24.83,0,34.29,9.45,9.45,24.83,9.45,34.29,0l17.65-17.65c2.29-2.29,4.02-4.94,5.2-7.76Z"/><path class="cls-1www" d="M91.34,8.8c-9.45-9.45-24.83-9.45-34.29,0l-17.65,17.65c-2.29,2.29-4.02,4.94-5.2,7.76-2.5,5.99-2.5,12.77,0,18.76,1.18,2.82,2.91,5.47,5.2,7.76,2.29,2.29,4.94,4.02,7.76,5.2l2.9-2.9c3.57-3.57,3.57-9.39,0-12.96-3.57-3.57-3.57-9.39,0-12.96l2.9-2.9,14.75-14.75c3.57-3.57,9.39-3.57,12.96,0,3.57,3.57,3.57,9.39,0,12.96l-14.75,14.75c2.5,5.99,2.5,12.77,0,18.76,2.82-1.18,5.47-2.91,7.76-5.2l17.65-17.65c9.45-9.45,9.45-24.83,0-34.29Z"/></g></g></svg></figure>
					<div>
					<label>Strona internetowa:</label>
					<span><a href="<?=$restaurant['www'];?>" rel="nofollow" target="_blank"><?=str_replace(array('https://','http://'), "", $restaurant['www']); ?></a></span>
					</div>
	         </div>
	 <?php endif;?> 
	  	 <?php if(!empty($restaurant['social_link']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure>
					<svg viewBox="0 0 102.56 121.11"><defs><style>.cls-1sl{fill:none;stroke:#3c3c3b;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}.cls-2sl{fill:#fff;}</style></defs><g id="Graphics"><g><g><g><path class="cls-2sl" d="M.29,34.39s0,.07,0,.1l1.87,25.49c.31,3.52,4.35,6.51,8.18,7.34l2.43,32.84c.15,2.06,1.92,3.74,3.95,3.74h13.53c2.03,0,3.8-1.68,3.95-3.74l2.43-32.83c3.83-.83,7.87-3.8,8.18-7.3l1.87-25.08c.02-.31,.01-.5,0-.61,0-5.68-4.55-10.47-10.15-10.61H10.5c-5.65,.14-10.21,4.93-10.21,10.67Z"/><path class="cls-2sl" d="M33.19,10C33.19,4.64,28.84,.29,23.49,.29S13.78,4.64,13.78,10s4.35,9.71,9.71,9.71,9.71-4.35,9.71-9.71Z"/></g><g><path class="cls-1sl" d="M1.71,34.39l1.87,25.49c.26,3.03,4.37,5.68,7.66,6.16,.15,.02,.29,.04,.43,.05l2.51,33.96c.1,1.34,1.24,2.43,2.54,2.43h13.53c1.3,0,2.44-1.09,2.54-2.43l2.51-33.96c.14-.01,.28-.03,.43-.05,3.29-.49,7.4-3.13,7.66-6.15l1.87-25.06c.01-.21,0-.35,0-.43,0-5-3.93-9.15-8.77-9.27H10.5c-4.86,.12-8.79,4.27-8.79,9.26Z"/><path class="cls-1sl" d="M23.49,18.29c4.57,0,8.29-3.72,8.29-8.29S28.06,1.71,23.49,1.71s-8.29,3.72-8.29,8.29,3.72,8.29,8.29,8.29Z"/><line class="cls-1sl" x1="23.49" y1="63.71" x2="23.49" y2="101.41"/><line class="cls-1sl" x1="35.3" y1="66.1" x2="37.4" y2="37.78"/><line class="cls-1sl" x1="11.67" y1="66.1" x2="9.58" y2="37.78"/></g></g><g><g><path class="cls-2sl" d="M92.06,23.72h-26.04c-5.6,.14-10.15,4.93-10.15,10.61,0,.11-.01,.3,0,.61l1.87,25.08c.31,3.5,4.35,6.47,8.18,7.3l2.43,32.83c.15,2.06,1.92,3.74,3.95,3.74h13.53c2.03,0,3.8-1.68,3.95-3.74l2.43-32.84c3.83-.83,7.87-3.81,8.18-7.34l1.87-25.49s0-.07,0-.1c0-5.75-4.56-10.54-10.21-10.67Z"/><path class="cls-2sl" d="M79.07,19.7c5.35,0,9.71-4.35,9.71-9.71S84.42,.29,79.07,.29s-9.71,4.35-9.71,9.71,4.35,9.71,9.71,9.71Z"/></g><g><path class="cls-1sl" d="M100.85,34.39l-1.87,25.49c-.26,3.03-4.37,5.68-7.66,6.16-.15,.02-.29,.04-.43,.05l-2.51,33.96c-.1,1.34-1.24,2.43-2.54,2.43h-13.53c-1.3,0-2.44-1.09-2.54-2.43l-2.51-33.96c-.14-.01-.28-.03-.43-.05-3.29-.49-7.39-3.13-7.66-6.15l-1.87-25.06c-.01-.21,0-.35,0-.43,0-5,3.93-9.15,8.77-9.27h26c4.86,.12,8.79,4.27,8.79,9.26Z"/><path class="cls-1sl" d="M79.07,18.29c-4.57,0-8.29-3.72-8.29-8.29s3.72-8.29,8.29-8.29,8.29,3.72,8.29,8.29-3.72,8.29-8.29,8.29Z"/><line class="cls-1sl" x1="79.06" y1="63.71" x2="79.06" y2="101.41"/><line class="cls-1sl" x1="67.26" y1="66.1" x2="65.16" y2="37.78"/><line class="cls-1sl" x1="90.89" y1="66.1" x2="92.98" y2="37.78"/></g></g><g><g><g><path class="cls-2sl" d="M44.52,119.4c-2.9,0-5.44-2.39-5.65-5.32l-2.34-31.63c-3.94-1.26-7.9-4.43-8.26-8.48l-1.87-25.1c-.02-.43-.02-.68,0-.82,0-6.55,5.3-12.07,11.81-12.23h26.08c6.59,.16,11.92,5.71,11.92,12.38,0,.08,0,.15,0,.23l-1.87,25.49c-.36,4.09-4.32,7.28-8.27,8.54l-2.34,31.63c-.21,2.93-2.75,5.32-5.65,5.32h-13.53Z"/><path class="cls-2sl" d="M64.27,37.52c5.65,.14,10.21,4.93,10.21,10.67,0,.04,0,.07,0,.1l-1.87,25.49c-.31,3.52-4.35,6.51-8.18,7.34l-2.43,32.84c-.15,2.06-1.92,3.74-3.95,3.74h-13.53c-2.03,0-3.8-1.68-3.95-3.74l-2.43-32.83c-3.83-.83-7.87-3.8-8.18-7.3l-1.87-25.08c-.02-.31-.01-.5,0-.61,0-5.68,4.55-10.47,10.15-10.61h26.04m.04-3.41h-26.16c-7.41,.18-13.44,6.43-13.48,13.95-.01,.27,0,.55,.01,.87v.03s0,.03,0,.03l1.87,25.08c.4,4.5,4.18,7.96,8.35,9.61l2.26,30.52c.28,3.87,3.51,6.9,7.35,6.9h13.53c3.84,0,7.07-3.03,7.35-6.91l2.26-30.52c4.17-1.66,7.96-5.13,8.35-9.61l1.87-25.53c0-.09,.01-.21,.01-.35,0-7.58-6.07-13.9-13.54-14.08h-.04Z"/></g><g><path class="cls-2sl" d="M51.28,35.21c-6.29,0-11.41-5.12-11.41-11.41s5.12-11.41,11.41-11.41,11.41,5.12,11.41,11.41-5.12,11.41-11.41,11.41Z"/><path class="cls-2sl" d="M51.28,14.09c5.35,0,9.71,4.35,9.71,9.71s-4.35,9.71-9.71,9.71-9.71-4.35-9.71-9.71,4.35-9.71,9.71-9.71m0-3.41c-7.23,0-13.12,5.89-13.12,13.12s5.89,13.12,13.12,13.12,13.12-5.89,13.12-13.12-5.88-13.12-13.12-13.12h0Z"/></g></g><g><path class="cls-1sl" d="M51.28,32.09c-4.57,0-8.29-3.72-8.29-8.29s3.72-8.29,8.29-8.29,8.29,3.72,8.29,8.29-3.72,8.29-8.29,8.29Z"/><line class="cls-1sl" x1="51.28" y1="77.51" x2="51.28" y2="115.21"/><line class="cls-1sl" x1="39.47" y1="79.9" x2="37.37" y2="51.58"/><line class="cls-1sl" x1="63.1" y1="79.9" x2="65.19" y2="51.58"/></g></g><path class="cls-1sl" d="M73.07,48.19l-1.87,25.49c-.26,3.03-4.37,5.68-7.66,6.16-.15,.02-.29,.04-.43,.05l-2.51,33.96c-.1,1.34-1.24,2.43-2.54,2.43h-13.53c-1.3,0-2.44-1.09-2.54-2.43l-2.51-33.96c-.14-.01-.28-.03-.43-.05-3.29-.49-7.39-3.13-7.66-6.15l-1.87-25.06c-.01-.21,0-.35,0-.43,0-5,3.93-9.15,8.77-9.27h26c4.86,.12,8.79,4.27,8.79,9.26Z"/></g></g></svg>
					</figure>
					<div>
					<label>Media społecznościowe:</label>
					<span><a href="<?=$restaurant['social_link'];?>" rel="nofollow" target="_blank"><?=str_replace(array('https://','http://'), "", $restaurant['social_link']); ?></a></span>
					</div>
	         </div>
	 <?php endif;?> 
	 	 <?php if(!empty($restaurant['email']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 100.14 70.85"><defs><style>.cls-1em{fill:#fff;}.cls-2em{fill:none;stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}</style></defs><g id="Graphics"><g><rect class="cls-2em" x="1.71" y="1.71" width="96.72" height="67.44" rx="3.87" ry="3.87"/><g><path class="cls-1em" d="M5.58,69.14c-2.13,0-2.61-1.2-1.07-2.68L47.27,25.5c1.54-1.47,4.05-1.47,5.59,0l42.77,40.97c1.54,1.47,1.05,2.68-1.07,2.68H5.58Z"/><path class="cls-2em" d="M5.58,69.14c-2.13,0-2.61-1.2-1.07-2.68L47.27,25.5c1.54-1.47,4.05-1.47,5.59,0l42.77,40.97c1.54,1.47,1.05,2.68-1.07,2.68H5.58Z"/></g><g><path class="cls-1em" d="M94.56,1.71c2.13,0,2.61,1.2,1.07,2.68L52.86,45.35c-1.54,1.47-4.05,1.47-5.59,0L4.5,4.38c-1.54-1.47-1.05-2.68,1.07-2.68H94.56Z"/><path class="cls-2em" d="M94.56,1.71c2.13,0,2.61,1.2,1.07,2.68L52.86,45.35c-1.54,1.47-4.05,1.47-5.59,0L4.5,4.38c-1.54-1.47-1.05-2.68,1.07-2.68H94.56Z"/></g></g></g></svg></figure>
					<div>
					<label>E-mail:</label>
					<span><a href="mailto:<?=$restaurant['email'];?>" rel="nofollow" target="_blank"><?=$restaurant['email']; ?></a></span>
					</div>
	         </div>
	 <?php endif;?> 
	 <?php if(!empty($restaurant['chef']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 57.48 70.43"><defs><style>.cls-1chef{stroke:#fff;stroke-width:6px;}.cls-1chef,.cls-2chef,.cls-3chef,.cls-4chef{stroke-linecap:round;}.cls-1chef,.cls-2chef,.cls-4chef{fill:none;}.cls-1chef,.cls-3chef,.cls-4chef{stroke-linejoin:round;}.cls-2chef{stroke-miterlimit:10;}.cls-2chef,.cls-3chef,.cls-4chef{stroke:#1d1d1b;stroke-width:2px;}.cls-3chef{fill:#fff;}</style></defs><g id="icons"><g><g><path class="cls-2chef" d="M55.81,69.43l.65-11.03c.22-3.19-1.83-5.89-5.34-6.99l-14.67-4.29"/><path class="cls-2chef" d="M1.67,69.43l-.65-11.03c-.22-3.19,1.83-5.89,5.34-6.99l14.67-4.29"/><line class="cls-2chef" x1="22.11" y1="42.44" x2="21.03" y2="47.12"/><line class="cls-2chef" x1="35.37" y1="42.44" x2="36.45" y2="47.12"/><path class="cls-3chef" d="M17.58,27.93c-1.9,.11-3.42,1.67-3.42,3.6s1.62,3.62,3.62,3.62c.2,0,.39-.03,.58-.06h0c1.64,6.65,5.66,11.35,10.37,11.35s8.73-4.71,10.37-11.35c.19,.03,.38,.06,.58,.06,2,0,3.62-1.62,3.62-3.62s-1.51-3.49-3.42-3.6"/></g><path class="cls-4chef" d="M36.45,47.12c0,2.57-1.66,7.86-7.71,7.86s-7.71-5.29-7.71-7.86"/><line class="cls-1chef" x1="28.74" y1="54.98" x2="15.1" y2="54.98"/><polyline class="cls-4chef" points="28.74 54.98 15.1 54.98 15.1 63.58"/><line class="cls-2chef" x1="15.1" y1="59.23" x2="15.1" y2="69.43"/><line class="cls-4chef" x1="22.48" y1="61.02" x2="22.48" y2="61.02"/><line class="cls-4chef" x1="22.48" y1="67.71" x2="22.48" y2="67.71"/><line class="cls-4chef" x1="34.83" y1="61.02" x2="34.83" y2="61.02"/><line class="cls-4chef" x1="34.83" y1="67.71" x2="34.83" y2="67.71"/><polyline class="cls-4chef" points="17.2 21.17 17.2 27.93 39.9 27.93 39.9 21.17"/><g><path class="cls-4chef" d="M38.25,20.93c4.23,0,7.65-3.43,7.65-7.65s-3.43-7.65-7.65-7.65c-.87,0-1.71,.15-2.49,.42-1.06-2.94-3.88-5.05-7.19-5.05s-6.13,2.11-7.19,5.07c-.79-.28-1.64-.44-2.53-.44-4.23,0-7.65,3.43-7.65,7.65s3.43,7.65,7.65,7.65"/><path class="cls-4chef" d="M38.25,20.93c-2.11,0-4.03-.86-5.41-2.24"/><path class="cls-4chef" d="M18.85,20.93c2.11,0,4.03-.86,5.41-2.24"/><path class="cls-4chef" d="M33.98,3.24c.69,.69,1.25,1.52,1.64,2.43,.39,.92,.6,1.92,.6,2.98s-.21,2.06-.6,2.98"/></g><path class="cls-4chef" d="M36.45,47.12c0,2.57-1.66,7.86-7.71,7.86"/></g></g></svg></figure>
					<div>
					<label>Szef kuchni:</label>
					<span><?=$restaurant['chef']; ?></span>
					</div>
	         </div>
	 <?php endif;?> 
	 	 <?php if(!empty($restaurant['speciality']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 70.2 74"><defs><style>.cls-1sp{stroke:#fff;stroke-width:6px;}.cls-1sp,.cls-2sp,.cls-3sp,.cls-4sp{stroke-linejoin:round;}.cls-1sp,.cls-3sp{fill:#fff;}.cls-1sp,.cls-3sp,.cls-4sp{stroke-linecap:round;}.cls-2sp,.cls-3sp,.cls-4sp{stroke:#1d1d1b;stroke-width:2px;}.cls-2sp,.cls-4sp{fill:none;}.cls-5sp{fill:#1d1d1b;}</style></defs><g id="icons"><g><g><line class="cls-4sp" x1="2" y1="37.19" x2="69.2" y2="37.19"/><line class="cls-4sp" x1="9.32" y1="41.99" x2="61.88" y2="41.99"/><g><path class="cls-2sp" d="M52.8,33.73c0-4.74-1.93-9.04-5.04-12.16"/><path class="cls-4sp" d="M47.76,21.57c-3.12-3.12-7.42-5.04-12.16-5.04"/><path class="cls-2sp" d="M13.32,33.73c0-12.3,9.97-22.28,22.28-22.28s22.28,9.98,22.28,22.28"/></g><path class="cls-2sp" d="M39.09,4.49c0,1.93-1.56,3.49-3.49,3.49s-3.49-1.56-3.49-3.49,1.56-3.49,3.49-3.49,3.49,1.56,3.49,3.49Z"/></g><g><path class="cls-4sp" d="M41.82,52.33s7.78-3.26,11.85-4.98c3.6-1.52,5.95-.46,7.62,1.6,.51,.63,.32,1.57-.38,1.97l-18.53,10.54c-.34,.2-.73,.3-1.13,.3H19.29"/><path class="cls-4sp" d="M10.32,54.14c4.35-5.46,8.18-7.54,12.47-7.54,3.6,0,5.14,.42,8.21,2.48h5.52c1.43,0,4.96,.38,5.51,4.22,.1,.73-.48,1.39-1.22,1.39h-12.54"/><polyline class="cls-1sp" points="3 57.4 7.04 53.36 20.63 66.96 16.59 71"/><polyline class="cls-3sp" points="3 57.4 7.04 53.36 20.63 66.96 16.59 71"/><path class="cls-5sp" d="M6.39,60.63c-.53,.53-.53,1.39,0,1.92,.53,.53,1.39,.53,1.92,0,.53-.53,.53-1.39,0-1.92-.53-.53-1.39-.53-1.92,0Z"/></g></g></g></svg></figure>
					<div>
					<label><?=lang('Flavors.RestaurantSpeciality');?>:</label>
					<span><?=$restaurant['speciality']; ?></span>
					</div>
	         </div>
	 <?php endif;?>
	  	 <?php if(!empty($restaurant['opening_year']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 113.22 99.21"><defs><style>.cls-1op{fill:none;}.cls-1op,.cls-2op{stroke:#2e2d2c;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.41px;}.cls-2op{fill:#fff;}</style></defs><g id="Graphics"><g><rect class="cls-1op" x="1.71" y="12.54" width="109.81" height="84.96" rx="7.74" ry="7.74"/><g><path class="cls-2op" d="M87.93,23.38c-2.83,0-5.12-2.29-5.12-5.12V6.82c0-2.83,2.29-5.12,5.12-5.12s5.12,2.29,5.12,5.12v11.43c0,2.83-2.29,5.12-5.12,5.12Z"/><path class="cls-2op" d="M25.29,23.38c-2.83,0-5.12-2.29-5.12-5.12V6.82c0-2.83,2.29-5.12,5.12-5.12s5.12,2.29,5.12,5.12v11.43c0,2.83-2.29,5.12-5.12,5.12Z"/></g><line class="cls-2op" x1="1.71" y1="34.15" x2="111.51" y2="34.15"/><g><polyline class="cls-2op" points="87.07 59.41 87.07 46.99 99.49 46.99"/><polyline class="cls-2op" points="62.62 59.41 62.62 46.99 75.04 46.99"/><polyline class="cls-2op" points="38.18 59.41 38.18 46.99 50.6 46.99"/><polyline class="cls-2op" points="13.73 59.41 13.73 46.99 26.15 46.99"/></g><g><polyline class="cls-2op" points="87.07 84.66 87.07 72.24 99.49 72.24"/><polyline class="cls-2op" points="62.62 84.66 62.62 72.24 75.04 72.24"/><polyline class="cls-2op" points="38.18 84.66 38.18 72.24 50.6 72.24"/><polyline class="cls-2op" points="13.73 84.66 13.73 72.24 26.15 72.24"/></g></g></g></svg></figure>
					<div>
					<label><?=lang('Flavors.RestaurantOpeningYear');?>:</label>
					<span><?=$restaurant['opening_year']; ?></span>
					</div>
	         </div>
	 <?php endif;?>
	   	 <?php if(!empty($restaurant['table_numbers']) and !empty($restaurant['awarded'])):?> 
	   <div class="param">
					<figure><svg viewBox="0 0 64.36 71.57"><defs><style>.cls-1tn{fill:none;stroke:#1d1d1b;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><g id="icons"><g><g><path class="cls-1tn" d="M49.84,33.18H14.52c-.62,0-1.12,.5-1.12,1.12v7.66c3.13,0,3.13-3.93,6.26-3.93s3.13,3.93,6.26,3.93,3.13-3.93,6.26-3.93,3.13,3.93,6.26,3.93,3.13-3.93,6.26-3.93,3.13,3.93,6.26,3.93v-7.66c0-.62-.5-1.12-1.12-1.12Z"/><g><path class="cls-1tn" d="M45.1,70.57v-17.66c0-2.81,2.28-5.09,5.08-5.09h13.18"/><line class="cls-1tn" x1="45.1" y1="53.97" x2="63.36" y2="53.97"/><line class="cls-1tn" x1="63.36" y1="25.56" x2="63.36" y2="70.57"/></g><g><path class="cls-1tn" d="M19.26,70.57v-17.66c0-2.81-2.28-5.09-5.08-5.09H1"/><line class="cls-1tn" x1="19.26" y1="53.97" x2="1" y2="53.97"/><line class="cls-1tn" x1="1" y1="25.56" x2="1" y2="70.57"/></g><line class="cls-1tn" x1="24.62" y1="70.57" x2="39.74" y2="70.57"/><line class="cls-1tn" x1="32.18" y1="70.57" x2="32.18" y2="41.93"/></g><path class="cls-1tn" d="M19.45,16.64c.7-1.65,1.71-3.14,2.96-4.39s2.74-2.26,4.39-2.96c1.65-.7,3.47-1.09,5.38-1.09s3.73,.39,5.38,1.09c1.65,.7,3.14,1.71,4.39,2.96,1.25,1.25,2.26,2.74,2.96,4.39H19.45Z"/><line class="cls-1tn" x1="32.18" y1="1" x2="32.18" y2="8.2"/></g></g></svg></figure>
					<div>
					<label><?=lang('Flavors.RestaurantTableNumbers');?>:</label>
					<span><?=$restaurant['table_numbers']; ?></span>
					</div>
	         </div>
	 <?php endif;?>
	 
	 </div>	
	<?php if(!empty($restaurant['coordinates_array'])):?>
	  <div class="map">
	     <h4>Lokalizacja</h4>
	     <div id="map" data-marker="/<?=$restaurant['link'];?>" data-lat="<?=$restaurant['coordinates_array'][0];?>" data-long="<?=$restaurant['coordinates_array'][1];?>"></div>
	 </div>
	<?php endif;?> 
	<?php endif;?>
	 <?php if(!empty($restaurant['similar_location']) and empty($restaurant['awarded'])):?>
	    <div class="similar-location">
	       <h4>W tej okolicy znajdziesz także:</h4>
	        <div class="similar-list">
	            <?php foreach($restaurant['similar_location'] as $rest):?>
				   <div class="similar-item">
				      <figure>
						<a href="/<?=$rest['link'];?>" title="<?=$rest['name'];?>, <?=$rest['address'];?>, <?=$rest['city'];?>">
							<?php if(!empty($rest['photo']['path'])):?>
								<img src="/image/c/185/140/<?=$rest['photo']['path'];?>" alt="<?php if(!empty($rest['photo']['caption'])):?><?=esc($rest['photo']['caption']);?><?php else:?><?=esc($rest['name']);?><?php endif;?>">
							<?php else:?>
								<img src="/image/c/185/140/flavors/20231213/brak_zdjecia.jpg" alt="<?=esc($rest['name']);?>">
							<?php endif;?>
						</a>				
					  </figure>
					  <div class="box">
					     <?php if(!empty($rest['category']['name'])):?>
						  <h4><a href="/<?=$rest['category']['link'];?>"><?=$rest['category']['name'];?></a></h4>
						 <?php endif;?>
					     <h2><a href="/<?=$rest['link'];?>"><?=$rest['name'];?></a></h2>
						 <h5><?=$rest['address'];?></h5>
					  </div>
				      <div class="distance">
					     <img src="/assets/gfx/distance.png" alt="Odległość" />
						 <br /><?=$rest['distance'];?> m
					  </div>
				   </div>
				<?php endforeach;?>
	        </div>
	    </div>
	<?php endif;?>
	<?php if(!empty($restaurant['vegetarian_list']) and empty($restaurant['awarded'])):?>
	<div class="vegetarian">  
	  <h4>Dania wegetariańskie serwują również:</h4>
	        <div class="vegetarian-list">
	            <?php foreach($restaurant['vegetarian_list'] as $rest):?>
				   <div class="vegetarian-item">
				      <figure>
						<a href="/<?=$rest['link'];?>" title="<?=$rest['name'];?>, <?=$rest['address'];?>, <?=$rest['city'];?>">
							<?php if(!empty($rest['photo']['path'])):?>
								<img src="/image/c/185/140/<?=$rest['photo']['path'];?>" alt="<?php if(!empty($rest['photo']['caption'])):?><?=esc($rest['photo']['caption']);?><?php else:?><?=esc($rest['name']);?><?php endif;?>">
							<?php else:?>
								<img src="/image/c/185/140/flavors/20231213/brak_zdjecia.jpg" alt="<?=esc($rest['name']);?>">
							<?php endif;?>
						</a>				
					  </figure>
					  <div class="box">
					     <?php if(!empty($rest['category']['name'])):?>
						  <h4><a href="/<?=$rest['category']['link'];?>"><?=$rest['category']['name'];?></a></h4>
						 <?php endif;?>
					     <h2><a href="/<?=$rest['link'];?>"><?=$rest['name'];?></a></h2>
						 <h5><?=$rest['address'];?></h5>
					  </div>
				      <div class="rating">
					     
						 <?php if(!empty($rest['avg']['rating'])):?>
						<div class="rate"><span><?=number_format($rest['avg']['rating'], 1, ',', '');?></span>/5</div>
						<div id="rate_lokal_<?=$rest['id'];?>" class="rate_lokal" data-rate="<?=number_format($rest['avg']['rating'], 1, '.', '');?>"></div>
						<?php endif;?>
					  </div>
				   </div>
				<?php endforeach;?>
	        </div>
	<?php endif; ?>
	
	
	<?php if(!empty($restaurant['others']) and empty($restaurant['awarded'])):?>
	<div class="vegetarian">  
	  <h4>Mogą zainteresować Cię również:</h4>
	        <div class="vegetarian-list">
	            <?php foreach($restaurant['others'] as $rest):?>
				   <div class="vegetarian-item">
				      <figure>
						<a href="/<?=$rest['link'];?>" title="<?=$rest['name'];?>, <?=$rest['address'];?>, <?=$rest['city'];?>">
							<?php if(!empty($rest['photo']['path'])):?>
								<img src="/image/c/185/140/<?=$rest['photo']['path'];?>" alt="<?php if(!empty($rest['photo']['caption'])):?><?=esc($rest['photo']['caption']);?><?php else:?><?=esc($rest['name']);?><?php endif;?>">
							<?php else:?>
								<img src="/image/c/185/140/flavors/20231213/brak_zdjecia.jpg" alt="<?=esc($rest['name']);?>">
							<?php endif;?>
						</a>				
					  </figure>
					  <div class="box">
					     <?php if(!empty($rest['category']['name'])):?>
						  <h4><a href="/<?=$rest['category']['link'];?>"><?=$rest['category']['name'];?></a></h4>
						 <?php endif;?>
					     <h2><a href="/<?=$rest['link'];?>"><?=$rest['name'];?></a></h2>
						 <h5><?=$rest['address'];?></h5>
					  </div>
				      <div class="rating">
					     
						 <?php if(!empty($rest['avg']['rating'])):?>
						<div class="rate"><span><?=number_format($rest['avg']['rating'], 1, ',', '');?></span>/5</div>
						<div id="rate_lokal_<?=$rest['id'];?>" class="rate_lokal" data-rate="<?=number_format($rest['avg']['rating'], 1, '.', '');?>"></div>
						<?php endif;?>
					  </div>
				   </div>
				<?php endforeach;?>
	        </div>
	<?php endif; ?>
		<?php if(!empty($restaurant['ranking_others']) and empty($restaurant['awarded'])):?>
	<div class="vegetarian">  
	  <h4>Ranking lokali:</h4>
	        <div class="vegetarian-list">
	            <?php foreach($restaurant['ranking_others'] as $rest):?>
				   <div class="vegetarian-item">
				      <figure>
						<a href="/<?=$rest['link'];?>" title="<?=$rest['name'];?>, <?=$rest['address'];?>, <?=$rest['city'];?>">
							<?php if(!empty($rest['photo']['path'])):?>
								<img src="/image/c/185/140/<?=$rest['photo']['path'];?>" alt="<?php if(!empty($rest['photo']['caption'])):?><?=esc($rest['photo']['caption']);?><?php else:?><?=esc($rest['name']);?><?php endif;?>">
							<?php else:?>
								<img src="/image/c/185/140/flavors/20231213/brak_zdjecia.jpg" alt="<?=esc($rest['name']);?>">
							<?php endif;?>
						</a>				
					  </figure>
					  <div class="box">
					     <?php if(!empty($rest['category']['name'])):?>
						  <h4><a href="/<?=$rest['category']['link'];?>"><?=$rest['category']['name'];?></a></h4>
						 <?php endif;?>
					     <h2><a href="/<?=$rest['link'];?>"><?=$rest['name'];?></a></h2>
						 <h5><?=$rest['address'];?></h5>
					  </div>
				      <div class="rating">
					     
						 <?php if(!empty($rest['avg']['rating'])):?>
						<div class="rate"><span><?=number_format($rest['avg']['rating'], 1, ',', '');?></span>/5</div>
						<div id="rate_lokal_<?=$rest['id'];?>" class="rate_lokal" data-rate="<?=number_format($rest['avg']['rating'], 1, '.', '');?>"></div>
						<?php endif;?>
					  </div>
				   </div>
				<?php endforeach;?>
	        </div>
	<?php endif; ?>
	    </div>
	    </div>	
	  </div>
</article>
<?php endif; ?>
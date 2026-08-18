<?php if(!empty($rating)):?>
<div class="home-rating">
   <h2><?=lang('Flavors.Newest');?> <span><?=lang('Flavors.Rating');?></span></h2>
   <div class="list">
   <?php foreach($rating as $rate):?>
    <div class="rate-box">
	  <div class="inside">
	    <figure><a href="/<?=$rate['info_restaurant']['link'];?>"><svg viewBox="0 0 32 32"><path d="M16,20a8,8,0,1,1,8-8A8,8,0,0,1,16,20ZM16,6a6,6,0,1,0,6,6A6,6,0,0,0,16,6Z"></path><path d="M30,32H28A12,12,0,0,0,4,32H2a14,14,0,0,1,28,0Z"></path></svg></a></figure>
		<div class="info">
		  <h3><a href="/<?=$rate['info_restaurant']['link'];?>"><?=$rate['info_restaurant']['name'];?></a></h3>
		 <div class="user_rate">
		 <?php if(!empty($rate['info_user']['nick'])):?>
		    <div class="user"><?=$rate['info_user']['nick'];?></div>
		  <?php elseif(!empty($rate['info_user']['name']) and !empty($rate['info_user']['surname'])):?>
		   <div class="user"><?=$rate['info_user']['name'];?> <?=$rate['info_user']['surname'];?></div>
		  <?php endif;?>
		  <?=lang('Flavors.RatedPlace');?>:
		  </div>
			<div class="rating">
			    <?php if(!empty($rate['rating_main'])):?><div class="rate"><span><?=number_format($rate['rating_main'], 1, '.', '');?></span>/5</div><?php endif;?>
				<div id="rate_lokal_<?=$rate['id'];?>" class="rate_lokal" data-rate="<?=number_format($rate['rating_main'], 1, '.', '');?>"></div>
			</div> 
		</div>
	  </div>
    </div>
   <?php endforeach;?>
   </div>
</div>
<?php endif;?>
<?php
/* 
Ranking restauracji
*/
?>
<section class="ranking">
  <?php if(!empty($title)): ?>
  <header>
    <h1><?=$title;?></h1>
  </header>
 <?php endif;?>
<div class="filters">
	<form method="get">
        <?php if(!empty($data['categories'])):?>
		<div class="box">  
		  <label for="cat"><?=lang('Flavors.Type');?></label>
		  <select id="cat" name="cat" onchange="this.form.submit()">
		     <option value=""><?=lang('Flavors.All');?></option>
		     <?php foreach($data['categories'] as $cat):?>
			   <option value="<?=$cat['id'];?>" <?php if(!empty($data['get']['cat']) and $data['get']['cat']==$cat['id']):?> selected="selected"<?php endif;?>><?=$cat['name'];?></option>
			 <?php endforeach;?>
		  </select>
		</div>  
		<?php endif;?>
       <div class="box"> 
	       <label for="type"><?=lang('Flavors.RankingType');?></label>
	       <select id="type" name="type" onchange="this.form.submit()">
		     <option value=""><?=lang('Flavors.RestaurantAvgRating');?></option>
		     <option value="1"<?php if(!empty($data['get']['type']) and $data['get']['type']==1):?> selected="selected"<?php endif;?>><?=lang('Flavors.RatingType_1');?></option>
			 <option value="2"<?php if(!empty($data['get']['type']) and $data['get']['type']==2):?> selected="selected"<?php endif;?>><?=lang('Flavors.RatingType_2');?></option>
			 <option value="3"<?php if(!empty($data['get']['type']) and $data['get']['type']==3):?> selected="selected"<?php endif;?>><?=lang('Flavors.RatingType_3');?></option>
			 <option value="4"<?php if(!empty($data['get']['type']) and $data['get']['type']==4):?> selected="selected"<?php endif;?>><?=lang('Flavors.RatingType_4');?></option>		   
		  </select>
	   </div>
	   <input type="hidden" name="order" value="" id="type_order" />
	</form>
</div>
<p><?=lang('Flavors.RankingInfo');?></p>
<div class="list">
  <div class="list-row list-head">
			<div class="list-col w70 center"><?=lang('Flavors.Place');?></div>
            <div class="list-col w100">&nbsp;</div>
			<div class="list-col name"><?=lang('Flavors.Restaurant');?></div>
			<div data-order="rate" class="order list-col w120 center<?php if((!empty($data['get']['order']) and $data['get']['order']=='rate_desc') or empty($data['get']['order'])):?> desc<?php elseif(!empty($data['get']['order']) and $data['get']['order']=='rate_asc'):?> asc<?php endif;?>"><?=lang('Flavors.RestaurantRate');?></div>
			<div data-order="cnt" class="order list-col w120 center<?php if(!empty($data['get']['order']) and $data['get']['order']=='cnt_desc'):?> desc<?php elseif(!empty($data['get']['order']) and $data['get']['order']=='cnt_asc'):?> asc<?php endif;?>"><?=lang('Flavors.RatesNumber');?></div>
			<div data-order="comments" class="order list-col w120 center<?php if(!empty($data['get']['order']) and $data['get']['order']=='comments_desc'):?> desc<?php elseif(!empty($data['get']['order']) and $data['get']['order']=='comments_asc'):?> asc<?php endif;?>"><?=lang('Flavors.CommentsNumber');?></div>
			<div class="list-col w120">&nbsp;</div>
  </div> 
  <?php if(!empty($data['ranking'])):?>
    <?php foreach($data['ranking'] as $k=>$rank):?>
	  <div class="list-row <?php if($k<10):?>top<?php endif;?>">
	    <div class="list-col w70 center place"><?=$k+1;?></div>
		<div class="list-col w100">
			<figure>
				<a href="/<?=$rank['link'];?>" title="<?=$rank['name'];?>">
					<?php if(!empty($rank['photo']['path'])):?>
						<img src="/image/c/100/60/<?=$rank['photo']['path'];?>" alt="<?php if(!empty($rank['photo']['caption'])):?><?=esc($rank['photo']['caption']);?><?php else:?><?=esc($rank['name']);?><?php endif;?>">
					<?php else:?>
						<img src="/image/c/100/60/flavors/20231213/brak_zdjecia.jpg" alt="<?=esc($rank['name']);?>">
					<?php endif;?>
				</a>
			</figure>
		</div>
		<div class="list-col name">
		<header>
		    <?php if(!empty($rank['category']['name'])):?><h3><a href="/<?=$rank['category']['link'];?>"><?=$rank['category']['name'];?></a></h3><?php endif;?>
            <h2><a href="/<?=$rank['link'];?>"><?=$rank['name'];?></a></h2>
        </header>
		</div>
		<div class="list-col w120 center">
			<div class="rating">
			    <?php if(!empty($rank['rate'])):?><div class="rate"><span><?=number_format($rank['rate'], 1, ',', '');?></span></div><?php endif;?>
				<div id="rate_lokal_<?=$rank['id'];?>" class="rate_lokal" data-rate="<?=number_format($rank['rate'], 1, '.', '');?>"></div>
			</div> 
		</div>
		<div class="list-col w120 center nr"><?=$rank['cnt'];?></div>
		<div class="list-col w120 center nr"><?=$rank['comments'];?></div>
		<div class="list-col w120 rate_btn">
		  <a href="/<?=$rank['link'];?>" onclick="RateRestaurant(this,<?=$rank['id'];?>);return false;">OCEŃ <svg viewBox="0 0 48 48"><path d="M0 0h48v48H0z" fill="none"/><path d="M24 4C12.95 4 4 12.95 4 24s8.95 20 20 20 20-8.95 20-20S35.05 4 24 4zm10 22h-8v8h-4v-8h-8v-4h8v-8h4v8h8v4z"/></svg></a>
		</div>
	  </div>
	<?php endforeach;?>
  <?php endif;?>
</div>


</section>
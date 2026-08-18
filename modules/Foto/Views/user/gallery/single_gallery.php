<?php
/* 
Fotorelacja
*/
?>
<div id="gallery_single">
  <h2 class="nag"><?=$data['gallery']['name'];?></h2>
  <h3 class="nag"><?=lang('Foto.GalleryCreatedDate');?>: <?=date('d.m.Y',strtotime($data['gallery']['created_at']));?>&nbsp;&nbsp;&nbsp;<?=lang('Foto.Author');?>: <a href="/<?=$data['gallery']['user_link'];?>"><?php if(!empty($data['gallery']['user_nick'])):?><?=$data['gallery']['user_nick'];?><?php elseif(!empty($data['gallery']['user_name']) and !empty($data['gallery']['user_surname'])):?><?=$data['gallery']['user_name'];?> <?=$data['gallery']['user_surname'];?><?php endif;?></a> &nbsp;&nbsp;&nbsp;<a href="/<?=$data['gallery']['user_link'];?>"><span class="trans400"><?=lang('Foto.GoToUSerGallery');?> »</span></a></h3>
  
  <?php if(!empty($data['gallery']['description'])):?>
  <div class="description">
    <?=$data['gallery']['description'];?>
  </div>
  <?php endif; ?>
<div id="main_photo">
<div class="zdj_nav">                       
    <?php if(!empty($data['gallery']['main_photo']['prev_url'])):?> <a id="zdj_prev1" href="/<?=$data['gallery']['main_photo']['prev_url'];?>">«</a><?php endif;?> 
		<span class="zdj_stat"><?=lang('Foto.Photo');?> <?=$data['page'];?> <?=lang('Foto.from');?> <?=count($data['gallery']['photos']);?></span> 
    <?php if(!empty($data['gallery']['main_photo']['next_url'])):?><a id="zdj_next1" href="/<?=$data['gallery']['main_photo']['next_url'];?>">»</a><?php endif;?>                            
</div>						   
<figure id="zdj">
	<?php if(!empty($data['gallery']['main_photo']['prev_url'])):?> <div class="prev_foto_btn"><a href="/<?=$data['gallery']['main_photo']['prev_url'];?>"><span class="trans400"><svg viewBox="0 0 32 32"><defs><style>.cls-1ar{fill:none;stroke:#ffffff;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><title></title><g id="chevron-left"><line class="cls-1ar" x1="11" x2="20" y1="16" y2="7"></line><line class="cls-1ar" x1="20" x2="11" y1="25" y2="16"></line></g></svg></span></a></div><?php endif;?>
    <?php if(!empty($data['gallery']['main_photo']['next_url'])):?> <div class="next_foto_btn"><a href="/<?=$data['gallery']['main_photo']['next_url'];?>"><span class="trans400"><svg viewBox="0 0 32 32"><defs><style>.cls-1ar{fill:none;stroke:#ffffff;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><title></title><g id="chevron-right"><line class="cls-1ar" x1="21" x2="12" y1="16" y2="25"></line><line class="cls-1ar" x1="12" x2="21" y1="7" y2="16"></line></g></svg></span></a></div><?php endif;?>
    <?php if(!empty($data['gallery']['main_photo']['path'])): ?>
<div style="display:inline-block;position:relative;">	
	<a href="/image/original/<?=$data['gallery']['main_photo']['path'];?>" title="<?=lang('Foto.ClickToZoom');?>" class="praca_zoom" target="_blank"><svg viewBox="0 0 512 512" width="512px"><path d="M448.3,424.7L335,311.3c20.8-26,33.3-59.1,33.3-95.1c0-84.1-68.1-152.2-152-152.2c-84,0-152,68.2-152,152.2  s68.1,152.2,152,152.2c36.2,0,69.4-12.7,95.5-33.8L425,448L448.3,424.7z M120.1,312.6c-25.7-25.7-39.8-59.9-39.8-96.3  s14.2-70.6,39.8-96.3S180,80,216.3,80c36.3,0,70.5,14.2,96.2,39.9s39.8,59.9,39.8,96.3s-14.2,70.6-39.8,96.3  c-25.7,25.7-59.9,39.9-96.2,39.9C180,352.5,145.8,338.3,120.1,312.6z"></path></svg></a>   
	   
<img src="/image/original/<?=$data['gallery']['main_photo']['path'];?>" class="praca_zdj" alt="<?php if(!empty($data['gallery']['main_photo']['caption'])):?><?=$data['gallery']['main_photo']['caption'];?><?php else:?>Rzeszów - <?=esc($data['gallery']['name']);?><?php endif;?>">
</div>
<?php endif; ?>
</figure>
<div class="zdj_nav">
                <?php if(!empty($data['gallery']['main_photo']['prev_url'])):?> <a id="zdj_prev1" href="/<?=$data['gallery']['main_photo']['prev_url'];?>">«</a><?php endif;?> 
                   <span class="zdj_stat"><?=lang('Foto.Photo');?> <?=$data['page'];?> <?=lang('Foto.from');?> <?=count($data['gallery']['photos']);?></span>
                <?php if(!empty($data['gallery']['main_photo']['next_url'])):?><a id="zdj_next1" href="/<?=$data['gallery']['main_photo']['next_url'];?>">»</a><?php endif;?> 
                                            
</div>
</div>
<?php if(!empty($data['gallery']['photos'])): $x=0;?>
<div id="other_photo">
      <?php foreach($data['gallery']['photos'] as $photo): $x++;?>
        <div class="item<?php if($data['gallery']['main_photo']['path']==$photo['path']):?> active<?php endif;?>">
		 <figure>
		   <a href="/<?=$data['gallery']['link'];?>/g/<?=crc32($photo['id']);?>">
			<source srcset="/image/c/300/300/<?=$photo['path']; ?>" media="(max-width: 800px)">
			<img src="/image/c/460/460/<?=$photo['path']; ?>" alt="<?php if(!empty($photo['caption'])):?><?=$photo['caption'];?><?php else:?>Rzeszów - <?=esc($data['gallery']['name']);?><?php endif;?> - <?=lang('Foto.photo');?> <?=$x;?>" class="trans400" />
		   </a>
		 </figure>
		</div>
      <?php endforeach;?>
</div>
<?php endif;?>
<?php if(!empty($data['gallery']['related_gallery'])):?>
<div class="related_gallery">
<h3><?php if(!empty($data['gallery']['id_category']) and $data['gallery']['id_category']==20):?>Pozostałe fotorelacje z tej inwestycji:<?php else:?>Pozostałe fotorelacje:<?php endif;?></h3>
  <div class="list">
     <?php foreach($data['gallery']['related_gallery'] as $gal):?>
	 
	 <div class="photo-item item-<?=$gal['id']; ?>">
                    <div class="photo">
                        <?php if($gal['photo']): ?>
                                <picture>    
									<a href="/<?=$gal['link']; ?>" title="<?=esc($gal['name']); ?>">
										<source srcset="/image/c/500/150/<?=$gal['photo']; ?>" media="(max-width: 800px)">
										<img src="/image/c/740/490/<?=$gal['photo']; ?>" alt="Rzeszów - <?=esc($gal['name']); ?>" class="trans400" />
									</a>	
                                </picture>
							<div class="info">
							 <h3><a href="/<?=$gal['link']; ?>" title="<?=esc($gal['name']); ?>"><?=$gal['name']; ?></a></h3>
							 <div class="photo_count">
							    <?php if(!empty($gal['user_link'])):?>  
							   <div><?=lang('Foto.photo');?>.: <a href="/<?=$gal['user_link'];?>" title="<?php if(!empty($gal['nick'])):?><?=esc($gal['nick']);?><?php elseif(!empty($gal['user_name']) and !empty($gal['user_surname'])):?><?=esc($gal['user_name']);?> <?=esc($gal['user_surname']);?><?php endif;?>"><b><?php if(!empty($gal['nick'])):?><?=$gal['nick'];?><?php elseif(!empty($gal['user_name']) and !empty($gal['user_surname'])):?><?=$gal['user_name'];?> <?=$gal['user_surname'];?><?php endif;?></b></a></div>
							 <?php endif;?>   
							   <div><svg viewBox="0 0 512 512" width="512"><path d="M460.22 150.06L389 112.11C383.567 109.205 379.024 104.881 375.854 99.5977C372.684 94.3148 371.007 88.2709 371 82.11C371.008 77.6326 370.133 73.1976 368.424 69.0589C366.716 64.9202 364.209 61.159 361.045 57.9907C357.881 54.8224 354.124 52.3091 349.987 50.5948C345.851 48.8804 341.417 47.9987 336.94 48H176.38C171.866 47.9987 167.395 48.8867 163.224 50.6134C159.053 52.3401 155.263 54.8715 152.07 58.0632C148.878 61.2549 146.345 65.0442 144.617 69.2149C142.889 73.3855 142 77.8556 142 82.37C142.003 88.4786 140.378 94.4776 137.294 99.7503C134.209 105.023 129.776 109.379 124.45 112.37L50.6299 153.82C41.3415 159.034 33.6089 166.627 28.2258 175.818C22.8428 185.01 20.0036 195.468 20 206.12V404C20 419.913 26.3213 435.174 37.5735 446.426C48.8257 457.679 64.087 464 80 464H432C447.913 464 463.174 457.679 474.426 446.426C485.678 435.174 492 419.913 492 404V203C491.998 192.124 489.041 181.453 483.443 172.128C477.845 162.803 469.818 155.175 460.22 150.06V150.06ZM256 407C236.42 407 217.279 401.194 200.999 390.315C184.718 379.437 172.029 363.976 164.536 345.886C157.043 327.796 155.082 307.89 158.902 288.686C162.722 269.482 172.151 251.842 185.996 237.996C199.842 224.151 217.482 214.722 236.686 210.902C255.89 207.082 275.796 209.043 293.886 216.536C311.976 224.029 327.437 236.718 338.315 252.999C349.194 269.279 355 288.42 355 308C355 334.256 344.57 359.437 326.003 378.004C307.437 396.57 282.256 407 256 407V407Z"></path></svg><b><?=$gal['number_of_photo'];?></b></div>
							 </div>
							</div>
                        <?php endif; ?>
                    </div>
                </div>
	 
	 
	 <?php endforeach;?>
  </div>
</div>
<?php endif;?>

  <?php if(!empty($data['gallery']['keywords'])):?>
  <div class="keywords">
    Tagi: <?php $list_key=explode(',',$data['gallery']['keywords']);if(!empty($list_key)):?><?php foreach($list_key as $b=>$key):?><?php if($b>0):?>, <?php endif;?><a href="/foto?search=<?=urlencode(esc($key));?>"><?=$key;?></a><?php endforeach;?><?php endif;?>
  </div>
  <?php endif; ?>
</div>
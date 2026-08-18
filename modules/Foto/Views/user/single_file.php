<div id="photo_single">
 <?php if(!empty($data['name'])):?> <h2 class="nag"><?=$data['name'];?></h2> <?php endif;?>
  <h3 class="nag"><?=lang('Foto.GalleryCreatedDate');?>: <b><?=date('d.m.Y H:i',strtotime($data['created_at']));?></b></h3>
  <div class="main_photo">
    <figure>
	  <a href="/image/original/<?=$data['path'];?>" title="<?=lang('Foto.ClickToZoom');?>" class="praca_zoom" target="_blank"><svg viewBox="0 0 512 512" width="512px"><path d="M448.3,424.7L335,311.3c20.8-26,33.3-59.1,33.3-95.1c0-84.1-68.1-152.2-152-152.2c-84,0-152,68.2-152,152.2  s68.1,152.2,152,152.2c36.2,0,69.4-12.7,95.5-33.8L425,448L448.3,424.7z M120.1,312.6c-25.7-25.7-39.8-59.9-39.8-96.3  s14.2-70.6,39.8-96.3S180,80,216.3,80c36.3,0,70.5,14.2,96.2,39.9s39.8,59.9,39.8,96.3s-14.2,70.6-39.8,96.3  c-25.7,25.7-59.9,39.9-96.2,39.9C180,352.5,145.8,338.3,120.1,312.6z"></path></svg></a>  
	  <img src="/image/original/<?=$data['path'];?>" alt="<?php if(!empty($data['caption'])):?><?=$data['caption'];?><?php endif;?>" />
	</figure>
	<?php if(!empty($data['description'])):?>
	  <div class="description"><?=$data['description'];?></div>
	<?php endif;?>
  </div>
  <div class="file_info">
    <div class="left">
	<?php if(!empty($data['nick']) or !empty($data['user_name']) or !empty($data['user_surname'])):?>
	<div class="line">
	    <div class="label"><?=lang('Foto.Author');?>:</div>
		<div class="value"><a href="/foto/g/user/<?=crc32($data['id_user']);?>" title="<?php if(!empty($data['nick'])):?><?=$data['nick'];?><?php else:?><?=$data['user_name'];?> <?=$data['user_surname'];?><?php endif;?>"><?php if(!empty($data['nick'])):?><?=$data['nick'];?><?php else:?><?=$data['user_name'];?> <?=$data['user_surname'];?><?php endif;?></a></div>
	  </div>
	<?php endif;?>  
	 <div class="line">
	    <div class="label"><?=lang('Foto.Category');?>:</div>
		<div class="value"><a href="/<?=$data['category']['link'];?>" title="<?=$data['category']['name'];?>"><?=$data['category']['name'];?></a></div>
	  </div>
	 <div class="line">
	    <div class="label"><?=lang('Foto.FileSize');?>:</div>
		<div class="value"><?=$data['size'];?></div>
	  </div>
	  <div class="line">
	    <div class="label"><?=lang('Foto.Dimension');?>:</div>
		<div class="value"><?=$data['dimensions'][0];?> x <?=$data['dimensions'][1];?></div>
	  </div>
	  <div class="line">
	    <div class="label"><?=lang('Foto.Views');?>:</div>
		<div class="value"><?=$data['views'];?></div>
	  </div>
	<?php if(!empty($data['keywords'])):?>
	  <div class="line">
	    <div class="label"><?=lang('Foto.Keywords');?>:</div>
		<div class="value">
		  <?php
		  $list_key=explode(',',$data['keywords']);
		  if(!empty($list_key)) {
			  foreach($list_key as $idk=>$val) {
				 if($idk>0) {echo ', ';} 
				 echo '<a href="/foto?search='.urlencode($val).'">'.$val.'</a>';
			  }
		  }
		  ?>
		</div>
	  </div>
	<?php endif;?>
	<p class="user_btn"><a href="/foto/g/user/<?=crc32($data['id_user']);?>" title="<?=lang('Foto.UserGallery');?> <?php if(!empty($data['nick'])):?><?=$data['nick'];?><?php else:?><?=$data['user_name'];?> <?=$data['user_surname'];?><?php endif;?>"><?=lang('Foto.UserGallery');?></p>
	</div>
	<div class="right"></div>
  </div>
 </div> 
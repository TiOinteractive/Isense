<?php
/* 
Mapa lokali
*/
?>
<section id="restaurants_map">
   <div class="container">
      <header class="filters">
		 <form method="get" id="filters">
		 	     <h1><?=$title;?></h1>
		 <?php if(!empty($data['categories'])):?>
		 <div>Rodzaj
		  <select name="type" onchange="this.form.submit()">
		    <option value=""><?=lang('Flavors.Choose');?></option>
		    <?php foreach($data['categories'] as $cat):?>
			  <option value="<?=$cat['id'];?>" <?php if(!empty($data['filters']['type']) and $data['filters']['type']==$cat['id']): $choosen_type=$cat['name']; ?>selected="selected"<?php endif;?>><?=$cat['name'];?></option>
			<?php endforeach; ?>
		  </select>
		 </div>
		  <?php endif;?>
		<?php if(!empty($data['cuisines'])):?>  
		 <div>Kuchnia
		  <select name="cuisine" onchange="this.form.submit()">
		    <option value=""><?=lang('Flavors.Choose');?></option>
		    <?php foreach($data['cuisines'] as $cat):?>
			  <option value="<?=$cat['id'];?>" <?php if(!empty($data['filters']['cuisine']) and $data['filters']['cuisine']==$cat['id']): $choosen_cuisine=$cat['name'];?>selected="selected"<?php endif;?>><?=$cat['name'];?></option>
			<?php endforeach; ?>
		  </select>
		 </div>
		 <?php endif;?>
		 <div>Znaleziono: <b><?php if(!empty($data['list']['restaurants'])):?><?=count($data['list']['restaurants']);?><?php else:?>0<?php endif;?></b> lokali</div>
		 <input name="letter" type="hidden" value="<?php if(!empty($data['filters']['letter'])):?><?=$data['filters']['letter'];?><?php endif;?>" /> 
		 </form>
	  </header>
	  <?php if(!empty($data['list']['letters'])):?>
			  <div class="letters" id="filter_letters">
			  <label>Lokale alfabetycznie:</label>
				<ul>
				  <?php foreach($data['list']['letters'] as $letter=>$count):?>
					<li<?php if(!empty($data['filters']['letter']) and $data['filters']['letter']==$letter):?> class="active"<?php endif;?>><?php if($count>0):?><a href="#" onclick="filterLetter('<?=$letter;?>');return false;"><?=$letter;?></a><?php else:?><span><?=$letter;?></span><?php endif;?></li>
				  <?php endforeach;?>
				</ul>
			  </div>
			<?php endif;?>
	      <?php if(!empty($data['filters']['letter']) or !empty($data['filters']['cuisine']) or !empty($data['filters']['type'])):?>
  <div class="choosen_filters">
    <div class="title"><h4><?=lang('Flavors.YourChoose');?>:</h4></div>
    <?php if(!empty($choosen_type)):?>
	  <div>rodzaj: <?=$choosen_type;?> <a href="#" onclick="Mapclear('type');return false;"><i class="fa-solid fa-xmark"></i></a></div>
	<?php endif;?>
	  <?php if(!empty($choosen_cuisine)):?>
	  <div>kuchnia: <?=$choosen_cuisine;?> <a href="#" onclick="Mapclear('cuisine');return false;"><i class="fa-solid fa-xmark"></i></a></div>
	<?php endif;?>
	 <?php if(!empty($data['filters']['letter'])):?>
	 <div><?=lang('Flavors.Letter');?>: <?=$data['filters']['letter'];?> <a href="#" onclick="clearLetter();return false;"><i class="fa-solid fa-xmark"></i></a></div>
	 <?php endif;?>
	 <div><?=lang('Flavors.ClearParameters');?> <a href="#" onclick="Mapclear('all');return false;"><i class="fa-solid fa-xmark"></i></a></div>
  </div>
  <?php endif;?> 
	  <div class="markers">
	    <?php if(!empty($data['list']['restaurants'])):?>
	        <?php foreach($data['list']['restaurants'] as $rest):?>
	         <div data-lat="<?=$rest['coordinates_array'][0];?>" data-long="<?=$rest['coordinates_array'][1];?>" data-marker="/<?=$rest['link'];?>" data-id="<?=$rest['id'];?>" data-ico="<?php if(!empty($rest['ico']['ico']['path'])):?>/image/r/35/35/<?=$rest['ico']['ico']['path'];?><?php endif;?>"></div>
	        <?php endforeach;?>
	    <?php endif;?>
	  </div>
   </div>
   <div id="map"></div>
   <div class="legends">
     <div class="container">
	   <?php if(!empty($data['list']['legend'])):?>
	      <div class="list">
	       <?php foreach($data['list']['legend'] as $legend):?>
              <div>
			    <figure><?php if(!empty($legend['ico']['path'])):?><img src="/image/r/60/60/<?=$legend['ico']['path'];?>" alt="<?=$legend['name'];?>" /><?php endif;?></figure>
				<h4><?=$legend['name'];?></h4>
			  </div>
           <?php endforeach;?>   
          </div>		   
	   <?php endif;?>
	 </div>
   </div>
</section>
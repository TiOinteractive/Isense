<section class="map-inside">	
<div class="title"><h2><span>KULINARNA MAPA</span> RZESZOWA</h2></div>  
	  <div class="markers">
	    <?php if(!empty($maps['restaurants'])):?>
	        <?php foreach($maps['restaurants'] as $rest):?>
	         <div data-lat="<?=$rest['coordinates_array'][0];?>" data-long="<?=$rest['coordinates_array'][1];?>" data-marker="/<?=$rest['link'];?>" data-id="<?=$rest['id'];?>" data-ico="<?php if(!empty($rest['ico']['ico']['path'])):?>/image/r/30/30/<?=$rest['ico']['ico']['path'];?><?php endif;?>"></div>
	        <?php endforeach;?>
	    <?php endif;?>
	  </div>
   <div id="map"></div>
</section>   
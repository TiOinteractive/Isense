<?php
/* 
Wyniki wyszukiwania
*/
?>
<?php if(!empty($data['list']['search'])):?>
<div id="cuisine-single" class="tags <?php if(!empty($data['page_content']['category']['filters']['view']) and $data['page_content']['category']['filters']['view']==2):?> view2<?php endif;?>">
  <header>
    <h1>Wyniki wyszukiwania dla: <span><?=$data['list']['search'];?></span>, Znaleziono <span><?=(count($data['list']['restaurants'])+count($data['list']['news']));?></span> wyników</h1>
  </header>
  <?php if(!empty($data['list']['restaurants'])):?>
  <h2>Lokale <span>(<?=count($data['list']['restaurants']);?>)</span></h2>
  	<?= view('\Modules\Flavors\Views/user/restaurants/restaurant_list', array('restaurants'=>$data['list']['restaurants'])); ?>
  <?php endif;?> 
  <?php if(!empty($data['list']['news'])):?>
  <h2>Aktualnośći <span>(<?=count($data['list']['news']);?>)</span></h2>
  	<?= view('\Modules\News/Views/user/list/flavor_list', array('data'=>array('list'=>$data['list']['news']),'title'=>'')); ?>
  <?php endif;?> 
 </div> 
 <?php endif;?>
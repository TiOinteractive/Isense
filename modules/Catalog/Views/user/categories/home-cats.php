<?php
/* 
Kategorie strona główna
*/
if(!empty($data['list'])):
?>
<section id="home-catalog">
 <div class="container">
  <div class="title resinet-title"><h2><?php if(!empty($url)):?><a href="<?=$url;?>"><?php endif;?><?=$title;?><?php if(!empty($url)):?></a><?php endif;?></h2></div>
  <div class="lists"> 
    <?php foreach($data['list'] as $item):?>
        <?php if(!empty($item['path']) && !empty($item['link']) && !empty($item['name'])):?>
            <div class="item">
                <figure><a href="/<?=$item['link'];?>"><img src="/image/c/380/380/<?=$item['path'];?>" alt="<?=esc($item['name']);?>"></a></figure>
                <h3><a href="/<?=$item['link'];?>"><?=$item['name'];?></a></h3>
            </div>
        <?php endif;?>  
    <?php endforeach;?>
  </div>
 </div>
</section>
<?php endif;?>
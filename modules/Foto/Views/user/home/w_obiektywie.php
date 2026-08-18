<?php
/*
Rzeszów w obiektywie
*/
if(!empty($data['list'])):
?>
<section id="home-w-obiektywie">
<div class="container"><div class="title resinet-title"><h2><a href="<?=$url;?>"><?=$title;?></a></h2></div></div>
   <div class="list">
     <?php foreach($data['list'] as $el):?>
	  <div>
	     <div class="caption">
			<h2><a href="/<?=$el['link'];?>"><?=$el['name'];?></a></h2>
		 </div>
	     <a href="/<?=$el['link'];?>">
		   <picture>
			<source srcset="/image/c/800/700/<?=$el['path']; ?>" media="(max-width: 800px)">
			<img src="/image/c/1600/780/<?=$el['path'];?>" alt="<?=$el['name'];?>">
		   </picture>
		 </a>
	  </div>
	 <?php endforeach;?>
   </div>
   <div class="thumbs">
     <ul class="list-thumbs">
	   <?php foreach($data['list'] as $k=>$el):?>
	    <li><figure><img src="/image/c/130/80/<?=$el['path'];?>" alt="<?=$el['name'];?>"></figure>
		<div class="line"><span></span></div>
		</li>
	   <?php endforeach;?>
	 </ul>
   </div>
</section>
<?php endif; ?>
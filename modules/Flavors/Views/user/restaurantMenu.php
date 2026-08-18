<?php if(!empty($menu)):?>
<div id="menu">
  <header><h3><?=lang('Flavors.Choose');?> <span><?=lang('Flavors.Restaurant');?></span> <i class="fa-solid fa-chevron-right"></i></h3></header>
   <nav id="cats" class="trans400">
        <ul>
		  <?php foreach($menu as $el): ?>
		    <li class="trans400<?php if(!empty($active) and $active==$el['id']):?> active<?php endif;?>">
			
			<figure>
			 <?php if(!empty($el['svg'])):?><a href="/<?=$el['link'];?>" class="item" title="<?=esc($el['name']);?>"><?=$el['svg'];?></a><?php endif;?>
			</figure>
				<a href="/<?=$el['link'];?>" class="item" title="<?=esc($el['name']);?>"><?=$el['name'];?></a>
			</li>
		  <?php endforeach; ?>
		  <li class="all trans400"><figure>
		  
		  <a href="/rzeszowskie-smaki/lokale" title="<?=lang('Flavors.All');?>"><svg viewBox="0 0 24 24" fill="none"><path d="M2 5H4.009V7H2V5Z" fill="#7a7a7a"/><path d="M6 5H22V7H6V5Z" fill="#7a7a7a"/><path d="M2 11H4.009V13H2V11Z" fill="#7a7a7a"/><path d="M6 11H22V13H6V11Z" fill="#7a7a7a"/><path d="M2 17H4.009V19H2V17Z" fill="#7a7a7a"/><path d="M6 17H22V19H6V17Z" fill="#7a7a7a"/></svg></a>
		  </figure><a href="/rzeszowskie-smaki/lokale" title="<?=lang('Flavors.All');?>"><?=lang('Flavors.All');?></a></li>
		</ul>
   </nav>		
</div>
<?php endif;?>

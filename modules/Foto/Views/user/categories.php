<h2><?=lang('Foto.Categories');?></h2>
<?php if(!empty($pages)):?>
  <div class="list">
   <ul>
   <?php foreach($pages as $page):?>
       <li>
	     <a href="/<?=$page['link'];?>" title="<?=$page['name'];?>" class="level_1<?php if(!empty($active_id) and $active_id==$page['id']):?> active<?php endif;?>"><?=$page['name'];?></a>
		 <?php if(!empty($page['list'])):?>
		   <ul>
				<?php foreach($page['list'] as $subpage):?>
					<li class="level_2<?php if(!empty($active_id) and $active_id==$subpage['id']):?> active<?php endif;?>"><div><a href="/<?=$subpage['link'];?>" title="<?=$subpage['name'];?>"><?=$subpage['name'];?></a></div><div><span><?=$subpage['count'];?></span></div></li>
				<?php endforeach;?>
		   </ul>
		 <?php endif;?>
	   </li>
    <?php endforeach;?>
	</ul>
   </div>
<?php endif;?>
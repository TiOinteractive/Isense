<?php if(!empty($cuisineList)):?>
<div id="cuisineMenu">
<header><h3><?=lang('Flavors.Choose');?> <span><?=lang('Flavors.ChooseCuisine');?></span> <i class="fa-solid fa-chevron-right"></i></h3></header>
 <div class="inside trans400">
 <ul>
    <?php foreach($cuisineList as $cuisine):?>
		<?php if($cuisine['count']>0): ?>
			<li<?php if(!empty($active) and $active==$cuisine['id']):?> class="active"<?php endif;?>><?php if(!empty($cuisine['photo']['path'])):?><figure><a href="/<?=$cuisine['link'];?>" title="<?=$cuisine['name'];?>"><img src="/image/r/19/19/<?=$cuisine['photo']['path'];?>" alt="<?=$cuisine['photo']['caption'] ? $cuisine['photo']['caption'] : $cuisine['name']; ?>" /></a></figure><?php elseif(!empty($cuisine['ico_svg'])):?><figure><a href="/<?=$cuisine['link'];?>" title="<?=$cuisine['name'];?>"><?=$cuisine['ico_svg'];?></a></figure><?php endif;?> <a href="/<?=$cuisine['link'];?>" title="<?=$cuisine['name'];?>"><?=$cuisine['name'];?></a></li>
		<?php  endif;  ?>
	<?php endforeach;?>
  </ul>
  </div>
</div>
<?php endif;?>
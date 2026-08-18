<?php if(!empty($bread)): ?>	
  <div class="container bread">
	<ul itemscope itemtype="https://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a class="a" itemprop="item" href="/<?=$settings['url_flavors'];?>/<?=$locale;?>"><span itemprop="name"><?=lang('User.other.HomePage');?></span></a>
        <meta itemprop="position" content="1" />
      </li>
	  <?php foreach($bread as $k=>$br): ?>	
            <?php if($br['link']!='/'.$settings['url_flavors']):?>
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<span class="arrow"> /</span>
                <?php if(!empty($br['link'])): ?><a class="a" itemprop="item" href="<?=$br['link'];?>"><?php else: ?><span class="a"><?php endif; ?>
                    <span itemprop="name"><?=!empty($br['name']) ? $br['name'] : '';?></span>
                <?php if(!empty($br['link'])): ?></a><?php else: ?></span><?php endif; ?>
                <meta itemprop="position" content="<?=($k+1);?>" />
            </li>	
		<?php endif;?>	
        <?php endforeach; ?>
    </ul>
	</div>
<?php endif; ?>
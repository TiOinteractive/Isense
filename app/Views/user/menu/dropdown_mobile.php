<?php if(!empty($menu)): ?>
    <div class="drop-mobile trans400">
	  <div id="white">
	    <div class="inside">
		    <?php foreach($menu as $k=>$item):?>
		       <div class="item<?php if(!empty($item['submenu'])):?> has-submenu<?php endif; ?>">
		         <h3><a href="<?=$item['url'];?>"><?=$item['name'];?></a> <?php if(!empty($item['submenu'])):?><span><i class="fa-solid fa-chevron-right"></i></span><?php endif; ?></h3>
				<?php if(!empty($item['submenu'])):?>
                   <ul class="trans400">
                   <?php foreach($item['submenu'] as $sub):?>
                       <li><a href="<?=$sub['url'];?>"><?=$sub['name'];?></a></li>
				   <?php endforeach;?>
					</ul>
				<?php endif; ?>
		      </div> 
		   <?php endforeach; ?>
		</div>
	  </div>
	</div>
<?php endif; ?>	
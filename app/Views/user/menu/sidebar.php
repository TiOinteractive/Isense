<?php if (!empty($menu)): ?>	   
    <div class="sidebar-menu">
        <h3><?=lang('Event.user.Places'); ?></h3>
        <div class="menu">
            <?php foreach ($menu as $men): ?>		
                <div class="menu-item"> 
                    <a href="<?= $men['url']; ?>" title="<?= $men['title']; ?>"><span class="ico"><?= $men['svg']; ?></span><span class="name"><?= $men['name']; ?></span></a>	
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="clear"></div>
<?php endif; ?>
<?php 
if(!empty($menu)): ?>
    <div class="home-gastro-menu">
            <div class="menu">
                <?php foreach($menu as $m): ?>
                    <div class="menu-item<?=isset($m['active']) && $m['active'] ? ' active' : ''; ?>">
                        <a href="<?=$m['url']; ?>" title="<?=esc($m['title']); ?>" <?php if($m['target']){echo ' target="'.$m['target'].'" ';}?>>
                            <?php if($m['photo']): ?>
                                <img src="/image//c/400/143/<?=$m['photo']['path']; ?>" alt="<?=$m['name']; ?>">
                            <?php endif; ?>
                            <span class="name trans400"><?=$m['name']; ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
	</div>
<?php endif; ?>	
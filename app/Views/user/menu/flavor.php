<?php 
if(!empty($menu)): ?>
    <div class="main-menu">
            <div class="menu">
                <?php foreach($menu as $m): ?>
                    <div class="m-<?=$m['id'];?> menu-item<?=isset($m['active']) && $m['active'] ? ' active' : ''; ?>">
                        <a href="<?=$m['url']; ?>" title="<?=esc($m['title']); ?>" <?php if($m['target']){echo ' target="'.$m['target'].'" ';}?>>
                            <?php if($m['svg']): ?>
                               <figure> <?=$m['svg'];?></figure>
                            <?php endif; ?>
                            <span class="name"><?=$m['name']; ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
	</div>
<?php endif; ?>	
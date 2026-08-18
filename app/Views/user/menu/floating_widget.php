<?php 
if(!empty($menu)): ?>
    <div class="floating-menu">
        <div class="menu">
            <?php foreach($menu as $m): ?>
                <div class="menu-item menu-item-<?=$m['id']; ?><?=!empty($m['class']) ? ' ' . $m['class'] : ''; ?><?=isset($m['active']) && $m['active'] ? ' active' : ''; ?>">
                    <a href="<?=$m['url']; ?>" title="<?=esc($m['title']); ?>" <?php if($m['target']){echo ' target="'.$m['target'].'" ';}?>>
                        <?php if(!empty($m['svg'])): ?>
                            <span class="ico"><?=$m['svg']; ?></span>
                        <?php endif; ?>
                        <span class="name trans400"><?=$m['name']; ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
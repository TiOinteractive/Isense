<?php 
if(!empty($menu)): ?>
    <div class="catalog-menu">
        <div class="container">
            <div class="menu">
                <?php foreach($menu as $m): ?>
                    <div class="menu-item<?=isset($m['active']) && $m['active'] ? ' active' : ''; ?>">
                        <div class="ico">
                            <?php if(!empty($m['svg'])): ?>
                                <a href="<?=$m['url']; ?>" title="<?=esc($m['title']); ?>" <?php if($m['target']){echo ' target="'.$m['target'].'" ';}?>><?=$m['svg']; ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="value">
                            <a href="<?=$m['url']; ?>" title="<?=esc($m['title']); ?>" <?php if($m['target']){echo ' target="'.$m['target'].'" ';}?>><?=$m['name']; ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php 
if(!empty($menu)): ?>
    <div class="main-menu">
        <div class="container">
            <div class="menu">
                <?php foreach($menu as $m): ?>
                    <div class="menu-item menu-item-<?=$m['id']; ?><?=isset($m['active']) && $m['active'] ? ' active' : ''; ?>">
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
        <?php if(!empty($external_submenu)): ?>
            <div class="external-submenu">
                <div class="container">
                    <div class="submenu">
                        <?php foreach($external_submenu as $m): ?>
                            <div d="<?=$m['id_parent_active']; ?>" class="menu-item menu-item-<?=$m['id']; ?><?=isset($m['active']) && $m['active'] ? ' active' : ''; ?>">
                                <a href="<?=$m['url']; ?>" title="<?=esc($m['title']); ?>" <?php if($m['target']){echo ' target="'.$m['target'].'" ';}?>><?=$m['name']; ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php elseif(!empty($page['id_page']) and $page['id_page']==1 and !empty($menu[7]['submenu'])): ?>

            <div class="external-submenu external-home">
                <div class="container">
                    <div class="submenu">
					    <div class="menu-item item-name"><span><?=$menu[7]['name'];?>:</span></div>
                        <?php foreach($menu[7]['submenu'] as $m): ?>
                            <div class="menu-item menu-item-<?=$m['id']; ?><?=isset($m['active']) && $m['active'] ? ' active' : ''; ?>">
                                <a href="<?=$m['url']; ?>" title="<?=esc($m['title']); ?>" <?php if($m['target']){echo ' target="'.$m['target'].'" ';}?>><?=$m['name']; ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
		
        <?php endif; ?>
    </div>
<?php endif; ?>
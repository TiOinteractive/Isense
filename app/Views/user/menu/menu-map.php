<?php 
if(!empty($menu)): ?>
        <div class="menu trans800" id="map">
		 <div class="container">
		 <h3>Mapa portalu RESinet.pl</h3>
            <ul class="flex">
                <?php foreach($menu as $m): ?>
                <li<?php if(isset($m['active']) && $m['active']==1) { echo ' class="active"';}?>>
                    <h4><a href="<?=$m['url']; ?>" title="<?=esc($m['title']); ?>" <?php if($m['target']){echo ' target="'.$m['target'].'" ';}?>><?=$m['name']; ?></a></h4>
                    <?php if(!empty($m['submenu'])): ?>
                        <ul>
                            <?php foreach($m['submenu'] as $s): ?>
                                <li<?php if(isset($s['active']) && $s['active']==1) { echo ' class="active"';}?>>
                                    <h5><a href="<?=$s['url']; ?>" title="<?=esc($s['title']); ?>" <?php if($s['target']){echo ' target="'.$s['target'].'"';}?>><?=$s['name']; ?></a></h5>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
		  </div>
        </div>
<?php endif; ?>
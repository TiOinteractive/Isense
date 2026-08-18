<?php if(!empty($types)): ?>
    <div class="place-types-with-home-places">
        <div class="container">
            <h2><?php if(!empty($page)): ?><a href="/<?=$page['link']; ?>" title="<?=esc($page['name']); ?>"><?=$page['name']; ?></a><?php else: ?><?=lang('User.entertainment.Places'); ?><?php endif; ?></h2>
            <div class="list">
                <?php foreach($types as $type): ?>
                    <div class="type-box">
                        <h3><a href="/<?=$type['link']; ?>" title="<?=esc($type['name']); ?>"><?=$type['name']; ?></a></h3>
                        <?php if(!empty($type['places'])): ?>
                            <ul class="places">
                                <?php foreach($type['places'] as $place): ?>
                                    <li><a href="/<?=$place['link']; ?>" title="<?=esc($place['name']); ?>"><?=$place['name']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

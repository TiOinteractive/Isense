<div class="event-types-filter-box">
    <h3><?=lang('Event.user.Categories'); ?></h3>
    <?php if (!empty($data['types'])): ?>
        <?php foreach ($data['types'] as $type): ?>
            <div class="type<?=!empty($data['filters']) && !empty($data['filters']['t']) && $data['filters']['t'] == $type['slug'] ? ' active' : ''; ?>" data-slug="<?=$type['slug']; ?>">
                <div class="ico"><?=$type['svg']; ?></div>
                <div class="name"><?=!empty($type['name2']) ? $type['name2'] : $type['name']; ?></div>
                <div class="count"><?php if(isset($type['count'])): ?>(<?=$type['count']; ?>)<?php endif; ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
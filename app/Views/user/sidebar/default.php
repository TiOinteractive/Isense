<?php


?>
<div class="sidebar sidebar-<?=$id_sidebar; ?>">
    <?php if(!empty($content)): ?>
        <?php foreach($content as $cont): ?>
            <?php if(!empty($cont) && !empty($cont['template'])): ?>
                <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

 
<?php if(!empty($flashdata)):?>
    <div class="alert-box <?= $flashdata['status'] ? 'success' : 'error'; ?>">
        <p><?=$flashdata['msg']; ?></p>
        <?php if(!empty($flashdata['list'])): ?>
            <ul>
                <?php foreach($flashdata['list'] as $l): ?>
                    <?php if(is_array($l)): ?>
                        <?php foreach($l as $i): ?>
                            <li><?=$i; ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><?=$l; ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <button class="close"><i class="fas fa-times"></i></button>
    </div>
<?php endif; ?>
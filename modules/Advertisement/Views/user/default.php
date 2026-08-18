<?php if(!empty($data)): ?>
    <div class="aa-zone zone-<?=$data['id']; ?> type-<?=$data['type'] ? $data['type'] : 'default'; ?> source-<?=$data['source'] ? $data['source'] : 'default'; ?>">
        <div class="aa-zone-container">
            <div class="aa-center">
                <span class="t"><?=lang('Advertisement.user.Advertisement'); ?></span>
                <?php if(!empty($data['code'])): ?><?=$data['code']; ?><?php endif; ?>
                <?php if(!empty($data['photo'])): ?>
                    <img src="/image/<?=$data['photo']['path']; ?>" alt="" />
                <?php endif; ?>
                <?php if(!empty($data['url']) && !empty($data['hash'])): ?>
                    <a class="aa-zone-url" href="<?= $locale ? '/' . $locale : ''; ?>/redirect/aa/<?=$data['hash']; ?>" title="" target="_blank" rel="nofollow"></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
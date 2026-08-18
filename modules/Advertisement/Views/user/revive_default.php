<?php if(!empty($data)): ?>
    <div class="aa-zone zone-<?=$data['id']; ?> type-<?=$data['type'] ? $data['type'] : 'default'; ?> source-<?=$data['source'] ? $data['source'] : 'default'; ?> external-<?=$data['external_id'] ? $data['external_id'] : '0'; ?>">
        <div class="aa-zone-container">
            <div class="aa-center">
                <span class="t"><?=lang('Advertisement.user.Advertisement'); ?></span>
                <ins data-revive-zoneid="<?=$data['external_id']; ?>" data-revive-id="67887929e6f9d4968c6bd5f4e2bfb4bd"></ins>
            </div>
        </div>
    </div>
<?php endif; ?>
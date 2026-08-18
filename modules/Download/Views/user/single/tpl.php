<?php
/* 
Download tpl
*/
?>
<section class="<?php if(!empty($id_cont)): ?>section-<?=$id_cont; ?><?php endif; ?>">
    <div class="container">
        <?php if(!empty($title)): ?>
            <h2><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
        <?php if(!empty($data) && !empty($data['files'])): ?>
            <div class="download download-<?=$data['id']; ?>">
                <?php foreach($data['files'] as $file): ?>
                    <div class="file">
                        <a href="/download/<?=$file['id_dfl']; ?>/<?=$file['path']; ?>" title="<?=esc($file['caption']); ?>"><?=$file['caption']; ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
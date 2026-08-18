<?php
/*
  Download list tpl
 */
$z = 0;
?>
<section class="section-<?= $id_cont; ?> download-tabs">
    <div class="container">
        <?php if (!empty($title)): ?>
            <h2><?= $title; ?></h2>
        <?php endif; ?>
        <?php if (!empty($subtitle)): ?>
            <h3><?= $subtitle; ?></h3>
        <?php endif; ?>
        <?php if (!empty($data) && !empty($data['list'])): ?>
            <div class="download-list">
                <div class="header-cat">
                    <ul>
                        <?php foreach ($data['list'] as $cat): ?>
                            <?php if (!empty($cat['files'])): $z++; ?>
                            <li class="cat-<?= $cat['id']; ?><?php if($z==1):?> active<?php endif;?>"><h3 class="trans400" rel="<?= $cat['id']; ?>" ><?= $cat['name']; ?></h3></li>
                            <?php endif; ?>	
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="file-body">
                    <?php
                    $z = 0;
                    foreach ($data['list'] as $cat):
                        ?>
                            <?php if (!empty($cat['files'])): $z++; ?>
                            <div class="trans400 download-category download-category-<?= $cat['id']; ?><?php if($z==1):?> active<?php endif;?>">
            <?php foreach ($cat['files'] as $file): ?>
                                    <div class="file">
                                        <div class="name">
                                            <a href="/download/<?= $file['id_dfl']; ?>/<?= $file['path']; ?>" title="<?= esc($file['caption'] ? $file['caption'] : $file['name']); ?>"><?= $file['caption'] ? $file['caption'] : $file['name']; ?></a>
                                        </div>
                                        <div class="size"><?php
                                            if (isset($file['file_size'])) {
                                                if ($file['file_size'] < 1024) {
                                                    echo "<span>" . $file['file_size'] . " b</span>";
                                                } elseif ($file['file_size'] < 1048576) {
                                                    $size_kb = round($file['file_size'] / 1024);
                                                    echo "<span>" . $size_kb . " KB</span>";
                                                } else {
                                                    $size_mb = round($file['file_size'] / 1048576, 1);
                                                    echo "<span>" . $size_mb . " MB</span>";
                                                }
                                            }
                                            ?>	</div>
                                        <div class="svg"><a href="/download/<?= $file['id_dfl']; ?>/<?= $file['path']; ?>" title="<?= esc($file['caption'] ? $file['caption'] : $file['name']); ?>">
                                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title/><g data-name="Layer 2" id="Layer_2"><path d="M14.41,2H4V22H20V7.59ZM12,18.41l-3.71-3.7,1.42-1.42L11,14.59V10h2v4.59l1.29-1.3,1.42,1.42ZM15,7V5.41L16.59,7Z"/></g></svg>

                                            </a></div>
                                    </div>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
    <?php endforeach; ?>					
                </div>
            </div>
<?php endif; ?>
    </div>
</section>
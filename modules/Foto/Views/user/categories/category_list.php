<?php
/*
  Category list
 */
/*
  echo '<pre>';
  print_r($data);
  echo '</pre>';
 */
?>
<?php if (!empty($data['categories_list'])): ?>
    <div id="category_single">  
        <h1 class="title">Kategorie zdjęć</h1>
        <div class="cat-foto-list">
            <?php foreach ($data['categories_list'] as $cat): ?>
                <div class="cat-main">
                    <h2><a href="/<?= $cat['link']; ?>" title="<?= esc($cat['name']); ?>"><?= $cat['name']; ?></a></h2>
                    <?php if (!empty($cat['list'])): ?>
                        <ul>
                            <?php foreach ($cat['list'] as $subcat): ?>
                                <li><h3><a href="/<?= $subcat['link']; ?>" title="<?= esc($subcat['name']); ?>"><?= $subcat['name']; ?></a> <span><?= $subcat['count']; ?></span></h3></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>	  
    </div>
<?php elseif (!empty($data['category']['categoryPhotoCatList'])): ?>
    <div id="category_list">  
        <h1><?= $data['category']['name']; ?></h1>

        <div id="subcat_list">
            <?php foreach ($data['category']['categoryPhotoCatList'] as $podkat): ?>
                <?php if ($podkat['number_photo'] > 0): ?>
                    <div class="box">
                        <h2 class="nag"><a href="/<?= $podkat['link']; ?>" title="<?= $podkat['name']; ?>"><?= $podkat['name']; ?></a> <span>(<?= $podkat['number_photo']; ?>)</span> <a href="/<?= $podkat['link']; ?>" class="right"><?= lang('Foto.ViewMore'); ?> »</a></h2>
                        <div class="list">   
                            <?php if (!empty($podkat['photos'])): foreach ($podkat['photos'] as $photo): ?>
                                    <div class="box_foto">
                                        <picture>
                                            <a href="/<?= $podkat['link']; ?>"><img src="/image/c/300/300/<?= $photo['path']; ?>" alt="<?php if (!empty($photo['caption'])): ?><?= $photo['caption']; ?><?php else: ?><?= $photo['name']; ?><?php endif; ?>  - <?= $data['category']['name']; ?>, <?= $podkat['name']; ?>" border="0"></a>
                                        </picture>  
                                        <div class="podpis">
                                            <h3><a title="<?php if (!empty($photo['caption'])): ?><?= $photo['caption']; ?><?php else:?><?= $photo['name']; ?><?php endif; ?> - <?= $data['category']['name']; ?>, <?= $podkat['name']; ?>" href="/<?= $podkat['link']; ?>"><?= $photo['name']; ?></a></h3>
                                            <div class="foto_count">
                                                <?php if (!empty($photo['user_info']['user_link'])): ?><div>fot.: <a href="/<?= $photo['user_info']['user_link']; ?>" title="ViC"><b><?php if (!empty($photo['user_info']['nick'])): ?><?= $photo['user_info']['nick']; ?><?php elseif (!empty($photo['user_info']['name']) and!empty($photo['user_info']['surname'])): ?><?= $photo['user_info']['name']; ?> <?= $photo['user_info']['surname']; ?><?php endif; ?></b></a></div><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>	
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </div>   
                    </div> 
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <div id="category_single">  
        <div class="container">  
            <h1 class="title"><?= $data['category']['name']; ?></h1>

            <div class="list">
                <?php if (!empty($data['category']['gallery'])): ?>
                    <?php foreach ($data['category']['gallery'] as $gal): ?>
                        <div class="photo-item gal-item item-<?= $gal['id']; ?>">
                            <div class="photo">
                                <?php if ($gal['photo']): ?>
                                    <picture>    
                                        <a href="/<?= $gal['link']; ?>" title="<?= esc($gal['name']); ?>">
                                            <source srcset="/image/c/500/150/<?= $gal['photo']; ?>" media="(max-width: 800px)">
                                            <img src="/image/c/740/490/<?= $gal['photo']; ?>" alt="Rzeszów - <?= esc($gal['name']); ?>" class="trans400" />
                                        </a>	
                                    </picture>
                                    <div class="info">
                                        <h3><a href="/<?= $gal['link']; ?>" title="<?= esc($gal['name']); ?>"><?= $gal['name']; ?></a></h3>
                                        <div class="photo_count">
                                            <?php if (!empty($gal['user_link'])): ?>
                                                <div><?= lang('Foto.photo'); ?>.: <a href="/<?= $gal['user_link']; ?>" title="<?php if (!empty($gal['nick'])): ?><?= esc($gal['nick']); ?><?php elseif (!empty($gal['user_name']) and!empty($gal['user_surname'])): ?><?= esc($gal['user_name']); ?> <?= esc($gal['user_surname']); ?><?php endif; ?>"><b><?php if (!empty($gal['nick'])): ?><?= $gal['nick']; ?><?php elseif (!empty($gal['user_name']) and!empty($gal['user_surname'])): ?><?= $gal['user_name']; ?> <?= $gal['user_surname']; ?><?php endif; ?></b></a></div>
                                            <?php endif; ?> 
                                            <div><svg viewBox="0 0 512 512" width="512"><path d="M460.22 150.06L389 112.11C383.567 109.205 379.024 104.881 375.854 99.5977C372.684 94.3148 371.007 88.2709 371 82.11C371.008 77.6326 370.133 73.1976 368.424 69.0589C366.716 64.9202 364.209 61.159 361.045 57.9907C357.881 54.8224 354.124 52.3091 349.987 50.5948C345.851 48.8804 341.417 47.9987 336.94 48H176.38C171.866 47.9987 167.395 48.8867 163.224 50.6134C159.053 52.3401 155.263 54.8715 152.07 58.0632C148.878 61.2549 146.345 65.0442 144.617 69.2149C142.889 73.3855 142 77.8556 142 82.37C142.003 88.4786 140.378 94.4776 137.294 99.7503C134.209 105.023 129.776 109.379 124.45 112.37L50.6299 153.82C41.3415 159.034 33.6089 166.627 28.2258 175.818C22.8428 185.01 20.0036 195.468 20 206.12V404C20 419.913 26.3213 435.174 37.5735 446.426C48.8257 457.679 64.087 464 80 464H432C447.913 464 463.174 457.679 474.426 446.426C485.678 435.174 492 419.913 492 404V203C491.998 192.124 489.041 181.453 483.443 172.128C477.845 162.803 469.818 155.175 460.22 150.06V150.06ZM256 407C236.42 407 217.279 401.194 200.999 390.315C184.718 379.437 172.029 363.976 164.536 345.886C157.043 327.796 155.082 307.89 158.902 288.686C162.722 269.482 172.151 251.842 185.996 237.996C199.842 224.151 217.482 214.722 236.686 210.902C255.89 207.082 275.796 209.043 293.886 216.536C311.976 224.029 327.437 236.718 338.315 252.999C349.194 269.279 355 288.42 355 308C355 334.256 344.57 359.437 326.003 378.004C307.437 396.57 282.256 407 256 407V407Z"></path></svg><b><?= $gal['number_of_photo']; ?></b></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if (!empty($data['category']['photos'])): ?>
                    <?php foreach ($data['category']['photos'] as $gal): ?>  
                        <div class="photo-item item-<?= $gal['id']; ?>">
                            <div class="photo">
                                <?php if ($gal['photo']): ?>
                                    <picture>
                                        <a href="/<?= $gal['link']; ?>" title="<?= esc($gal['name']); ?>">								
                                            <source srcset="/image/c/400/400/<?= $gal['photo']; ?>" media="(max-width: 800px)">
                                            <img src="/image/c/350/350/<?= $gal['photo']; ?>" alt="<?= esc($gal['name']); ?>" class="trans400" />
                                        </a>	
                                    </picture>
                                    <div class="info">
                                        <?php if (!empty($gal['name'])): ?><h3><a href="/<?= $gal['link']; ?>" title="<?= esc($gal['name']); ?>"><?= $gal['name']; ?></a></h3><?php endif; ?>
                                    </div>
                                <?php endif; ?>		
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            </div>		 
            <?php if (!empty($data['category']['pager'])): ?>
                <?= $data['category']['pager']; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
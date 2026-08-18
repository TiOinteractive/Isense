<?php
/* 
News tpl
*/
?>
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 2, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<section class="news-single news-<?=$data['id'];?>">
    <div class="container">
        <?php if(!empty($data['subtitle']) || !empty($data['title'])): ?>
            <h2 class="name"><?=!empty($data['subtitle']) ? $data['subtitle'] : $data['title']; ?></h2>
        <?php endif; ?>
      <div class="column">
	    <?php if(!empty($data['photo']) && !$mobile): ?>
			<?php 
			  $data['photo_method']='c';
			  $data['photo_big']=$data['photo']['path'];
			  if(!empty($data['photo']['crop_dimension'])) {
					$data['crop_dimension']=json_decode($data['photo']['crop_dimension']); 
					$data['photo']['path']=$data['crop_dimension']->width.'/'.$data['crop_dimension']->height.'/'.$data['crop_dimension']->x.'/'.$data['crop_dimension']->y.'/'.$data['photo']['path'];
					$data['photo_method']='r';
				}	
			?>
                <div class="main-photo">
                    <a href="/image/r/1920/1080/<?=$data['photo_big']; ?>"  title="<?=esc($data['photo']['caption'] ? $data['photo']['caption']: $data['title']); ?>" rel="lightbox">
                        <picture>
                            <source srcset="/image/<?=$data['photo_method'];?>/460/300/<?=$data['photo']['path']; ?>" media="(max-width: 800px)">
                            <img src="/image/<?=$data['photo_method'];?>/800/520/<?=$data['photo']['path']; ?>" alt="<?=esc($data['photo']['caption']); ?>" />
                        </picture>
                    </a>
                </div>
            <?php endif; ?>
                <?php if(!empty($data['content'])): ?>
                <div class="content"><?=$data['content']; ?></div>
            <?php endif; ?>
                <?php if(!empty($data['source'])): ?>
                    <div class="news-source"><?=lang('News.user.Source'); ?> <?=$data['source']; ?></div>
                <?php endif; ?>
        </div>
        <div class="photos">
            <?php if(!empty($data['photo']) && $mobile): ?>
			<?php 
			  $data['photo_method']='crop';
			  $data['photo_big']=$data['photo']['path'];
			  if(!empty($data['photo']['crop_dimension'])) {
					$data['crop_dimension']=json_decode($data['photo']['crop_dimension']); 
					$data['photo']['path']=$data['crop_dimension']->width.'/'.$data['crop_dimension']->height.'/'.$data['crop_dimension']->x.'/'.$data['crop_dimension']->y.'/'.$data['photo']['path'];
					$data['photo_method']='resize';
				}	
			?>
                  <div class="photo">
                    <a href="/image/c/1920/1080/<?=$data['photo_big']; ?>" data-thumb="/image/c/120/70/<?=$data['photo']['path'];?>" title="<?=esc($data['photo']['caption'] ? $data['photo']['caption']: $data['title']); ?>"  rel="lightbox">
                        <picture>
                            <source srcset="/image/<?=$data['photo_method'];?>/460/300/<?=$data['photo']['path']; ?>" media="(max-width: 800px)">
                            <img src="/image/<?=$data['photo_method'];?>/800/520/<?=$data['photo']['path']; ?>" alt="<?=esc($data['photo']['caption']); ?>" />
                        </picture>
                    </a>
                </div>
            <?php endif;?>
            <?php if(!empty($data['photos'])): ?>
                <?php foreach($data['photos'] as $photo): ?>
                    <div class="photo">
                        <a href="/image/c/1920/1080/<?=$photo['path']; ?>" data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['title']); ?>"  rel="lightbox">
                            <picture>
                                <source srcset="/image/c/460/300/<?=$photo['path']; ?>" media="(max-width: 800px)">
                                <img src="/image/c/800/520/<?=$photo['path']; ?>" alt="<?=esc($photo['caption']); ?>" />
                            </picture>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if(!empty($data['audio'])): ?>
            <div class="audio-list">
                <?php foreach($data['audio'] as $audio): ?>
                <div class="audio">
                    <audio controls>
                        <source src="/audio/<?=$audio['path']; ?>" type="<?=$audio['mime']; ?>">
                        Your browser does not support the audio tag.
                    </audio>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if(!empty($data['video'])): ?>
            <div class="video-list">
                <?php foreach($data['video'] as $video): ?>
                <div class="video">
                    <video width="320" height="240" controls>
                        <source src="/video/<?=$video['path']; ?>" type="<?=$video['mime']; ?>">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

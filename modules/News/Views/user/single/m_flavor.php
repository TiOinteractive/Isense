<?php
/* 
News - Smaki
*/
?>
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header_flavor.php') ? 'user/m_header_flavor' : 'user/header_flavor'); ?>
<?php
    if(isset($breadcrumbs)) {
        echo view('user/breadcrumbs_flavor',array('bread'=>$breadcrumbs));
    }
?>
<div id="flavors">
<section class="news-single news-<?=$data['id'];?> entertainment-news news-view-1">
    <div class="container">
        <?php if(!empty($data['subtitle']) || !empty($data['title'])): ?>
            <h1 class="name"><?=!empty($data['subtitle']) ? $data['subtitle'] : $data['title']; ?></h1>
            <?php if(!empty($data['date']) || !empty($data['author'])): ?>
                <p class="date-author"><?=!empty($data['date']) ? lang('User.entertainment.Added') . ': ' . date('d.m.Y', strtotime($data['date'])) . ' ' . lang('User.entertainment.YearShort') : ''; ?><?=!empty($data['date']) && !empty($data['author']) ? ' / ' : ''; ?><?=!empty($data['author']) ? $data['author'] : ''; ?></p>
            <?php endif; ?>
        <?php endif; ?>
      <div class="column">
	    <?php if(!empty($data['photo'])): ?>
		  <?php 
			  $data['photo_method']='c';
			  $data['photo_big']=$data['photo']['path'];
			  if(!empty($data['photo']['crop_dimension'])) {
					$data['crop_dimension']=json_decode($data['photo']['crop_dimension']); 
					$data['photo']['path']=$data['crop_dimension']->width.'/'.$data['crop_dimension']->height.'/'.$data['crop_dimension']->x.'/'.$data['crop_dimension']->y.'/'.$data['photo']['path'];
					$data['photo_method']='r';
				}	
			?>
                <div class="photo">
                    <a href="/image/r/1920/1080/<?=$data['photo_big']; ?>"  data-thumb="/image/c/120/70/<?=$data['photo']['path'];?>" title="<?=esc($data['photo']['caption'] ? $data['photo']['caption']: $data['title']); ?>" rel="lightbox">
                        <picture>
                            <source srcset="/image/<?=$data['photo_method'];?>/460/300/<?=$data['photo']['path']; ?>" media="(max-width: 800px)">
                            <img src="/image/<?=$data['photo_method'];?>/1260/600/<?=$data['photo']['path']; ?>" alt="<?=esc($data['photo']['caption']); ?>" />
                        </picture>
                    </a>
                    <?php if(!emptY($data['photo']['caption'])): ?>
                        <div class="photo-caption">
                            <?=$data['photo']['caption']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="entertainment-row">
            <div class="col col-content">
                <?php if(!empty($data['content'])): ?>
                    <div class="content"><?=$data['content']; ?></div>
                <?php endif; ?>
                <?php if(!empty($data['source'])): ?>
                    <div class="news-source"><?=lang('News.user.Source'); ?> <?=$data['source']; ?></div>
                <?php endif; ?>
                <?php if(!empty($data['comment'])): ?>
                    <?= view_cell('\Modules\Comments\Libraries\Comments::showCommentsForm', ['id_lang' => $id_lang, 'locale' => $locale, 'id_link' => $data['id_link']]); ?>
                <?php endif; ?>
            </div>
            <div class="col col-sidebar flavor-sidebar">
                <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => 14, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
        </div>
    </div>
</section>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer_flavor' : 'user/footer_flavor'); ?>
<?php
/* 
Duże zdjęcie - galeria miniaturki
*/
?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>
<?php
    $id_add = 2;
    if(!empty($data['id_page']) && in_array($data['id_page'], array(3))) {
        $id_add = 11;
    }
?>
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => $id_add, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<section class="news-single news-<?=$data['id'];?> entertainment-news news-view-1">
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
                            <source srcset="/image/<?=$data['photo_method'];?>/800/520/<?=$data['photo']['path']; ?>" media="(max-width: 800px)">
                            <img src="/image/<?=$data['photo_method'];?>/1260/600/<?=$data['photo']['path']; ?>" alt="<?=esc($data['photo']['caption']); ?>" />
                        </picture>
                    </a>
                    <?php if(!empty($data['photo']['caption'])): ?>
                        <div class="photo-caption">
                            <?=$data['photo']['caption']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
			<?php if(!empty($data['photos'])): ?>
                <div class="thumbnails-box">
                    <?php foreach($data['photos'] as $p=>$photo): ?>
                        <?php if($p < 4): ?>
                            <div class="thumbnail">
                                <a href="/image/r/1920/1080/<?=$photo['path']; ?>"  data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['title']); ?>" rel="lightbox">
                                    <picture>
                                        <img src="/image/c/460/300/<?=$photo['path']; ?>" alt="<?=esc($photo['caption']); ?>" />
                                    </picture>
                                    <?php if($p == 3 && count($data['photos']) > 4): ?>
                                        <span class="plus"><strong>+<?=count($data['photos']) - 4; ?></strong></span>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php else: ?>
                            <a href="/image/r/1920/1080/<?=$photo['path']; ?>"  data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['title']); ?>" rel="lightbox"></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
	<div class="container">
        <?php if(!empty($data['subtitle']) || !empty($data['title'])): ?>
            <h1 class="name"><?=!empty($data['subtitle']) ? $data['subtitle'] : $data['title']; ?></h1>
            <?php if(!empty($data['date']) || !empty($data['author'])): ?>
                <p class="date-author"><?=!empty($data['date']) ? lang('User.entertainment.Added') . ': ' . date('d.m.Y', strtotime($data['date'])) . ' ' . lang('User.entertainment.YearShort') : ''; ?><?=!empty($data['date']) && !empty($data['author']) ? ' / ' : ''; ?><?=!empty($data['author']) ? '<span>' . $data['author'] . '</span>' : ''; ?></p>
            <?php endif; ?>
        <?php endif; ?>
		 <div class="social-share-buttons<?php if(empty($data['photo'])): ?> no-photo<?php endif; ?>">
                <a class="facebook open" href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode(current_url()); ?>" title="<?=lang('Users.social.ShareFacebook'); ?>" target="_blank" rel="nofollow">
                    <svg viewBox="0 0 56.693 56.693"><path d="M40.43,21.739h-7.645v-5.014c0-1.883,1.248-2.322,2.127-2.322c0.877,0,5.395,0,5.395,0V6.125l-7.43-0.029  c-8.248,0-10.125,6.174-10.125,10.125v5.518h-4.77v8.53h4.77c0,10.947,0,24.137,0,24.137h10.033c0,0,0-13.32,0-24.137h6.77  L40.43,21.739z"/></svg>
                </a>
                <a class="twitter open" href="https://twitter.com/intent/tweet?text=<?=urlencode($data['title']); ?>&url=<?=urlencode(current_url()); ?>" title="<?=lang('Users.social.ShareX'); ?>" target="_blank" rel="nofollow">
                    <svg viewBox="0 0 24 24"><path d="M14.095479,10.316482L22.286354,1h-1.940718l-7.115352,8.087682L7.551414,1H1l8.589488,12.231093L1,23h1.940717  l7.509372-8.542861L16.448587,23H23L14.095479,10.316482z M11.436522,13.338465l-0.871624-1.218704l-6.924311-9.68815h2.981339  l5.58978,7.82155l0.867949,1.218704l7.26506,10.166271h-2.981339L11.436522,13.338465z"/></svg>
                </a>
                <a class="linkedin open" href="https://www.linkedin.com/shareArticle?mini=true&url=<?=urlencode(current_url()); ?>&title=<?=urlencode($data['title']); ?>" title="<?=lang('Users.social.ShareLinkedIn'); ?>" target="_blank" rel="nofollow">
                    <svg viewBox="0 0 32 32"><g><path d="M32,31.292V19.46c0-6.34-3.384-9.29-7.896-9.29c-3.641,0-5.273,2.003-6.182,3.409v-2.924h-6.86   c0.091,1.937,0,20.637,0,20.637h6.86V19.767c0-0.615,0.044-1.232,0.226-1.672c0.495-1.233,1.624-2.509,3.518-2.509   c2.483,0,3.475,1.892,3.475,4.666v11.041H32V31.292z M3.835,7.838c2.391,0,3.882-1.586,3.882-3.567   c-0.044-2.024-1.49-3.564-3.836-3.564S0,2.246,0,4.271c0,1.981,1.489,3.567,3.792,3.567H3.835z M7.265,31.292V10.655H0.406v20.637   H7.265z"/></g></svg>
                </a>
                <?php if($mobile): ?>
                    <a class="whatsapp open" href="https://wa.me/?text=<?=urlencode($data['title']); ?>%20<?=urlencode(current_url()); ?>" title="<?=lang('Users.social.ShareWhatsApp'); ?>" target="_blank" rel="nofollow">
                        <svg viewBox="0 0 56.693 56.693"><g><path d="M46.3802,10.7138c-4.6512-4.6565-10.8365-7.222-17.4266-7.2247c-13.5785,0-24.63,11.0506-24.6353,24.6333   c-0.0019,4.342,1.1325,8.58,3.2884,12.3159l-3.495,12.7657l13.0595-3.4257c3.5982,1.9626,7.6495,2.9971,11.7726,2.9985h0.01   c0.0008,0-0.0006,0,0.0002,0c13.5771,0,24.6293-11.0517,24.635-24.6347C53.5914,21.5595,51.0313,15.3701,46.3802,10.7138z    M28.9537,48.6163h-0.0083c-3.674-0.0014-7.2777-0.9886-10.4215-2.8541l-0.7476-0.4437l-7.7497,2.0328l2.0686-7.5558   l-0.4869-0.7748c-2.0496-3.26-3.1321-7.028-3.1305-10.8969c0.0044-11.2894,9.19-20.474,20.4842-20.474   c5.469,0.0017,10.6101,2.1344,14.476,6.0047c3.8658,3.8703,5.9936,9.0148,5.9914,14.4859   C49.4248,39.4307,40.2395,48.6163,28.9537,48.6163z"/><path d="M40.1851,33.281c-0.6155-0.3081-3.6419-1.797-4.2061-2.0026c-0.5642-0.2054-0.9746-0.3081-1.3849,0.3081   c-0.4103,0.6161-1.59,2.0027-1.9491,2.4136c-0.359,0.4106-0.7182,0.4623-1.3336,0.1539c-0.6155-0.3081-2.5989-0.958-4.95-3.0551   c-1.83-1.6323-3.0653-3.6479-3.4245-4.2643c-0.359-0.6161-0.0382-0.9492,0.27-1.2562c0.2769-0.2759,0.6156-0.7189,0.9234-1.0784   c0.3077-0.3593,0.4103-0.6163,0.6155-1.0268c0.2052-0.4109,0.1027-0.7704-0.0513-1.0784   c-0.1539-0.3081-1.3849-3.3379-1.8978-4.5706c-0.4998-1.2001-1.0072-1.0375-1.3851-1.0566   c-0.3585-0.0179-0.7694-0.0216-1.1797-0.0216s-1.0773,0.1541-1.6414,0.7702c-0.5642,0.6163-2.1545,2.1056-2.1545,5.1351   c0,3.0299,2.2057,5.9569,2.5135,6.3676c0.3077,0.411,4.3405,6.6282,10.5153,9.2945c1.4686,0.6343,2.6152,1.013,3.5091,1.2966   c1.4746,0.4686,2.8165,0.4024,3.8771,0.2439c1.1827-0.1767,3.6419-1.489,4.1548-2.9267c0.513-1.438,0.513-2.6706,0.359-2.9272   C41.211,33.7433,40.8006,33.5892,40.1851,33.281z"/></g></svg>
                    </a>
                <?php endif; ?>
                <a class="mail" href="mailto:?subject=<?=urlencode($data['title']); ?>&body=<?=urlencode(current_url()); ?>" title="<?=lang('Users.social.ShareEmail'); ?>" rel="nofollow">
                    <svg viewBox="-0.709 -27.689 141.732 141.732"><g><path d="M90.854,43.183l39.834,34.146l-3.627,3.627L86.924,46.552L70.177,60.907L53.626,46.719L13.693,80.951l-3.807-3.807   L49.5,43.182L9.68,9.044l3.627-3.627l56.676,48.587L82.8,43.016l-0.035-0.032h0.073l43.829-37.575l3.811,3.811L90.854,43.183z    M140.314,80.96V5.411c0-2.988-2.416-5.411-5.396-5.411c-0.021,0-0.041,0.003-0.062,0.004C134.835,0.003,134.814,0,134.793,0   c-0.333,0-0.655,0.035-0.975,0.098V0.018H11.158V0.01H5.564C5.508,0.007,5.453,0,5.396,0C5.376,0,5.355,0.003,5.334,0.004   C5.312,0.003,5.293,0,5.271,0C2.359,0,0,2.366,0,5.284c0,0.021,0.003,0.042,0.003,0.063C0.003,5.368,0,5.39,0,5.411V80.96   c0,2.979,2.416,5.396,5.396,5.396h129.521C137.898,86.355,140.314,83.939,140.314,80.96"/></g></svg>
                </a>
                <a class="copy" href="<?=current_url(); ?>" rel="nofollow" title="<?=lang('Users.social.ShareUrl'); ?>" target="_blank">
                    <svg viewBox="0 0 640 512"><path d="M598.6 41.41C570.1 13.8 534.8 0 498.6 0s-72.36 13.8-99.96 41.41l-43.36 43.36c15.11 8.012 29.47 17.58 41.91 30.02c3.146 3.146 5.898 6.518 8.742 9.838l37.96-37.96C458.5 72.05 477.1 64 498.6 64c20.67 0 40.1 8.047 54.71 22.66c14.61 14.61 22.66 34.04 22.66 54.71s-8.049 40.1-22.66 54.71l-133.3 133.3C405.5 343.1 386 352 365.4 352s-40.1-8.048-54.71-22.66C296 314.7 287.1 295.3 287.1 274.6s8.047-40.1 22.66-54.71L314.2 216.4C312.1 212.5 309.9 208.5 306.7 205.3C298.1 196.7 286.8 192 274.6 192c-11.93 0-23.1 4.664-31.61 12.97c-30.71 53.96-23.63 123.6 22.39 169.6C293 402.2 329.2 416 365.4 416c36.18 0 72.36-13.8 99.96-41.41L598.6 241.3c28.45-28.45 42.24-66.01 41.37-103.3C639.1 102.1 625.4 68.16 598.6 41.41zM234 387.4L196.1 425.3C181.5 439.1 162 448 141.4 448c-20.67 0-40.1-8.047-54.71-22.66c-14.61-14.61-22.66-34.04-22.66-54.71s8.049-40.1 22.66-54.71l133.3-133.3C234.5 168 253.1 160 274.6 160s40.1 8.048 54.71 22.66c14.62 14.61 22.66 34.04 22.66 54.71s-8.047 40.1-22.66 54.71L325.8 295.6c2.094 3.939 4.219 7.895 7.465 11.15C341.9 315.3 353.3 320 365.4 320c11.93 0 23.1-4.664 31.61-12.97c30.71-53.96 23.63-123.6-22.39-169.6C346.1 109.8 310.8 96 274.6 96C238.4 96 202.3 109.8 174.7 137.4L41.41 270.7c-27.6 27.6-41.41 63.78-41.41 99.96c-.0001 36.18 13.8 72.36 41.41 99.97C69.01 498.2 105.2 512 141.4 512c36.18 0 72.36-13.8 99.96-41.41l43.36-43.36c-15.11-8.012-29.47-17.58-41.91-30.02C239.6 394.1 236.9 390.7 234 387.4z"/></svg>
                </a>
            </div>
			     <div class="entertainment-row">
            <div class="col col-content">
                <?php if(!empty($data['content'])): ?>
                    <div class="content"><?=$data['content']; ?></div>
                <?php endif; ?>
                <?php if(!empty($data['source'])): ?>
                    <div class="news-source"><?=lang('News.user.Source'); ?> <?=$data['source']; ?></div>
                <?php endif; ?>
                <?php if(!empty($data['photos'])): ?>
                    <div class="gallery-box">
                        <?php foreach($data['photos'] as $p=>$photo): ?>
                            <div class="gallery-photo">
                                <a href="/image/r/1920/1080/<?=$photo['path']; ?>"  data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['title']); ?>" rel="lightbox">
                                    <picture>
                                        <img src="/image/c/1000/650/<?=$photo['path']; ?>" alt="<?=esc($photo['caption']); ?>" />
                                    </picture>
                                </a>
                                <?php if(!empty($photo['caption'])): ?>
                                    <span class="photo-caption"><?=$photo['caption']; ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 2, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
                <?php if(!empty($data['comment'])): ?>
                    <?= view_cell('\Modules\Comments\Libraries\Comments::showCommentsForm', ['id_lang' => $id_lang, 'locale' => $locale, 'id_link' => $data['id_link']]); ?>
                <?php endif; ?>
                <?php if(!empty($data['tags'])): ?>
                    <div class="tags">
                        <div class="title">
                            <h3><?=lang('User.other.Tags'); ?></h3>
                        </div>
                        <?php foreach($data['tags'] as $tag): ?>
                            <div class="tag"><a href="/<?=!empty($global_links['search_tags']) ? $global_links['search_tags'] : ''; ?>/g/t/<?=$tag['tag']; ?>" title="<?=esc($tag['tag']); ?>"><?=$tag['tag']; ?></a></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if(!empty($data['id_page_cont'])): ?>
                    <?= view_cell('\Modules\News\Libraries\News::showOtherNewsList', ['id_page_cont' => $data['id_page_cont'], 'id_lang' => $id_lang, 'locale' => $locale, 'exclude' => [$data['id']]]) ?>
                <?php endif; ?>
            </div>
            <div class="col col-sidebar">
                <?php
                    $id_sidebar = 5;
                    if(!empty($data['id_page']) && in_array($data['id_page'], array(3))) {
                        $id_sidebar = 15;
                    }
                ?>
                <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
        </div>
    </div>

</section>
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
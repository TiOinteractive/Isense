<?php
/* 
Automatyczny
*/	
?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<?php if($page['photo']):?><div class="page-with-photo">
<figure class="page-photo-header" style="background-image:url('/image/<?=$page['photo'];?>');">
<?php if($page['header']):?><h1><?=$page['header'];?></h1><?php endif;?>
<div class="photo-mask"></div>
<div class="photo-mask2"></div>
</figure>
<?php endif;?>

<?php if(!empty($content)): ?>
    <?php foreach($content as $cont): ?>
        <?php if(!empty($cont) && !empty($cont['template'])): ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if($page['photo']):?></div><?php endif;?>


<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
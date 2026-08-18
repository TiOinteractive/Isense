<?php
/*
  RzeszowskieSmaki - mapa lokali
 */
?>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_header_flavor.php') ? 'user/m_header_flavor' : 'user/header_flavor'); ?>
<div id="flavors">
    <?php
    if (isset($breadcrumbs)) {
        echo view('user/breadcrumbs_flavor', array('bread' => $breadcrumbs));
    }
    ?>
    <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 17, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
    <?php
    if (!empty($content)) {
        foreach ($content as $cont):
            if (!empty($cont) && !empty($cont['template'])):
            ?>
                <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'config' => $cont['config'], 'id_sidebar' => $cont['id_sidebar'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
            <?php
            endif;
        endforeach;
    }
    ?>	
</div>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer_flavor' : 'user/footer_flavor'); ?>
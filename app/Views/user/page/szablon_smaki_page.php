<?php
/*
  RzeszowskieSmaki - podstrona
 */
?>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_header_flavor.php') ? 'user/m_header_flavor' : 'user/header_flavor'); ?>
<div id="flavors" <?php if(!empty($data['page_content']['restaurant']['awarded'])):?>class="awarded"<?php endif;?>>
    <?php
    if (isset($breadcrumbs)) {
        echo view('user/breadcrumbs_flavor', array('bread' => $breadcrumbs));
    }
    ?>
    <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 17, 'id_lang' => $id_lang, 'locale' => $locale]) ?>




    <div id="flavors_page">
        <div class="container">
            <div id="column">
                <?php if (empty($data['page_content']['restaurant']['awarded'])): ?>
                    <div class="left-sidebar">
                        <?= view_cell('\Modules\Flavors\Libraries\Flavors::FlavorMenu', ['id_lang' => $id_lang, 'active' => '']); ?>
                        <?= view_cell('\Modules\Flavors\Libraries\Flavors::CuisineMenu', ['id_lang' => $id_lang, 'active' => !empty($data['cuisine']['id']) ? $data['cuisine']['id'] : '']); ?>
                        <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 20, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
                    </div>
                <?php endif; ?> 
                <div class="center-column">
                    <?php
                    if (!empty($data['template'])) {
                        echo view($data['template'], ['id_lang' => $id_lang, 'data' => $data]);
                    } elseif (!empty($content)) {
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
            </div>
        </div>
    </div>
</div>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer_flavor' : 'user/footer_flavor'); ?>
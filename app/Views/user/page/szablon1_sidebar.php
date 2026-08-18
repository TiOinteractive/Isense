<?php
/* 
Automatyczny - Sidbar po prawej
*/		
?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="entertainment">
    <div class="container">
        <div class="entertainment-row" style="display: flex; flex-wrap: nowrap;margin: 0 -15px;position: relative;">
            <div class="col col-content">
                <?php if(!empty($content)): ?>
                    <?php foreach($content as $cont): ?>
                        <?php if(!empty($cont) && !empty($cont['template'])): ?>
                            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col col-sidebar">
                <?php if(!empty($id_sidebar)): ?>
                    <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
    
<?php /* view_cell('\Modules\Event\Libraries\Event::showMainPlaceTypesWithHomePlacesList', ['id_lang' => $id_lang, 'locale' => $locale, 'id_page' => 5])*/ ?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
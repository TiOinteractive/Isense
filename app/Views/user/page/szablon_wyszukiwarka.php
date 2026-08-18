<?php
/*
  Strona wyszukiwarki
 */
?>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>
<section class="search-list">
    <div class="container">
        <?php if (!empty($page['header'])): ?> 
            <div class="title resinet-title">
                <h2><?php if (!empty($url)): ?><a href="<?= $url; ?>"><?php endif; ?><?= $page['header']; ?><?php if (!empty($url)): ?></a><?php endif; ?></h2>
            </div>	
        <?php endif; ?> 
        <div class="entertainment-row">
            <div class="col col-content">
                <div id="news_cont">
                    <div class="margin1">
                        <div id="cse-search-results"></div>
                        <div class="gcse-searchresults-only"></div>
                        <style>
                            #cse-search-results {position: relative; box-sizing: border-box;}
                            #cse-search-results iframe {box-sizing: border-box; max-width: 100%; min-height: 2200px;}
                        </style>
                    </div>
                </div>
                <script async src='https://cse.google.com/cse.js?cx=partner-pub-9301198135861729:2617030725'></script>
            </div>
            <div class="col col-sidebar">
                <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $page['id_sidebar'], 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
        </div>
</section>

<?= view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
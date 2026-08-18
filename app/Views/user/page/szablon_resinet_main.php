<?php
/*
  RESinet strona główna
 */
?>

<?= view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<?php if ($page['photo']): ?><div class="page-with-photo">
        <figure class="page-photo-header" style="background-image:url('/image/<?= $page['photo']; ?>');">
            <?php if ($page['header']): ?><h1><?= $page['header']; ?></h1><?php endif; ?>
            <div class="photo-mask"></div>
            <div class="photo-mask2"></div>
        </figure>
    <?php endif; ?>



    <?php if (!empty($content)): ?>
        <?php foreach ($content as $cont): ?>
            <?php if ($cont['id'] == 86 || $cont['id'] == 92): ?>
                <?php if (!empty($cont) && !empty($cont['template'])): ?>
                    <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
                <?php endif; ?>
            <?php endif; ?>	
        <?php endforeach; ?>
    <?php endif; ?>


    <?php if (empty($mobile)): ?>
        <section id="news-most">
            <div class="container flex">
                <div class="left-column">
                    <?php if (!empty($content)): ?>
                        <?php foreach ($content as $cont): ?>
                            <?php if ($cont['id'] == 76): ?>
                                <?php if (!empty($cont) && !empty($cont['template'])): ?>
                                    <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
                                <?php endif; ?>
                            <?php endif; ?>	
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="right-column">


                </div>
            </div>
        </section>

        <?php if (!empty($content)): ?>
            <?php foreach ($content as $cont): ?>
                <?php if ($cont['id'] != 76 and $cont['id'] != 86 and $cont['id'] != 92): ?>
                    <?php if (!empty($cont) && !empty($cont['template'])): ?>
                        <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
                    <?php endif; ?>
                <?php endif; ?>	
            <?php endforeach; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php if (!empty($content['cont_71'])): $cont = $content['cont_71']; ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>
        <?php if (!empty($content['cont_72'])): $cont = $content['cont_72']; ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>

        <?php if (!empty($content['cont_75'])): $cont = $content['cont_75']; ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>

        <?php if (!empty($content['cont_88'])): $cont = $content['cont_88']; ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>
        <?php if (!empty($content['cont_90'])): $cont = $content['cont_90']; ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>

        <div class="container">
            <?= view_cell('\Modules\Survey\Libraries\Survey::showSurvey', ['id_lang' => $id_lang, 'locale' => $locale]) ?>
            <?php if (!empty($content['cont_76'])): $cont = $content['cont_76']; ?>
                <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
            <?php endif; ?>
        </div>
        <?php if (!empty($content['cont_77'])): $cont = $content['cont_77']; ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>
        <?php if (!empty($content['cont_87'])): $cont = $content['cont_87']; ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>
        <?php if (!empty($content['cont_110'])): $cont = $content['cont_110']; ?>
            <?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'url' => $cont['url'], 'id_sidebar' => $cont['id_sidebar'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
        <?php endif; ?>
    <?php endif; ?>


    <?php if ($page['photo']): ?></div><?php endif; ?>


<?php /*
  <div id="commercial-links">
  <div class="container">
  <h3>Polecamy</h3>
  <div class="list flex">
  <div class="box">
  <ul>
  <li>
  <a href="https://www.tiointeractive.pl/pozycjonowanie-stron-rzeszow.html" title="Pozycjonowanie stron Rzeszów" target="_blank">
  <span class="name trans400">Pozycjonowanie stron Rzeszów</span></a>
  </li>
  <li>
  <a href="https://www.serwis.tio.pl/" title="Apple - Serwis komputerowy Rzeszów" target="_blank"><span class="name trans400">Apple - Serwis komputerowy Rzeszów</span></a>
  </li>
  <li>
  <a href="https://www.helios-szklo.pl/" title="sztućce i porcelana - Gerlach | Amefa" target="_blank"><span class="name trans400">sztućce i porcelana - Gerlach | Amefa</span></a>
  </li>
  <li>
  <a href="https://www.tio.pl/" title="APPLE notebooki MacBook, Ipad, Iphone, komputery iMac, Apple Watch" target="_blank"><span class="name trans400">APPLE notebooki MacBook, Ipad, Iphone, komputery iMac, Apple Watch</span></a>
  </li>
  <li>
  <a href="https://sklep.greinplast.pl/" title="Greinplast - Chemia budowlana - Sklep" target="_blank"><span class="name trans400">Greinplast - Chemia budowlana - Sklep</span></a>
  </li>
  </ul>
  </div>
  <div class="box">
  <ul>
  <li>
  <a href="https://www.k-sport.com.pl/" title="Siłownia domowa - K-Sport Polska" target="_blank"><span class="name trans400">Siłownia domowa - K-Sport Polska</span></a>
  </li>
  <li>
  <a href="https://www.garden.k-sport.com.pl/" title="Huśtawki ogrodowe, stelaż huśtawki, hamaki ogrodowe" target="_blank"><span class="name trans400">Huśtawki ogrodowe, stelaż huśtawki, hamaki ogrodowe</span></a>
  </li>
  <li>
  <a href="https://www.citistom.pl/" title="Citistom - stomatolog, dentysta, protetyk Katowice" target="_blank"><span class="name trans400">Citistom - stomatolog, dentysta, protetyk Katowice</span></a>
  </li>
  <li>
  <a href="https://www.resinet.pl/rzeszowskie-smaki" title="Restauracje, kawiarnie, pizzerie, kebab Rzeszów" target="_blank"><span class="name trans400">Restauracje, kawiarnie, pizzerie, kebab Rzeszów</span></a>
  </li>
  <li>
  <a href="https://wood-pool.pl/basen-ogrodowy" title="Baseny ogrodowe klasy PREMIUM" target="_blank"><span class="name trans400">Baseny ogrodowe klasy PREMIUM</span></a>
  </li>
  </ul>
  </div>
  <div class="box">
  <ul>
  <li>
  <a href="https://www.eura-tech.eu/" title="Eura - Tech: Systemy bezpieczeństwa i automatyka domowa" target="_blank"><span class="name trans400">Eura - Tech: Systemy bezpieczeństwa i automatyka domowa</span></a>
  </li>
  <li>
  <a href="http://www.szkolkazielinscy.pl/" title="Szkółka Zielińscy - Zasów - drzewa i krzewy liściaste, owocowe i iglaste - Producenta" target="_blank"><span class="name trans400">Szkółka Zielińscy - Zasów - drzewa i krzewy liściaste, owocowe i iglaste - Producenta</span></a>
  </li>
  <li>
  <a href="https://www.milleniumhall.pl/" title="Galeria Centrum Kulturalno - Handlowe Millenium Hall Rzeszów - Twoje miejsce w Rzeszowie!" target="_blank"><span class="name trans400">Galeria Centrum Kulturalno - Handlowe Millenium Hall Rzeszów - Twoje miejsce w Rzeszowie!</span></a>
  </li>
  <li>
  <a href="https://www.putzsystem.pl/" title="Agregaty malarskie, agregaty tynkarskie, narzedzia Milwaukee" target="_blank"><span class="name trans400">Agregaty malarskie, agregaty tynkarskie, narzedzia Milwaukee</span></a>
  </li>
  <li>
  <a href="https://www.tanierosliny.pl/" title="TanieRosliny - internetowy sklep ogrodniczy" target="_blank"><span class="name trans400">TanieRosliny - internetowy sklep ogrodniczy</span></a>
  </li>
  </ul>
  </div>
  </div>
  </div>
  </div>
 */ ?>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
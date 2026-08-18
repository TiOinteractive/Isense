<?php
/*
  Lista produktów
 */
?>

<?php if(!empty($data) &&!empty($data['list'])): ?>
<section class="section-<?= $id_cont; ?> shop-list">
    <div class="container">
        <?php if(!empty($title)): ?> 
        <div class="title resinet-title">
            <h2><?php if(!empty($url)): ?><a href="<?= $url; ?>"><?php endif; ?><?= $title; ?><?php if(!empty($url)): ?></a><?php endif; ?></h2>
            <?php if(!empty($subtitle)): ?>
            <h3><?= $subtitle; ?></h3>
            <?php endif; ?>
        </div>	
        <?php endif; ?> 
        <div id="product_lists">
            <?php foreach($data['list'] as $product): ?>
            <div class="product">
                <div class="inside"> 
                    <figure class="trans400">
                        <a href="https://www.zakupy.resinet.pl/<?= $product['product_link']['link']; ?>" target="_blank" title="<?= $product['nazwa_sklep']; ?>: <?= $product['nazwa']; ?>"><img src="<?= $product['zdjecie_glowne']; ?>" alt="<?= $product['nazwa_sklep']; ?>: <?= str_replace('`', '', $product['nazwa']); ?>"></a>
                    </figure>
                    <h4><a href="https://www.zakupy.resinet.pl/<?= $product['shop_link']['link']; ?>" target="_blank" title="<?= $product['nazwa_sklep']; ?>"><?= $product['nazwa_sklep']; ?></a></h4>
                    <h3><a href="https://www.zakupy.resinet.pl/<?= $product['product_link']['link']; ?>" target="_blank" title="<?= $product['nazwa_sklep']; ?>: <?= $product['nazwa']; ?>"><?= $product['nazwa']; ?></a></h3>
                    <div class="price">
                        <div class="normal"><span><?= number_format($product['cena'], 2, ',', ' '); ?></span> zł</div>
                        <?php if(!empty($product['cena_old']) and $product['cena_old']>0 and $product['cena_old']>$product['cena']): ?>
                        <div class="old"><span><?= number_format($product['cena_old'], 2, ',', ' '); ?></span> zł</div>
                                <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>	
<?php endif; ?>
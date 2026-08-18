<?php
/*
  Resinet - sidebar
*/
?>

<?php if(!empty($data['list'])): ?>
<section class="section-<?=$id_cont; ?> resinet-foto-gallery-sidebar">
    <div class="container">	
        <?php if(!empty($title)):?>
            <div class="title resinet-title-sidebar">
               <h2><?php if(!empty($url)):?><a href="<?= $url; ?>" title="<?=esc($title); ?>"><?php endif;?><?= $title; ?><?php if(!empty($url)):?></a><?php endif;?></h2>
            </div>
        <?php endif;?>
        <div class="list">
            <?php foreach($data['list'] as $foto):?>
                <div class="item">
                    <div class="item-cont">
                        <div class="photo">
                            <?php if(!empty($foto['photo'])):?> 
                                <a href="/<?= $foto['link']; ?>" title="<?= esc($foto['name']); ?>">
                                    <img src="/image/c/740/490/<?= $foto['photo']; ?>" alt="<?php if(!empty($foto['photo_caption'])):?><?= $foto['photo_caption']; ?><?php else: ?><?= esc($foto['name']); ?><?php endif; ?>" />
                                </a>  
                            <?php endif; ?>
                            <?php if(!empty($foto['number_of_photo'])):?><div class="number"><svg viewBox="0 0 48 48" width="25px"><path clip-rule="evenodd" d="M43,41H5c-2.209,0-4-1.791-4-4V15c0-2.209,1.791-4,4-4h1l0,0c0-1.104,0.896-2,2-2  h2c1.104,0,2,0.896,2,2h2c0,0,1.125-0.125,2-1l2-2c0,0,0.781-1,2-1h8c1.312,0,2,1,2,1l2,2c0.875,0.875,2,1,2,1h9  c2.209,0,4,1.791,4,4v22C47,39.209,45.209,41,43,41z M45,15c0-1.104-0.896-2-2-2l-9.221-0.013c-0.305-0.033-1.889-0.269-3.193-1.573  l-2.13-2.13l-0.104-0.151C28.351,9.132,28.196,9,28,9h-8c-0.153,0-0.375,0.178-0.424,0.231l-0.075,0.096l-2.087,2.086  c-1.305,1.305-2.889,1.54-3.193,1.573l-4.151,0.006C10.046,12.994,10.023,13,10,13H8c-0.014,0-0.026-0.004-0.04-0.004L5,13  c-1.104,0-2,0.896-2,2v22c0,1.104,0.896,2,2,2h38c1.104,0,2-0.896,2-2V15z M24,37c-6.075,0-11-4.925-11-11s4.925-11,11-11  s11,4.925,11,11S30.075,37,24,37z M24,17c-4.971,0-9,4.029-9,9s4.029,9,9,9s9-4.029,9-9S28.971,17,24,17z M24,31  c-2.762,0-5-2.238-5-5s2.238-5,5-5s5,2.238,5,5S26.762,31,24,31z M24,23c-1.656,0-3,1.344-3,3c0,1.657,1.344,3,3,3  c1.657,0,3-1.343,3-3C27,24.344,25.657,23,24,23z M10,19H6c-0.553,0-1-0.447-1-1v-2c0-0.552,0.447-1,1-1h4c0.553,0,1,0.448,1,1v2  C11,18.553,10.553,19,10,19z" fill-rule="evenodd"/></svg><?=$foto['number_of_photo'];?></div><?php endif; ?>
                        </div>
                        <div class="info">
                            <h3><a href="/<?= $foto['link']; ?>" title="<?= esc($foto['name']); ?>"><?= $foto['name']; ?></a></h3>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php endif; ?>
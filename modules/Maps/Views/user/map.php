<?php
/* 
Szablon 1
*/
?>
<section class="section-<?=$id_cont; ?> maps-<?=$data['id'];?>">
    <div class="container">
        <?php if(!empty($title)): ?>
            <h2 style="color:#fff;"><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
    </div>
    <?php if(!empty($data)): ?>
    <div class="map-container" id="map-<?=$data['id'];?>" style="width:100%;height:300px;position:relative;outline:none;overflow:hidden;"></div>
    <?php endif; ?>
</section>
<?php
/* 
Rozrywka - Główna
*/	
?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<div class="entertainment-boxes">
    <div class="container">
        <div class="entertainment-home-row">
            <div class="col col2-8">
                <?php if(!empty($content['cont_31'])): ?>
                    <?= view($content['cont_31']['template'], array('id_cont' => $content['cont_31']['id'], 'title' => $content['cont_31']['title'], 'subtitle' => $content['cont_31']['subtitle'], 'url' => $content['cont_31']['url'], 'data' => $content['cont_31']['data'])); ?>
                <?php endif; ?>
            </div>
            <div class="col col2-4">
                <?php if(!empty($content['cont_25'])): ?>
                    <?= view($content['cont_25']['template'], array('id_cont' => $content['cont_25']['id'], 'title' => $content['cont_25']['title'], 'subtitle' => $content['cont_25']['subtitle'], 'url' => $content['cont_25']['url'], 'data' => $content['cont_25']['data'])); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="entertainment-news">
    <div class="container">
        <div class="entertainment-home-row">
            <div class="col col2-8">
                <?php if(!empty($content['cont_24'])): ?>
                    <?= view($content['cont_24']['template'], array('id_cont' => $content['cont_24']['id'], 'title' => $content['cont_24']['title'], 'subtitle' => $content['cont_24']['subtitle'], 'url' => $content['cont_24']['url'], 'data' => $content['cont_24']['data'])); ?>
                <?php endif; ?>
            </div>
            <div class="col col2-4">
                <?php if(!empty($content['cont_33'])): ?>
                    <?= view($content['cont_33']['template'], array('id_cont' => $content['cont_33']['id'], 'title' => $content['cont_33']['title'], 'subtitle' => $content['cont_33']['subtitle'], 'url' => $content['cont_33']['url'], 'data' => $content['cont_33']['data'])); ?>
                <?php endif; ?>
                <?php if(!empty($content['cont_34'])): ?>
                    <?= view($content['cont_34']['template'], array('id_cont' => $content['cont_34']['id'], 'title' => $content['cont_34']['title'], 'subtitle' => $content['cont_34']['subtitle'], 'url' => $content['cont_34']['url'], 'data' => $content['cont_34']['data'])); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if(!empty($content['cont_26'])): ?>
    <?= view($content['cont_26']['template'], array('id_cont' => $content['cont_26']['id'], 'title' => $content['cont_26']['title'], 'subtitle' => $content['cont_26']['subtitle'], 'url' => $content['cont_26']['url'], 'data' => $content['cont_26']['data'])); ?>
<?php endif; ?>
<?php if(!empty($content['cont_27'])): ?>
    <?= view($content['cont_27']['template'], array('id_cont' => $content['cont_27']['id'], 'title' => $content['cont_27']['title'], 'subtitle' => $content['cont_27']['subtitle'], 'url' => $content['cont_27']['url'], 'data' => $content['cont_27']['data'])); ?>
<?php endif; ?>
<?php if(!empty($content['cont_2'])): ?>
    <?= view($content['cont_2']['template'], array('id_cont' => $content['cont_2']['id'], 'title' => $content['cont_2']['title'], 'subtitle' => $content['cont_2']['subtitle'], 'url' => $content['cont_2']['url'], 'data' => $content['cont_2']['data'])); ?>
<?php endif; ?>
<?php if(!empty($content['cont_28'])): ?>
    <?= view($content['cont_28']['template'], array('id_cont' => $content['cont_28']['id'], 'title' => $content['cont_28']['title'], 'subtitle' => $content['cont_28']['subtitle'], 'url' => $content['cont_28']['url'], 'data' => $content['cont_28']['data'])); ?>
<?php endif; ?>
<div class="container">
    <div class="entertainment-home-row">
        <div class="col col-6">
            <?php if(!empty($content['cont_29'])): ?>
                <?= view($content['cont_29']['template'], array('id_cont' => $content['cont_29']['id'], 'title' => $content['cont_29']['title'], 'subtitle' => $content['cont_29']['subtitle'], 'url' => $content['cont_29']['url'], 'data' => $content['cont_29']['data'])); ?>
            <?php endif; ?>
        </div>
        <div class="col col-4">
            <?php if(!empty($content['cont_30'])): ?>
                <?= view($content['cont_30']['template'], array('id_cont' => $content['cont_30']['id'], 'title' => $content['cont_30']['title'], 'subtitle' => $content['cont_30']['subtitle'], 'url' => $content['cont_30']['url'], 'data' => $content['cont_30']['data'])); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>
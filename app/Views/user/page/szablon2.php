<?php
/* 
Ręczny
*/
	
?>

<?php if(!empty($content['cont_1'])): ?>
    <?= view($content['cont_1']['template'], array('id_cont' => $content['cont_1']['id'], 'title' => $content['cont_1']['title'], 'subtitle' => $content['cont_1']['subtitle'], 'data' => $content['cont_1']['data'])); ?>
<?php endif; ?>

<?php if(!empty($content['cont_3'])): ?>
    <?= view($content['cont_3']['template'], array('id_cont' => $content['cont_3']['id'], 'title' => $content['cont_3']['title'], 'subtitle' => $content['cont_3']['subtitle'], 'data' => $content['cont_3']['data'])); ?>
<?php endif; ?>

<?php if(!empty($content['cont_2'])): ?>
    <?= view($content['cont_2']['template'], array('id_cont' => $content['cont_2']['id'], 'title' => $content['cont_2']['title'], 'subtitle' => $content['cont_2']['subtitle'], 'data' => $content['cont_2']['data'])); ?>
<?php endif; ?>

<?php if(!empty($content['cont_4'])): ?>
    <?= view($content['cont_4']['template'], array('id_cont' => $content['cont_4']['id'], 'title' => $content['cont_4']['title'], 'subtitle' => $content['cont_4']['subtitle'], 'data' => $content['cont_4']['data'])); ?>
<?php endif; ?>

<?php if(!empty($content['cont_5'])): ?>
    <?= view($content['cont_5']['template'], array('id_cont' => $content['cont_5']['id'], 'title' => $content['cont_5']['title'], 'subtitle' => $content['cont_5']['subtitle'], 'data' => $content['cont_5']['data'])); ?>
<?php endif; ?>

<?php if(!empty($content['cont_6'])): ?>
    <?= view($content['cont_6']['template'], array('id_cont' => $content['cont_6']['id'], 'title' => $content['cont_6']['title'], 'subtitle' => $content['cont_6']['subtitle'], 'data' => $content['cont_6']['data'])); ?>
<?php endif; ?>
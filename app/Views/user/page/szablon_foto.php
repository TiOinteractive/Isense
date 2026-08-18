<?php
/* 
Foto.resinet
*/
if(!empty($custom_data['custom_data']['file'])) {
    $data=$custom_data['custom_data']['file'];
	$data['action']='category';
	if(!empty($custom_data['custom_data']['file']['id_category'])) {$data['active_id']=$custom_data['custom_data']['file']['id_category'];}
	if(!empty($custom_data['custom_data']['template'])) {
		$data['template']=$custom_data['custom_data']['template'];
	}	
}	
?>
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 18, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<div id="photo">
	<?= view_cell('\Modules\Foto\Libraries\Foto::showStats'); ?>
	<div class="container">
	<div id="photo-page" <?php if(empty($data) or (!empty($data) and $data['action']=='category')):?>class="two_column"<?php endif; ?>>
	  <?php if(empty($data) or (!empty($data) and $data['action']=='category')):?>	
		<div id="leftMenu">
		  <?= view_cell('\Modules\Foto\Libraries\Foto::showCats',array('id_lang'=>$id_lang,'id'=>0,'active_id'=>!empty($data['active_id']) ? $data['active_id'] : '')); ?>
		  <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 20, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
		</div>
	  <?php endif; ?>	
		<div id="rightPage">
	<?php if(!empty($content)): ?>
		<?php foreach($content as $cont): ?>
			<?php if(!empty($cont) && !empty($cont['template'])): ?>
				<?= view($cont['template'], array('id_cont' => $cont['id'], 'title' => $cont['title'], 'subtitle' => $cont['subtitle'], 'data' => !empty($cont['data']) ? $cont['data'] : null)); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php elseif(!empty($data['template'])): ?>
	  <?= view($data['template'], array('data'=>$data)); ?>
	<?php endif; ?>
		</div>
	</div>
	</div>
</div>
<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer_foto.php') ? 'user/m_footer_foto' : 'user/footer_foto'); ?>
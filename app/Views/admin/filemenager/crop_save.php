<?php if(!empty($post)):?>
<?php
if(!empty($post['field_name'])) {
   $f_name=$post['field_name'];	
}
else {
   $f_name='photo';
}	
?>
  <div class="crop_save">	
    <p><a href="/image/<?=$post['file_path'];?>?crop=1&w=<?=round($post['width']);?>&h=<?=round($post['height']);?>&x=<?=round($post['x']);?>&y=<?=round($post['y']);?>" target="_blank"><img src="/image/<?=$post['file_path'];?>?crop=1&w=<?=round($post['width']);?>&h=<?=round($post['height']);?>&x=<?=round($post['x']);?>&y=<?=round($post['y']);?>" /></a></p>
	<input type="hidden" name="<?=$f_name;?>[crop][path]" value="<?=$post['file_path'];?>" />
	<input type="hidden" name="<?=$f_name;?>[crop][width]" value="<?=round($post['width']);?>" />
	<input type="hidden" name="<?=$f_name;?>[crop][height]" value="<?=round($post['height']);?>" />
	<input type="hidden" name="<?=$f_name;?>[crop][x]" value="<?=round($post['x']);?>" />
	<input type="hidden" name="<?=$f_name;?>[crop][y]" value="<?=round($post['y']);?>" />
	   <a href="#" class="remove-crop-file filemenager-crop-remove" title="<?=lang('Admin.file-menager.DeleteCropFile');?>" data-title="<?=lang('Admin.file-menager.DeleteCropFile');?>" data-message="<?=lang('Admin.file-menager.ConfirmInfoCrop') . ': <strong>' . $post['file_path'] . '</strong>';?>" data-btn-ok="<?=lang('Admin.file-menager.Delete');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><i class="fa-solid fa-xmark"></i></a>
  </div>
<?php endif; ?>



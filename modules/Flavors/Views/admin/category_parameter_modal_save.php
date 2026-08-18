<?php if(!empty($params)):?>
	<div class="order-list">	
  <?php foreach($params as $param):?>
    <div class="list-row">
      <div class="list-col w200">
        <?=$param['name'];?>
		<input type="hidden" name="cat_param[]" value="<?=$param['id'];?>" />
      </div>
	  <div class="list-col w200">
        <?=$param['filter_name'];?>
      </div>
	  <div class="list-col">
        <?php if(!empty($param['values'])):?>
		 <?php foreach($param['values'] as $val):?>
		   <?=$val['value'];?>
		 <?php endforeach;?>
		 <?php endif;?>
      </div>
	  <div class="list-col w100 center">
	    <a class="remove-param" href="" data-title="<?=lang('Flavors.DeleteParameter');?>"><i class="fa-regular fa-trash-can fa-xl"></i><div></div></a>
	  </div>
    </div>
   <?php endforeach;?>
   </div>
<?php endif;?>
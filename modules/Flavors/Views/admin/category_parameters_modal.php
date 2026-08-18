<form class="form parameter-form" id="modal-value" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/save-cat-param" method="post">
<div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Flavors.ParameterName');?>
                </div>
                <div class="list-col w200">
                    <?=lang('Flavors.ParameterFilterName');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Flavors.Choose');?>
                </div>
            </div>
			<?php if(!empty($parameters)):?>
			   <?php foreach($parameters as $param):?>
			      <div class="list-row">
				    <div class="list-col">
					   <?=$param['name'];?>
					</div>
				    <div class="list-col w200">
					   <?=$param['filter_name'];?>
					</div>
				    <div class="list-col center w100">
					   <input type="checkbox" name="param[<?=$param['id'];?>]" value="<?=$param['id'];?>" <?php if(!empty($selected['cat_param']) and in_array($param['id'],$selected['cat_param'])):?>disabled="disabled"<?php endif;?> />
					</div>
				  </div>
			  <?php endforeach;?>
			<?php endif; ?>
</div>			
</form>
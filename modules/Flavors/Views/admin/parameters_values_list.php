<p><a class="btn" href="javascript:ParameterAddValue('<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/add_parameter_value/<?=$id_parameter; ?>','<?=lang('Flavors.ParametersAddValue');?>','<?=lang('Flavors.Save');?>','<?=lang('Flavors.Cancel');?>');" title="<?=lang('Flavors.ParametersAddValue');?>"><?=lang('Flavors.ParametersAddValue');?></a></p>
<div class="list form">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Flavors.ParametersValue');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Flavors.Edit');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Flavors.Delete');?>
                </div>
            </div>
			 <?php if(!empty($values['value_list'])): ?>
                <?php foreach($values['value_list'] as $value): ?>
				  <div class="list-row list-row-<?=$value['id']; ?>">
				     <div class="list-col">
                          <?=$value['value']; ?>
                     </div>
				     <div class="list-col center w100 hide-1200">
						 <a href="javascript:ParameterAddValue('<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/add_parameter_value/<?=$id_parameter; ?>?id_value=<?=$value['id'];?>','<?=lang('Flavors.ParametersEditValue');?>','<?=lang('Flavors.Save');?>','<?=lang('Flavors.Cancel');?>');" title="<?=lang('Flavors.Edit');?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
					 </div>
					 <div class="list-col center w100">
						  <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/delete_parameter_value/<?=$value['id']; ?>" data-title="<?=lang('Flavors.DeleteParameterValue');?>" data-message="<?=lang('Flavors.DeleteParameterValueConfirm') . ': <b>' . $value['value'] . '</b>'; ?>" data-btn-ok="<?=lang('Flavors.Remove');?>" data-btn-cancel="<?=lang('Flavors.Cancel');?>" title="<?=lang('Flavors.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
					 </div>
				  </div>
				<?php endforeach; ?>
			    <?php endif; ?>			
</div>		
<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($parameter) && !empty($parameter['id'])): ?>
                <?=$parameter['name']; ?>
                <span><?=lang('Flavors.ParameterEdit'); ?></span>
            <?php else: ?>
                <?=lang('Flavors.AddParameter'); ?>
            <?php endif; ?>
        </div>
		<?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
		        <form class="form parameter-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/<?php echo $action; ?><?=!empty($parameter['id']) ? '/' . $parameter['id'] : '' ; ?>" method="post">
            <input type="hidden" name="extra_charge" value="<?=in_array($action, array('extra-charge-add', 'extra-charge-edit', 'extra-charge-save')) ? 1: 0; ?>" />
            <div class="form-row nag">
                <h3><?=lang('Flavors.BasicInformation'); ?></h3>
            </div>
		    <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                    <?php $l=0; foreach($languages as $lang): ?>
                        <div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.ParameterName');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($parameter['lang']) ? esc($parameter['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
							<div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Flavors.ParameterFilterName');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][filter_name]" value="<?=!empty($parameter['lang']) ? esc($parameter['lang'][$lang['id']]['filter_name']) : ''; ?>" />
                                </div>
                            </div>
		           </div>
		        <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
			 <div class="form-row nag">
                <h3><?=lang('Flavors.ParameterSettings'); ?></h3>
            </div>
		     <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Flavors.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($parameter['publish']) && $parameter['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Flavors.Save');?></button>
            </div>  
		
		</form>
		</div>
	<?php if(!in_array($action, array('add-parameter'))):?> 
        <div class="form-row nag">
              <h3><?=lang('Flavors.ParametersValuesList'); ?></h3>
        </div>	
       <div id="ajax_params_value" data-action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/par_values<?=!empty($parameter['id']) ? '/' . $parameter['id'] : '';  ?>"></div>	
	<?php endif;?>
</div>	
</div>	
<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	    <div class="head">
            <?php if(!empty($zone) && !empty($zone['id'])): ?>
                <?=$zone['name']; ?>
                <span><?=lang('Banner.ZoneEdit'); ?></span>
            <?php else: ?>
                <?=lang('Banner.NewZoneAdd'); ?>
            <?php endif; ?>
        </div>
	     <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
		 <form class="form slider-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/<?php echo $action; ?><?=!empty($id) ? '/' . $id : '' ; ?>" method="post">
		   <div class="form-row nag">
                <h3><?=lang('Banner.ZoneSettings'); ?></h3>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Banner.NumberToShow');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="zone[number_to_show]" value="<?=!empty($zone['number_to_show']) ? $zone['number_to_show'] : ''; ?>" >
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Banner.Sort');?></label>
                </div>
                <div class="form-field">
                    <select name="zone[order_sort]">
					   <option value="date"<?php if(isset($zone['order_sort']) and $zone['order_sort']=='date') { echo ' selected="selected" ';}?>><?=lang('Banner.sort.date');?></option>
					   <option value="random"<?php if(isset($zone['order_sort']) and $zone['order_sort']=='random') { echo ' selected="selected" ';}?>><?=lang('Banner.sort.random');?></option>
					   <option value="order"<?php if(isset($zone['order_sort']) and $zone['order_sort']=='order') { echo ' selected="selected" ';}?>><?=lang('Banner.sort.order');?></option>
					</select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Banner.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="zone[publish]" <?php if(!empty($zone['publish']) && $zone['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
		   <div class="form-group">
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
                <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Banner.Name');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="zone[lang][<?=$lang['id']; ?>][name]" value="<?=!empty($zone['lang']) ? $zone['lang'][$lang['id']]['name'] : ''; ?>" />
                        </div>
                    </div>
		      </div>
			   <?php ++$l; endforeach; ?>
		   <?php if(!empty($languages) && count($languages) > 1): ?></div> <?php endif; ?>
		 </div>
	</div>	 
		  <div class="form-row submit">
                <button type="submit" class=""><?php if($action=="edit_zone") { echo lang('Banner.side.SaveBaner');} else { echo lang('Banner.side.AddZone'); } ?></button>
            </div> 
		 </form>
	</div>
</div>	
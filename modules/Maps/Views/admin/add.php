<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($map) && !empty($map['id'])): ?>
                <?=$map['name']; ?>
                <span><?=lang('Maps.MapEdit'); ?></span>
            <?php else: ?>
                <?=lang('Maps.NewMapAdd'); ?>
            <?php endif; ?>
            <a class="configuration" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/configuration" title="<?=lang('Maps.Configuration'); ?>"><i class="fa-solid fa-gear"></i></a>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form map-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/<?php echo $action; ?><?=!empty($map['id']) ? '/' . $map['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Maps.MapSettings'); ?></h3>
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
                        <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Maps.Name');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?= !empty($map['lang']) ? esc($map['lang'][$lang['id']]['name']) : ''; ?>" >
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Maps.CenterPos');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="center" value="<?= !empty($map['center']) ? esc($map['center']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Maps.Zoom');?></label>
                </div>
                <div class="form-field">
                    <input type="number" min="0" max="100" name="zoom" value="<?= !empty($map['zoom']) ? $map['zoom'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Maps.Style');?></label>
                </div>
                <div class="form-field">
                    <textarea name="styles"><?= !empty($map['styles']) ? $map['styles'] : ''; ?></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Maps.Type');?></label>
                </div>
                <div class="form-field">
                    <select name="type">
                        <option value="google_maps"<?php if(!empty($map['type']) && $map['type'] == 'google_maps'): ?> selected="selected"<?php endif; ?>><?=lang('Maps.GoogleMaps'); ?></option>
                        <option value="open_street_map"<?php if(!empty($map['type']) && $map['type'] == 'open_street_map'): ?> selected="selected"<?php endif; ?>><?=lang('Maps.OpenStreetMap'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Maps.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($map['publish']) && $map['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row-space"></div>     
            <div class="form-row nag with-btn">
                <h3><?=lang('Maps.Markers'); ?></h3>
                <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/marker-add" class="btn add-marker" title=""><?=lang('Maps.marker.AddMarker');?></a>
            </div>
            <div class="markers-box">
                <?php if(!empty($map['markers'])): ?>
                    <?php foreach($map['markers'] as $no=>$marker): ?>
                        <?=view('Modules\Maps\Views\admin\add_marker', array('marker'=>$marker, 'no'=>$no)); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?=view('Modules\Maps\Views\admin\add_marker', array('no'=>0)); ?>
                <?php endif; ?>
            </div>       
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Maps.Save');?></button>
            </div>     
        </form>
    </div>
</div>

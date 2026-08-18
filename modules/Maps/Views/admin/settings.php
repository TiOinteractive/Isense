<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=lang('Maps.Configuration');?>
            <a class="configuration" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/configuration" title="<?=lang('Maps.Configuration'); ?>"><i class="fa-solid fa-gear"></i></a>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form maps-settings-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/maps/configuration" method="post">
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Maps.configuration.GoogleMapsKey');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="google_map_key" value="<?= !empty($settings['google_map_key']) ? $settings['google_map_key'] : ''; ?>" >
                </div>
            </div>   
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Maps.Save');?></button>
            </div>  
        </form>
    </div>
</div>
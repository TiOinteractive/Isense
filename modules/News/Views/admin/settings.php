<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=lang('News.Configuration');?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form maps-settings-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/news/configuration/<?=$id_content; ?>" method="post">
            
            
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.configuration.aaaa');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="google_map_key" value="<?= !empty($settings['google_map_key']) ? $settings['google_map_key'] : ''; ?>" >
                </div>
            </div>  
            
            
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('News.Save');?></button>
            </div>  
        </form>
    </div>
</div>
<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($slider) && !empty($slider['id'])): ?>
                <?=$slider['name']; ?>
                <span>
                    <?php if(!empty($archive)): ?>
                        <?=lang('Slider.SliderArchive'); ?>
                    <?php else: ?>
                        <?=lang('Slider.SliderEdit'); ?>
                    <?php endif; ?>
                </span>
            <?php else: ?>
                <?=lang('Slider.NewSliderAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form slider-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/slider/<?php echo $action; ?><?=!empty($slider['id']) ? '/' . $slider['id'] : '' ; ?>" method="post">
            <?php if(empty($archive)): ?>
                <div class="form-row nag">
                    <h3><?=lang('Slider.SliderSettings'); ?></h3>
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
                                        <label><?=lang('Slider.Name');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?= !empty($slider['lang']) ? esc($slider['lang'][$lang['id']]['name']) : ''; ?>" >
                                    </div>
                                </div>
                            </div>
                        <?php ++$l; endforeach; ?>
                    <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Slider.Publish');?></label>
                    </div>
                    <div class="form-field">
                        <input type="checkbox" name="publish" <?php if(!empty($slider['publish']) && $slider['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-row-space"></div>
            <div class="form-row nag with-btn">
                <h3><?=lang('Slider.Slides'); ?></h3>
                <?php if(empty($archive)): ?>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/slider/slide-add" class="btn add-slide" title=""><?=lang('Slider.slide.AddSlide');?></a>
                <?php endif; ?>
            </div>
            <div class="order-box slides-box">
                <?php if(!empty($slider['slide'])): ?>
                    <?php foreach($slider['slide'] as $no=>$slide): ?>
                        <?=view('Modules\Slider\Views\admin\add_slide', array('slide'=>$slide, 'no'=>$no)); ?>
                    <?php endforeach; ?>
                <?php elseif($action != 'archive'): ?>
                    <?=view('Modules\Slider\Views\admin\add_slide', array('no'=>0)); ?>
                <?php endif; ?>
            </div>            
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Slider.Save');?></button>
            </div>     
        </form>
    </div>
</div>

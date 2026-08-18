<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($survey) && !empty($survey['id'])): ?>
                <?=$survey['name']; ?>
                <span>
                    <?=lang('Survey.SurveyEdit'); ?>
                </span>
            <?php else: ?>
                <?=lang('Survey.NewSurveyAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form survey-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey/<?php echo $action; ?><?=!empty($survey['id']) ? '/' . $survey['id'] : '' ; ?>" method="post">
            <?php if(!empty($survey['result'])): ?>
                <div class="form-row nag">
                    <h3><?=lang('Survey.SurveyResults'); ?></h3>
                </div>
                <div class="chart-box">
                    <div class="chart" id="survey-chart"></div>
                </div>
            <?php endif; ?>
            <div class="form-row nag">
                <h3><?=lang('Survey.SurveySettings'); ?></h3>
            </div>
            <input type="hidden" name="name" value="<?=!empty($survey) && !empty($survey['name']) ? $survey['name'] : ''; ?>" />
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
                                    <label><?=lang('Survey.Question');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][question]" value="<?= !empty($survey['lang']) ? esc($survey['lang'][$lang['id']]['question']) : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Survey.Description');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="lang[<?=$lang['id']; ?>][description]" ><?= !empty($survey['lang']) ? esc($survey['lang'][$lang['id']]['description']) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Survey.SurveyDateRange'); ?></label>
                </div>
                <div class="form-field">
                    <input class="datepicker-range" type="text" name="date" value="<?=!empty($survey) && !empty($survey['date']) ? $survey['date'] : ''; ?>" autocomplete="off" />
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Survey.SingleChoice');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="single" <?php if(!empty($survey['single']) && $survey['single']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Survey.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($survey['publish']) && $survey['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag with-btn">
                <h3><?=lang('Survey.Options'); ?></h3>
                <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey/option-add" class="btn add-option" title=""><?=lang('Survey.option.AddOption');?></a>
            </div>
            <div class="order-box options-box">
                <?php if(!empty($survey['options'])): ?>
                    <?php foreach($survey['options'] as $no=>$option): ?>
                        <?=view('Modules\Survey\Views\admin\add_option', array('option'=>$option, 'no'=>$no)); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?=view('Modules\Survey\Views\admin\add_option', array('no'=>0)); ?>
                <?php endif; ?>
            </div>            
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Survey.Save');?></button>
            </div>     
        </form>
    </div>
</div>

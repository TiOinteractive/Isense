<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($city) && !empty($city['id'])): ?>
                <?= $city['name']; ?>
                <span><?= lang('Event.EventCityEdit'); ?></span>
            <?php else: ?>
                <?= lang('Event.NewEventCityAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form event-city-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/<?php echo $action; ?><?= !empty($city['id']) ? '/' . $city['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Event.BasicInformation'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l = 0; foreach($languages as $lang): ?>
                            <div class="tab<?= $l==0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                <?php $l = 0; foreach($languages as $lang): ?>
                    <div class="link-box lang-<?= $lang['id']; ?> tab-item<?= $l==0 ? ' active' : ''; ?>">
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Name'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][name]" value="<?= !empty($city['lang']) && !empty($city['lang'][$lang['id']]) ? esc($city['lang'][$lang['id']]['name']) : ''; ?>" />
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Event.EventCitySettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($city['publish']) && $city['publish']): ?>checked="checked"<?php endif; ?> value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Event.Save'); ?></button>
            </div>
        </form>
    </div>
</div>

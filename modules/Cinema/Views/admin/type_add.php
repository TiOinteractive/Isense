<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($type) &&!empty($type['id'])): ?>
                <?= $type['name']; ?>
            <span>
            <?= lang('Cinema.TypeEdit'); ?>
            </span>
            <?php else: ?>
            <?= lang('Cinema.NewTypeAdd'); ?>
        <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form cinema-type-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/cinema/<?php echo $action; ?><?= !empty($type['id']) ? '/' . $type['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Cinema.BasicInformation'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l = 0;
                        foreach($languages as $lang): ?>
                        <div class="tab<?= $l==0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                        <?php ++$l;
                        endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                <?php $l = 0; foreach($languages as $lang): ?>
                    <div class="link-box lang-<?= $lang['id']; ?> tab-item<?= $l==0 ? ' active' : ''; ?>">
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Name'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][name]" value="<?= !empty($type['lang']) ? esc($type['lang'][$lang['id']]['name']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.DirectLink'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-id" type="hidden" name="lang[<?= $lang['id']; ?>][id_link]" value="<?= !empty($type['lang']) ? $type['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                <input class="link-id-lang" type="hidden" value="<?= $lang['id']; ?>" />
                                <input class="link-direct-links" type="hidden" value="<?=$direct_links[$lang['id']]; ?>" />
                                <input class="link-field" type="text" name="lang[<?= $lang['id']; ?>][link]" value="<?= !empty($type['lang']) ? esc($type['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Content'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea class="wyswig-textarea" name="lang[<?= $lang['id']; ?>][content]"><?= !empty($type['lang']) ? $type['lang'][$lang['id']]['content'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Cinema.TypeSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.TypeSlugs'); ?></label>
                </div>
                <div class="form-field">
                    <textarea name="slugs"><?= !empty($type) ? $type['slugs'] : ''; ?></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($type['publish']) && $type['publish']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Cinema.Save'); ?></button>
            </div>
        </form>
    </div>
</div>

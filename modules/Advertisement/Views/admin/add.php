<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($advertisement) &&!empty($advertisement['id'])): ?>
                <?= $advertisement['name']; ?>
            <span>
            <?= lang('Advertisement.AdvertisementEdit'); ?>
            </span>
            <?php else: ?>
            <?= lang('Advertisement.NewAdvertisementAdd'); ?>
        <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form event-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/advertisement/<?php echo $action; ?><?= !empty($advertisement['id']) ? '/' . $advertisement['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Advertisement.BasicInformation'); ?></h3>
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
                                <label><?= lang('Advertisement.Name'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][name]" value="<?= !empty($advertisement['lang']) ? esc($advertisement['lang'][$lang['id']]['name']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Advertisement.Code'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="lang[<?= $lang['id']; ?>][code]"><?= !empty($advertisement['lang']) ? $advertisement['lang'][$lang['id']]['code'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?=lang('Advertisement.Photo');?></label>
                            </div>
                            <div class="form-field">
                                <div class="files-list">
                                    <?php if(!empty($advertisement['lang']) && !empty($advertisement['lang'][$lang['id']]) && !empty($advertisement['lang'][$lang['id']]['photo'])): ?>
                                        <?=view('admin/filemenager/files_list', array('files'=>array($advertisement['lang'][$lang['id']]['photo']), 'name'=>'lang[' . $lang['id'] . ']','key_name'=>'photo')); ?>
                                    <?php endif; ?>
                                </div>
                                <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Advertisement.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="photo" data-type="image" data-field-name="lang[<?= $lang['id']; ?>]" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Advertisement.AddChangePhoto');?></a>
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Advertisement.AdvertisementSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Advertisement.Source'); ?></label>
                </div>
                <div class="form-field">
                    <select name="source">
                        <option value=""></option>
                        <option value="revive"<?php if(!empty($advertisement['source']) && $advertisement['source'] == 'revive'): ?> selected="selected"<?php endif; ?>><?=lang('Advertisement.source.revive'); ?></option>
                        <option value="adsense"<?php if(!empty($advertisement['source']) && $advertisement['source'] == 'adsense'): ?> selected="selected"<?php endif; ?>><?=lang('Advertisement.source.adsense'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Advertisement.Type'); ?></label>
                </div>
                <div class="form-field">
                    <select name="type">
                        <option value=""><?=lang('Advertisement.type.Default'); ?></option>
                        <option value="background"<?php if(!empty($advertisement['type']) && $advertisement['type'] == 'background'): ?> selected="selected"<?php endif; ?>><?=lang('Advertisement.type.background'); ?></option>
                        <option value="before_enter"<?php if(!empty($advertisement['type']) && $advertisement['type'] == 'before_enter'): ?> selected="selected"<?php endif; ?>><?=lang('Advertisement.type.before_enter'); ?></option>
                        <option value="popup"<?php if(!empty($advertisement['type']) && $advertisement['type'] == 'popup'): ?> selected="selected"<?php endif; ?>><?=lang('Advertisement.type.popup'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Advertisement.Pages'); ?></label>
                </div>
                <div class="form-field">
                    <?php if(!empty($pages)): ?>
                        <?php foreach($pages as $p): ?>
                            <div>
                                <input id="pages-<?=$p['id_content']; ?>" type="checkbox" name="pages[]" value="<?=$p['id_content']; ?>">
                                <label for="pages-<?=$p['id_content']; ?>"><?=(!empty($p['tree_name']) ? $p['tree_name'] : '') . $p['name'] . ' [' . (!empty($p['content_name']) ? $p['content_name'] . ' - ' : '') . '' . lang('Admin.page_content.Tab') . ' ' . ($p['order'] + 1) . ']'; ?></label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Advertisement.ExternalID'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="external_id" value="<?=!empty($advertisement) && !empty($advertisement['external_id']) ? $advertisement['external_id'] : ''; ?>" class="short" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Advertisement.AdvertisementDateRange'); ?></label>
                </div>
                <div class="form-field">
                    <input class="datepicker-range short" type="text" name="date" value="<?=!empty($advertisement) && !empty($advertisement['date']) ? $advertisement['date'] : ''; ?>" autocomplete="off" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Advertisement.Url'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="url" value="<?=!empty($advertisement) && !empty($advertisement['url']) ? $advertisement['url'] : ''; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Advertisement.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($advertisement['publish']) && $advertisement['publish']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Advertisement.Save'); ?></button>
            </div>
        </form>
    </div>
</div>

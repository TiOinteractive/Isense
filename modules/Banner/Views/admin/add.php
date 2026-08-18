<div class="main-cont">
    <?php if (isset($breadcrumbs)) {
        echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if (!empty($banner) && !empty($banner['id'])): ?>
                <?=$banner['name']; ?>
                <span><?= lang('Banner.BanerEdit'); ?></span>
            <?php else: ?>
            <?= lang('Banner.NewBanerAdd'); ?>
        <?php endif; ?>
        </div>
<?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form slider-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/<?php echo $action; ?><?= !empty($id) ? '/' . $id : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Banner.BannerSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Banner.Zones'); ?></label>
                </div>
                <div class="form-field">
                    <?php
                    if ($zones) {
                        ?>
                        <select name="banner[zone]">
                            <?php
                            foreach ($zones as $zone) {
                                echo '<option value="' . $zone['id'] . '"';
                                if (isset($banner['id_zone']) and $zone['id'] == $banner['id_zone']) {
                                    echo ' selected="selected"';
                                } echo '>' . $zone['lang']->name . '</option>';
                            }
                            ?>
                        </select>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?= lang('Banner.SetDate'); ?></label>
                    </div>
                    <div class="form-field">
                        <input type="checkbox" name="banner[dates]" <?php if (!empty($banner['date_start']) && $banner['date_start']): ?>checked="checked"<?php endif; ?>  id="show-range" onclick="show_hide('#show-range', '#date-range-row');" value="1" >
                    </div>
                </div>
                <div  id="date-range-row" <?php if (empty($banner['date_start'])) {
                                echo 'class="hide"';
                            } ?>>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Banner.DateFromTo'); ?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" id="date-range" class="date-range" name="banner[data]" value="<?php if(isset($banner['date_range'])) { echo $banner['date_range']; } ?>" data-start="<?php if(isset($banner['date_start'])) { echo date("d / m / Y",strtotime($banner['date_start'])); } else { echo date("d / m / Y");}?>" data-end="<?php if(isset($banner['date_end'])) { echo date("d / m / Y",strtotime($banner['date_end'])); } else { echo date("d / m / Y");}?>" data-min="<?= date("d/m/Y"); ?>" /> 
                            <p><input type="checkbox" name="banner[date_single]" id="range-single" <?php if (isset($banner['date_start']) and $banner['date_end'] == Null) {
                                echo ' checked="checked" ';
                            } ?>onclick="single_date('#date-range-row');" value="1" data-min="<?php if(isset($banner['date_start'])) { echo date("d / m / Y",strtotime($banner['date_start'])); } else { echo date("d / m / Y");}?>" > <?= lang('Banner.DateSingle'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?= lang('Banner.Publish'); ?></label>
                    </div>
                    <div class="form-field">
                        <input type="checkbox" name="banner[publish]" <?php if (!empty($banner['publish']) && $banner['publish']): ?>checked="checked"<?php endif; ?>  value="1" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="tabs">
                        <?php if(!empty($languages) && count($languages) > 1): ?>
                        <div class="tabs-head">
    <?php $l = 0;
    foreach ($languages as $lang): ?>
                                <div class="tab<?= $l == 0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
        <?php ++$l;
    endforeach; ?>
                        </div>
                        <div class="tabs-content">
                        <?php endif; ?>
    <?php $l = 0;
    foreach ($languages as $lang): ?>
                                <div class="tab-item<?= $l == 0 ? ' active' : ''; ?>">
                                    <div class="form-row">
                                        <div class="form-label">
                                            <label><?= lang('Banner.Name'); ?></label>
                                        </div>
                                        <div class="form-field">
                                            <input type="text" name="banner[lang][<?= $lang['id']; ?>][name]" value="<?= !empty($banner['lang']) ? $banner['lang'][$lang['id']]['name'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <label><?= lang('Banner.slide.Caption'); ?></label>
                                        </div>
                                        <div class="form-field">
                                            <input type="text" name="banner[lang][<?= $lang['id']; ?>][caption]" value="<?= !empty($banner['lang']) ? $banner['lang'][$lang['id']]['caption'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <label><?= lang('Banner.slide.Url'); ?></label>
                                        </div>
                                        <div class="form-field">
                                            <input type="text" name="banner[lang][<?= $lang['id']; ?>][url]" value="<?= !empty($banner['lang']) ? $banner['lang'][$lang['id']]['url'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <label><?= lang('Banner.slide.Photo'); ?></label>
                                        </div>
                                        <div class="form-field">
                                            <div class="files-list">
                                                <?php if (!empty($banner['lang'][$lang['id']]['file'])): ?>
                                                    <?= view('admin/filemenager/files_list', array('files' => array($banner['lang'][$lang['id']]['file']), 'name' => 'banner[lang][' . $lang['id'] . ']', 'key_name' => 'file')); ?>
                                                <?php endif; ?>
                                            </div>
                                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?= lang('Banner.slide.AddFile'); ?>" class="btn file-menager"  data-multi="false" data-key="file" data-type="image" data-field-name="banner[lang][<?= $lang['id']; ?>]" data-title="<?= lang('Admin.file-menager.FileMenager'); ?>" data-btn-ok="<?= lang('Admin.file-menager.Select'); ?>" data-btn-cancel="<?= lang('Admin.file-menager.Cancel'); ?>"><?= lang('Banner.slide.AddFile'); ?></a>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <label><?= lang('Banner.slide.PhotoMobile'); ?></label>
                                        </div>
                                        <div class="form-field">
                                            <div class="files-list">
        <?php if (!empty($banner['lang'][$lang['id']]['file_mobile'])): ?>
            <?= view('admin/filemenager/files_list', array('files' => array($banner['lang'][$lang['id']]['file_mobile']), 'name' => 'banner[lang][' . $lang['id'] . ']', 'key_name' => 'file_mobile')); ?>
        <?php endif; ?>
                                            </div>
                                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?= lang('Slider.slide.AddFile'); ?>" class="btn file-menager" data-multi="false" data-key="file_mobile" data-type="image" data-field-name="banner[lang][<?= $lang['id']; ?>]" data-title="<?= lang('Admin.file-menager.FileMenager'); ?>" data-btn-ok="<?= lang('Admin.file-menager.Select'); ?>" data-btn-cancel="<?= lang('Admin.file-menager.Cancel'); ?>"><?= lang('Banner.slide.AddFile'); ?></a>
                                        </div>
                                    </div>
                                </div>
        <?php ++$l;
    endforeach; ?>
                    <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
                </div>
            </div>	
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Banner.UrlTarget'); ?></label>
                </div>
                <div class="form-field">
                    <select name="banner[url_target]">
                        <option value=""></option>
                        <option value="_blank"<?php if(!empty($banner['url_target']) && $banner['url_target'] == '_blank'): ?> selected="selected"<?php endif; ?>><?= lang('Banner.url_target.Blank'); ?></option>
                        <option value="_self"<?php if(!empty($banner['url_target']) && $banner['url_target'] == '_self'): ?> selected="selected"<?php endif; ?>><?= lang('Banner.url_target.Self'); ?></option>
                        <option value="_parent"<?php if(!empty($banner['url_target']) && $banner['url_target'] == '_parent'): ?> selected="selected"<?php endif; ?>><?= lang('Banner.url_target.Parent'); ?></option>
                        <option value="_top"><?php if(!empty($banner['url_target']) && $banner['url_target'] == '_top'): ?> selected="selected"<?php endif; ?><?= lang('Banner.url_target.Top'); ?></option>
                    </select>
                </div>
            </div>	
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Banner.UrlRel'); ?></label>
                </div>
                <div class="form-field">
                    <select name="banner[url_rel]">
                        <option value=""></option>
                        <option value="ugc"<?php if(!empty($banner['url_rel']) && $banner['url_rel'] == 'ugc'): ?> selected="selected"<?php endif; ?>><?= lang('Banner.url_rel.Ugc'); ?></option>
                        <option value="sponsored"<?php if(!empty($banner['url_rel']) && $banner['url_rel'] == 'sponsored'): ?> selected="selected"<?php endif; ?>><?= lang('Banner.url_rel.Sponsored'); ?></option>
                        <option value="nofollow"<?php if(!empty($banner['url_rel']) && $banner['url_rel'] == 'nofollow'): ?> selected="selected"<?php endif; ?>><?= lang('Banner.url_rel.Nofollow'); ?></option>
                        <option value="follow"<?php if(!empty($banner['url_rel']) && $banner['url_rel'] == 'follow'): ?> selected="selected"<?php endif; ?>><?= lang('Banner.url_rel.Follow'); ?></option>
                    </select>
                </div>
            </div>	
            <div class="form-row submit">
                <button type="submit" class=""><?php if($action=="edit") { echo lang('Banner.side.SaveBaner');} else { echo lang('Banner.side.AddBaner'); } ?></button>
            </div>  	
    </div>

</form>
</div>
</div>

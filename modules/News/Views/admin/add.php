<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($news) && !empty($news['id'])): ?>
                <?=$news['name']; ?>
                <span><?=lang('News.NewsEdit'); ?></span>
            <?php else: ?>
                <?=lang('News.NewNewsAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form news-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/news/<?php echo $action; ?>/<?=$id_content; ?><?=!empty($news['id']) ? '/' . $news['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('News.BasicInformation'); ?></h3>
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
                        <div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.Title');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][title]" value="<?=!empty($news['lang']) ? esc($news['lang'][$lang['id']]['title']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.SubTitle');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][subtitle]" value="<?=!empty($news['lang']) ? esc($news['lang'][$lang['id']]['subtitle']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.Header');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][header]" value="<?=!empty($news['lang']) ? esc($news['lang'][$lang['id']]['header']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.DirectLink');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-page-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_page]" value="<?=!empty($page) ? $page['id_page'] : ''; ?>" />
                                    <input class="link-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_link]" value="<?=!empty($news['lang']) ? $news['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                    <input class="link-id-lang" type="hidden" value="<?=$lang['id']; ?>" />
                                    <input class="link-field" type="text" name="lang[<?=$lang['id']; ?>][link]" value="<?=!empty($news['lang']) && !empty($news['lang'][$lang['id']]['link']['link']) ? esc($news['lang'][$lang['id']]['link']['link']) : ''; ?>"<?php if(empty($news['lang']) || empty($news['lang'][$lang['id']]['redirect']) || empty($news['lang'][$lang['id']]['link']['redirect'])): ?> readonly="readonly"<?php endif; ?> />
                                    <input class="link-synchronize" type="checkbox" name="lang[<?=$lang['id']; ?>][link_sync]" value="1" id="link-<?=$lang['id']; ?>-synchronize" data-link="<?=!empty($news['lang']) && empty($news['lang'][$lang['id']]) && empty($news['lang'][$lang['id']]['link']['sync']) ? esc($news['lang'][$lang['id']]['link']['sync']) : ''; ?>"<?php if((!empty($news['lang']) && !empty($news['lang'][$lang['id']]['link']) && !empty($news['lang'][$lang['id']]['link']['sync'])) || empty($news['id'])): ?> checked="checked"<?php endif; ?><?php if(!empty($news['lang']) && !empty($news['lang'][$lang['id']]['link']) && !empty($news['lang'][$lang['id']]['link']['redirect'])): ?> disabled="disabled"<?php endif; ?> /><label for="link-<?=$lang['id']; ?>-synchronize"><?=lang('News.SynchronizeLink'); ?></label>
                                    <input class="link-redirect" type="checkbox" name="lang[<?=$lang['id']; ?>][link_redirect]" value="1" id="link-<?=$lang['id']; ?>-redirect" data-link="<?=!empty($news['lang']) && !empty($news['lang'][$lang['id']]['link']['redirect']) ? esc($news['lang'][$lang['id']]['link']['redirect']) : ''; ?>"<?php if(!empty($news['lang']) && !empty($news['lang'][$lang['id']]['link']) && !empty($news['lang'][$lang['id']]['link']['redirect'])): ?> checked="checked"<?php endif; ?> /><label for="link-<?=$lang['id']; ?>-redirect"><?=lang('News.RedirectLink'); ?></label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.Introduction');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="lang[<?=$lang['id']; ?>][introduction]"><?=!empty($news['lang']) ? esc($news['lang'][$lang['id']]['introduction']) : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.Content');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][content]"><?=!empty($news['lang']) ? $news['lang'][$lang['id']]['content'] : ''; ?></textarea>
                                    <div class="shortcodes">
                                        <code>
                                            [news id="1,2,3"]
                                        </code>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.Author');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][author]" value="<?=!empty($news['lang']) ? esc($news['lang'][$lang['id']]['author']) : lang('News.user.EditorialStaff'); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.InformationSource');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][source]" value="<?=!empty($news['lang']) ? esc($news['lang'][$lang['id']]['source']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.Tags');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][tags]" class="tags_input" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/tags/searchtags/<?=$id_content; ?>" value="<?=!empty($news['lang']) ? esc($news['lang'][$lang['id']]['tags']) : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('News.Metatags'); ?></h3>
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
                                    <label><?=lang('News.MetaTitle');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="meta[lang][<?=$lang['id']; ?>][title]" value="<?=!empty($news['meta']['lang']) ? $news['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.MetaDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][description]"><?=!empty($news['meta']['lang']) ? $news['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('News.MetaKeywords');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][keywords]"><?=!empty($news['meta']['lang']) ? $news['meta']['lang'][$lang['id']]['keywords'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('News.Scripts'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.JSDOMLoadedScript');?></label>
                </div>
                <div class="form-field">
                    <textarea name="script"><?=!empty($news['script']) ? $news['script'] : ''; ?></textarea>
                </div>
            </div>
            
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('News.NewsMedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.PrimaryPhoto');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($news['photo'])): ?>
                            <?=view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $news['photo'], 'multi' => false,'crop'=>true)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photo" data-crop="true" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Photos');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($news['photos'])): ?>
                            <?php foreach($news['photos'] as $k=>$photo): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'photos', 'file' => $photo, 'multi' => true, 'no' => $k,'crop'=>false)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photos" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Audio');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($news['audio'])): ?>
                            <?php foreach($news['audio'] as $k=>$audio): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'audio', 'file' => $audio, 'multi' => true, 'no' => $k,'crop'=>false)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="audio" data-field="audio" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Video');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($news['video'])): ?>
                            <?php foreach($news['video'] as $k=>$video): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'video', 'file' => $video, 'multi' => true, 'no' => $k,'crop'=>false)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="video" data-field="video" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('News.NewsSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Date');?></label>
                </div>
                <div class="form-field">
                    <input class="datepicker-date" type="text" name="date" value="<?=!empty($news['date']) ? date('d.m.Y', strtotime($news['date'])) : date('d.m.Y'); ?>" autocomplete="off" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Template');?></label>
                </div>
                <div class="form-field">
                    <?php if(!empty($templates)): ?>
                        <div class="templates-box">
                            <?php foreach($templates as $k=>$template): ?>
                                <div class="template-item">
                                    <input id="template-<?=$k; ?>" type="radio" name="template" value="<?=$template['file']; ?>"<?php if(!empty($news) && !empty($news['template']) && $news['template'] == $template['file']) { echo ' checked="checked"';} elseif(empty($news['template']) and $k==0) { echo ' checked="checked"';} ?> />
                                    <label for="template-<?=$k; ?>"><img src="/adm/img/news/<?=$template['file']; ?>.png" alt="<?=$template['name']; ?>" /><?=$template['name']; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.SelectAsHome');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="home" <?php if((empty($news['id']) && $id_content != 13) || (!empty($news['home']) && $news['home'])):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
			 <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.PublishDate');?></label>
                </div>
                <div class="form-field">
                    <input class="datetimepicker-date" type="text" name="publish_date" value="<?=!empty($news['publish_date']) ? date('d.m.Y H:i', strtotime($news['publish_date'])) : ''; ?>" autocomplete="off" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(empty($news['id']) || (!empty($news['publish']) && $news['publish'])):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Investments');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="investments" <?php if(!empty($news['investments']) && $news['investments']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Slider');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="slider" <?php if(!empty($news['slider']) && $news['slider']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Patronate');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="patronate" <?php if(!empty($news['patronate']) && $news['patronate']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
			 <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.ShowDontMiss');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="dont_miss" <?php if(!empty($news['dont_miss']) && $news['dont_miss']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
			    <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.Comment');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="comment" <?php if((!empty($news['comment']) && $news['comment']) || empty($news['id'])):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.ShowInBox');?></label>
                </div>
                <div class="form-field">
                    <select name="show_in_box">
                        <option value="" <?php if(empty($news['show_in_box'])):?>selected="selected"<?php endif; ?>><?=lang('News.ChooseBox');?></option>
                        <option value="1" <?php if(!empty($news['show_in_box']) && $news['show_in_box']==1):?>selected="selected"<?php endif;?>>Big Box 1</option>
                        <option value="2" <?php if(!empty($news['show_in_box']) && $news['show_in_box']==2):?>selected="selected"<?php endif;?>>Box 2</option>
						<option value="3" <?php if(!empty($news['show_in_box']) && $news['show_in_box']==3):?>selected="selected"<?php endif;?>>Box 3</option>
						<option value="4" <?php if(!empty($news['show_in_box']) && $news['show_in_box']==4):?>selected="selected"<?php endif;?>>Box 4</option>
						<option value="5" <?php if(!empty($news['show_in_box']) && $news['show_in_box']==5):?>selected="selected"<?php endif;?>>Box 5</option>
						<option value="6" <?php if(!empty($news['show_in_box']) && $news['show_in_box']==6):?>selected="selected"<?php endif;?>>Box 6</option>
                     </select>
                </div>
            </div>
            <?php if(!empty($page['moveTo'])): ?>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('News.MoveTo');?></label>
                    </div>
                    <div class="form-field">
                        <select name="move_to">
                            <option value=""  selected="selected"><?= lang('News.SelectSection'); ?></option>
                            <?php foreach($page['moveTo'] as $section): ?>
                                <option value="<?= $section['id']; ?>"><?= $section['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('News.Save'); ?></button>
            </div>
        </form>
    </div>
</div>
<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($movie) &&!empty($movie['id'])): ?>
                <?= $movie['name']; ?>
            <span>
            <?= lang('Cinema.CinemaMovieEdit'); ?>
            </span>
            <?php else: ?>
            <?= lang('Cinema.NewCinemaMovieAdd'); ?>
        <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form cinema-movie-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/cinema/<?php echo $action; ?>/<?=$id_content; ?><?= !empty($movie['id']) ? '/' . $movie['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Cinema.BasicInformation'); ?></h3>
            </div>
            <input type="hidden" name="name" value="<?=!empty($movie) &&!empty($movie['id']) ? $movie['name'] : ''; ?>" />
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
                                <label><?= lang('Cinema.Title'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][title]" value="<?= !empty($movie['lang']) ? esc($movie['lang'][$lang['id']]['title']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.OriginalTitle'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lang['id']; ?>][original]" value="<?= !empty($movie['lang']) ? esc($movie['lang'][$lang['id']]['original']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.DirectLink'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-page-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_page]" value="<?=!empty($page) ? $page['id_page'] : ''; ?>" />
                                <input class="link-id" type="hidden" name="lang[<?= $lang['id']; ?>][id_link]" value="<?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                <input class="link-id-lang" type="hidden" value="<?= $lang['id']; ?>" />
                                <input class="link-field" type="text" name="lang[<?= $lang['id']; ?>][link]" value="<?= !empty($movie['lang']) ? esc($movie['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Introduction'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="lang[<?= $lang['id']; ?>][introduction]"><?= !empty($movie['lang']) ? esc($movie['lang'][$lang['id']]['introduction']) : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Content'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea class="wyswig-textarea" name="lang[<?= $lang['id']; ?>][content]"><?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['content'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Director'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lang['id']; ?>][director]" value="<?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['director'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Scenario'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lang['id']; ?>][scenario]" value="<?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['scenario'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Actors'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lang['id']; ?>][actors]" value="<?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['actors'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Country'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lang['id']; ?>][country]" value="<?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['country'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Distributor'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lang['id']; ?>][distributor]" value="<?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['distributor'] : ''; ?>" />
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Cinema.Metatags'); ?></h3>
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
                    <div class="tab-item<?= $l==0 ? ' active' : ''; ?>">
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.MetaTitle'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="meta[lang][<?= $lang['id']; ?>][title]" value="<?= !empty($movie['meta']['lang']) ? $movie['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.MetaDescription'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="meta[lang][<?= $lang['id']; ?>][description]"><?= !empty($movie['meta']['lang']) ? $movie['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Cinema.CinemaMedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Poster'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($movie['poster'])): ?>
                            <?= view('admin/filemenager/upload_file', array('field' => 'poster', 'file' => $movie['poster'], 'multi' => false)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="poster" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.PrimaryPhoto'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($movie['photo'])): ?>
                            <?= view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $movie['photo'], 'multi' => false)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photo" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Photos'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($movie['photos'])): ?>
                            <?php foreach($movie['photos'] as $k => $photo): ?>
                                <?= view('admin/filemenager/upload_file', array('field' => 'photos', 'file' => $photo, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photos" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Cinema.CinemaMovieSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.VideoUrl'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="video_url" value="<?=!empty($movie['video_url']) && $movie['video_url'] ? $movie['video_url'] : ''; ?>" />
                    <span class="s">(YouTube, Vimeo, Dailymotion lub bezpośredni adres pliku)</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Genre'); ?></label>
                </div>
                <div class="form-field">
                     <?php if(!empty($genres)): ?>
                        <div class="form-cols">
                            <?php foreach($genres as $genre): ?>
                                <div class="form-col col-2">
                                    <input id="genre-<?= $genre['id']; ?>" type="checkbox" name="genres[]" value="<?= $genre['id']; ?>"<?= !empty($movie) && !empty($movie['genres']) && in_array($genre['id'], $movie['genres']) ? ' checked="checked"' : ''; ?> />
                                    <label for="genre-<?= $genre['id']; ?>"><?= $genre['name']; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Type'); ?></label>
                </div>
                <div class="form-field">
                    <?php if(!empty($types)): ?>
                        <div class="form-cols">
                            <?php foreach($types as $type): ?>
                                <div class="form-col col-2">
                                    <input id="type-<?= $type['id']; ?>" type="checkbox" name="types[]" value="<?= $type['id']; ?>"<?= !empty($movie) && !empty($movie['types']) && in_array($type['id'], $movie['types']) ? ' checked="checked"' : ''; ?> />
                                    <label for="type-<?= $type['id']; ?>"><?= $type['name']; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Template'); ?></label>
                </div>
                <div class="form-field">
                    <select name="template">
                        <?php if(!empty($templates)): ?>
                            <?php foreach($templates as $template): ?>
                                <option value="<?= $template['file']; ?>"<?= !empty($movie) && $movie['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?= $template['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.ProductionYear'); ?></label>
                </div>
                <div class="form-field">
                    <input type="number" name="production" value="<?=!empty($movie['production']) && $movie['production'] ? $movie['production'] : ''; ?>" min="0" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Duration'); ?></label>
                </div>
                <div class="form-field">
                    <input type="number" name="duration" value="<?=!empty($movie['duration']) && $movie['duration'] ? $movie['duration'] : ''; ?>" min="0" /> <?=lang('Cinema.Minute'); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Age'); ?></label>
                </div>
                <div class="form-field">
                    <input type="number" name="age" value="<?=!empty($movie['age']) && $movie['age'] ? $movie['age'] : ''; ?>" min="0" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Recommended'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="recommended" <?php if(!empty($movie['recommended']) && $movie['recommended']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.ForKids'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="for_kids" <?php if(!empty($movie['for_kids']) && $movie['for_kids']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Patronage'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="patronage" <?php if(!empty($movie['patronage']) && $movie['patronage']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.DontMiss'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="dont_miss" <?php if(!empty($movie['dont_miss']) && $movie['dont_miss']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.SelectAsHome'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="home" <?php if(!empty($movie['home']) && $movie['home']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Cinema.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($movie['publish']) && $movie['publish']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Cinema.Save'); ?></button>
            </div>
        </form>
    </div>
</div>

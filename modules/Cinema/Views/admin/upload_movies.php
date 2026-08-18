<div class="file-box"<?php if($multi): ?>data-no="<?=!empty($no) ? $no : 0; ?>"<?php endif; ?>>
    <div class="file">
        <a href="#" class="remove-file filemenager-file-remove" title="<?=lang('Admin.file-menager.Delete');?>" data-title="<?=lang('Admin.file-menager.DeleteFile');?>" data-message="<?=lang('Admin.file-menager.ConfirmInfo') . ': <strong>' . $file['basename'] . '</strong>';?>" data-btn-ok="<?=lang('Admin.file-menager.Delete');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><i class="fa-solid fa-xmark"></i></a>
        <input type="checkbox" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[publish]"<?=!empty($file['publish']) && $file['publish'] ? 'checked="checked"' : ''; ?> value="1" /> <label class="name"><?= $file['basename']; ?></label>
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[id]" value="<?= !empty($file['id']) ? $file['id'] : ''; ?>" />
        <input class="order-field" type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[order]" value="<?= !empty($file['order']) ? $file['order'] : ''; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[path]" value="<?= $file['path']; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[name]" value="<?= $file['name']; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[basename]" value="<?= $file['basename']; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[type]" value="<?= $file['type']; ?>" />
        <input type="hidden" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[ext]" value="<?= $file['ext']; ?>" />
        <div class="flex">
            <div class="preview">
                <?php if(!empty($file['type']) && $file['type'] == 'image'): ?>
                    <a href="/image/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                        <img src="/image/c/250/250/<?=$file['path']; ?>" alt="<?= $file['name']; ?>" />
                    </a>
                <?php else: ?>
                    <?php if(!empty($file['type']) && $file['type'] == 'audio'): ?>
                        <a href="/audio/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php elseif(!empty($file['type']) && $file['type'] == 'video'): ?>
                        <a href="/video/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php else: ?>
                        <a href="/file/<?= $file['path']; ?>" title="<?=lang('Admin.file-menager.Preview'); ?>" target="_blank">
                    <?php endif; ?>
                    <span class="ext"><?=!empty($file['ext']) ? $file['ext'] : '&nbsp;'; ?></span>
                    </a>
                <?php endif; ?>
            </div>
            <div class="fields">
                <div class="tabs sm">
                    <div class="tabs-head">
                        <?php if(!empty($languages) && count($languages) > 1): ?>
                            <?php $l=0; foreach($languages as $lang): ?>
                            <div class="tab<?=$l==0 ? ' active' : ''; ?>"><?=$lang['short_name']; ?></div>
                            <?php ++$l; endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="tabs-content">
                        <?php $l=0; foreach($languages as $lang): ?>
                            <div class="lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Cinema.Title');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][title]" value="<?=!empty($movie['lang']) ? esc($movie['lang'][$lang['id']]['title']) : ''; ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Cinema.Content');?></label>
                                    </div>
                                    <div class="form-field">
                                        <textarea class="wyswig-textarea" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][content]"><?=!empty($movie['lang']) ? esc($movie['lang'][$lang['id']]['content']) : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Cinema.Director');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][director]" value="<?=!empty($movie['lang']) ? esc($movie['lang'][$lang['id']]['director']) : ''; ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?= lang('Cinema.Scenario'); ?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][scenario]" value="<?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['scenario'] : ''; ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?= lang('Cinema.Actors'); ?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][actors]" value="<?= !empty($movie['lang']) ? $movie['lang'][$lang['id']]['actors'] : ''; ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Cinema.Country');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][country]" value="<?=!empty($movie['lang']) ? esc($movie['lang'][$lang['id']]['country']) : ''; ?>" />
                                    </div>
                                </div>
                            </div>
                        <?php ++$l; endforeach; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?= lang('Cinema.VideoUrl'); ?></label>
                    </div>
                    <div class="form-field">
                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[video_url]" value="<?=!empty($movie['video_url']) && $movie['video_url'] ? $movie['video_url'] : ''; ?>" />
                        <span class="s">(YouTube, Vimeo, Dailymotion lub bezpośredni adres pliku)</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Cinema.Genre');?></label>
                    </div>
                    <div class="form-field">
                        <div class="form-cols">
                            <?php if(!empty($genres)): ?>
                                <?php foreach($genres as $genre): ?>
                                    <div class="form-col col-2">
                                        <input type="checkbox" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[genres][]" value="<?= $genre['id']; ?>" id="<?=$field; ?>-<?=$multi ? (!empty($no) ? $no : 0) : ''; ?>-genres-<?= $genre['id']; ?>" <?= !empty($movie) && !empty($movie['genres']) && in_array($genre['id'], $movie['genres']) ? ' checked="checked"' : ''; ?> />
                                        <label for="<?=$field; ?>-<?=$multi ? (!empty($no) ? $no : 0) : ''; ?>-genres-<?= $genre['id']; ?>"><?= $genre['name']; ?></label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Cinema.ProductionYear');?></label>
                    </div>
                    <div class="form-field">
                        <input type="number" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[production]" value="<?=!empty($movie['production']) ? esc($movie['production']) : ''; ?>" min="0" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Cinema.Duration');?></label>
                    </div>
                    <div class="form-field">
                        <input type="number" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[duration]" value="<?=!empty($movie['duration']) ? esc($movie['duration']) : ''; ?>" min="0" /> <?=lang('Cinema.Minute'); ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?= lang('Cinema.Age'); ?></label>
                    </div>
                    <div class="form-field">
                        <input type="number" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[age]" value="<?=!empty($movie['age']) && $movie['age'] ? $movie['age'] : ''; ?>" min="0" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?= lang('Cinema.ForKids'); ?></label>
                    </div>
                    <div class="form-field">
                        <input type="checkbox" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[for_kids]" <?php if(!empty($movie['for_kids']) && $movie['for_kids']): ?>checked="checked"<?php endif; ?>  value="1" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
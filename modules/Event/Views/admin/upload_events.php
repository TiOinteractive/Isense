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
                                        <label><?=lang('Event.Name');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][name]" value="<?=!empty($event['lang']) ? esc($event['lang'][$lang['id']]['name']) : ''; ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Event.SubName');?></label>
                                    </div>
                                    <div class="form-field">
                                        <input type="text" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][subname]" value="<?=!empty($event['lang']) ? esc($event['lang'][$lang['id']]['subname']) : ''; ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Event.Content');?></label>
                                    </div>
                                    <div class="form-field">
                                        <textarea class="wyswig-textarea" name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][content]"><?=!empty($event['lang']) ? esc($event['lang'][$lang['id']]['content']) : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Event.Tickets');?></label>
                                    </div>
                                    <div class="form-field">
                                        <textarea name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][tickets]"><?=!empty($event['lang']) ? esc($event['lang'][$lang['id']]['tickets']) : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">
                                        <label><?=lang('Event.TicketPrice');?></label>
                                    </div>
                                    <div class="form-field">
                                        <textarea name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[lang][<?=$lang['id']; ?>][price]"><?=!empty($event['lang']) ? esc($event['lang'][$lang['id']]['price']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php ++$l; endforeach; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Event.Type');?></label>
                    </div>
                    <div class="form-field">
                        <select name="<?=$field; ?><?=$multi ? '[' . (!empty($no) ? $no : 0) . ']' : ''; ?>[type]" >
                            <?php if(!empty($types)): ?>
                                <?php foreach($types as $type): ?>
                                    <option value="<?= $type['id']; ?>"<?= !empty($event) && !empty($event['type']) && $type['id'] == $event['type'] ? ' selected="selected"' : ''; ?>><?= $type['name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
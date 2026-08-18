<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.Option');?></label>
    </div>
    <div class="form-field">
        <select name="config[option]">
            <option value="0"><?=lang('News.All'); ?></option>
            <option value="home"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='home'):?> selected="selected"<?php endif; ?>><?=lang('News.SelectedAsHome'); ?></option>
            <option value="investments"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='investments'):?> selected="selected"<?php endif; ?>><?=lang('News.SelectedAsInvestments'); ?></option>
            <option value="slider"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='slider'):?> selected="selected"<?php endif; ?>><?=lang('News.SelectedAsSlider'); ?></option>
            <option value="notslider"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='notslider'):?> selected="selected"<?php endif; ?>><?=lang('News.SelectedAsNotSlider'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.Lists');?></label>
    </div>
    <div class="form-field">
        <div class="form-cols">
            <?php if(!empty($lists)): ?>
                <?php foreach($lists as $list): ?>
                    <div class="form-col col-10">
                        <input type="checkbox" name="config[lists][]" value="<?=$list['id_content']; ?>" id="config-lists-<?=$list['id_content']; ?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['lists']) && in_array($list['id_content'], $page_content['config']['lists'])):?> checked="checked"<?php endif; ?> />
                        <label for="config-lists-<?=$list['id_content']; ?>"><?=(!empty($list['tree_name']) ? $list['tree_name'] : '') . $list['name'] . ' [' . (!empty($list['content_name']) ? $list['content_name'] . ' - ' : '') . '' . lang('Admin.page_content.Tab') . ' ' . ($list['order'] + 1) . ']'; ?></label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.Tags');?></label>
    </div>
    <div class="form-field">
        <div class="scroll-list-box">
            <input type="text" class="scroll-list-search" placeholder="<?=lang('News.SearchTag'); ?>" autocomplete="off" />
            <div class="form-cols scroll-list">
                <?php if(!empty($tags)): ?>
                    <?php foreach($tags as $tag): ?>
                        <div class="form-col col-10">
                            <input type="checkbox" name="config[tags][]" value="<?=$tag['id']; ?>" id="config-tags-<?=$tag['id']; ?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['tags']) && in_array($tag['id'], $page_content['config']['tags'])):?> checked="checked"<?php endif; ?> />
                            <label for="config-tags-<?=$tag['id']; ?>"><?=esc($tag['tag']); ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderLatest'); ?></option>
			<option value="latest_sponsored"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest_sponsored'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderLatestSponsored'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderRandom'); ?></option>
            <option value="date"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='date'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderDate'); ?></option>
            <option value="order"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='order'):?> selected="selected"<?php endif; ?>><?=lang('News.Order'); ?></option>
            <option value="most_readed"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='most_readed'):?> selected="selected"<?php endif; ?>><?=lang('News.MostReaded'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.ItemsPerPage');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.Pagination');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[pagination]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['pagination']) && $page_content['config']['pagination'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>
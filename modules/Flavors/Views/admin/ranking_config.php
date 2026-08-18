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
        <label><?=lang('Flavors.MinimumRanking');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[min_ranks]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['min_ranks']) ? $page_content['config']['min_ranks'] : ''; ?>" />
    </div>
</div>
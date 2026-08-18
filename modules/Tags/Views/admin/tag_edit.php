<form class="form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/Tags/savetag/<?=$tag['id'];?>">
<div class="form-row" style="margin:0px;">
<div class="form-field" style="width:100%;">
    <?php if(!empty($tag['tag'])): ?>
        <input type="text" name="tag" value="<?=$tag['tag'];?>" />
    <?php endif; ?>
</div>					
</div>
</form>
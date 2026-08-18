
<div class="form-row">
    <div class="form-label">
        <label><?= lang('Event.Type'); ?></label>
    </div>
    <div class="form-field">
        <select name="config[types][]" multiple="multiple">
           <?php if(!empty($types)): ?>
                <?php foreach($types as $k=>$t): ?>
                    <?= view('\Modules\Event\Views\admin/event_place_type_select_parents', array('type'=>$t, 'id_parent'=>!empty($place['id_type']) ? $place['id_type'] : 0, 'count'=>count($types), 'item_no'=>$k+1)); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
</div>